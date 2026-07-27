<?php

namespace App\Support;

class DeliveryFee
{
    private static array $fees = [
        'Lagos'       => 1500,
        'Ogun'        => 2000,
        'Oyo'         => 2500,
        'Osun'        => 2500,
        'Ondo'        => 2500,
        'Ekiti'       => 2500,
        'FCT'         => 2500,
        'Rivers'      => 3000,
        'Delta'       => 3000,
        'Edo'         => 3000,
        'Anambra'     => 3000,
        'Imo'         => 3000,
        'Enugu'       => 3000,
        'Abia'        => 3000,
        'Ebonyi'      => 3500,
        'Cross River' => 3500,
        'Akwa Ibom'   => 3500,
        'Bayelsa'     => 3500,
        'Kano'        => 3500,
        'Kaduna'      => 3500,
        'Kwara'       => 3000,
        'Kogi'        => 3000,
        'Benue'       => 3500,
        'Nasarawa'    => 3000,
        'Niger'       => 3500,
        'Plateau'     => 3500,
        'Gombe'       => 4000,
        'Bauchi'      => 4000,
        'Adamawa'     => 4000,
        'Taraba'      => 4000,
        'Borno'       => 4500,
        'Yobe'        => 4500,
        'Jigawa'      => 4000,
        'Katsina'     => 4000,
        'Kebbi'       => 4000,
        'Sokoto'      => 4500,
        'Zamfara'     => 4500,
    ];

    public static function for(string $state): int
    {
        return self::$fees[$state] ?? 3500;
    }

    public static function all(): array
    {
        return self::$fees;
    }
}
