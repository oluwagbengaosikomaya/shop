<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function reply(Request $request)
    {
        $request->validate(['message' => 'required|string|max:500']);

        $apiKey = config('services.gemini.key');

        if (empty($apiKey)) {
            return response()->json(['reply' => 'Gemini API key is not configured. Please set GEMINI_API_KEY in your .env file.']);
        }

        // Products context
        $products = Product::select('name', 'price', 'category', 'stock', 'description')->get();
        $productList = $products->map(fn($p) =>
            "- {$p->name} | ₦{$p->price} | Category: {$p->category} | Stock: {$p->stock} | {$p->description}"
        )->join("\n");

        // Order history context (only for logged-in users)
        $orderContext = '';
        if (auth()->check()) {
            $orders = Order::with('items')
                ->where('user_id', auth()->id())
                ->latest()
                ->take(5)
                ->get();

            if ($orders->isNotEmpty()) {
                $orderLines = $orders->map(function ($order) {
                    $items = $order->items->map(fn($i) => "{$i->product_name} x{$i->quantity}")->join(', ');
                    return "- Order #{$order->id} | Status: {$order->status} | Total: ₦{$order->total} | Items: {$items} | Date: {$order->created_at->format('M d, Y')}";
                })->join("\n");

                $orderContext = "\n\nCustomer's recent orders:\n{$orderLines}";
            } else {
                $orderContext = "\n\nThe customer has no orders yet.";
            }
        }

        $systemInstruction = "You are a helpful shopping assistant for \"The Gift Shop\", a Nigerian gift store. "
            . "Answer customer questions about products, availability, pricing, and orders. "
            . "Be friendly, concise, and helpful. Use ₦ for prices. "
            . "If asked about something outside the shop, politely redirect to shop-related topics.\n\n"
            . "Current products:\n{$productList}"
            . $orderContext;

        $response = Http::timeout(15)
            ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$apiKey}", [
                'system_instruction' => [
                    'parts' => [['text' => $systemInstruction]],
                ],
                'contents' => [
                    [
                        'role'  => 'user',
                        'parts' => [['text' => $request->message]],
                    ],
                ],
                'generationConfig' => [
                    'maxOutputTokens' => 300,
                    'temperature'     => 0.7,
                ],
            ]);

        if ($response->failed()) {
            Log::error('Gemini chat error', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            $error = $response->json('error.message') ?? 'Unknown error';
            return response()->json(['reply' => "AI error: {$error}"]);
        }

        $reply = $response->json('candidates.0.content.parts.0.text') ?? 'I could not understand that. Please try again.';

        return response()->json(['reply' => trim($reply)]);
    }
}
