<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="The Gift Shop — thoughtful gifts for every occasion.">
    <title>@yield('title', 'The Gift Shop')</title>

    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/main.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/fa/css/all.min.css') }}" rel="stylesheet">

    <style>
        body { background-color: #f8f9fa; }
        .navbar-brand i { font-size: 1.2rem; }
        .toast-container { z-index: 9999; }
        .product-card { transition: transform .2s, box-shadow .2s; position: relative; }
        .product-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,.12) !important; }
        .product-card .quick-view-btn,
        .product-card .add-to-cart-btn,
        .product-card .btn { position: relative; z-index: 2; }
        #products-grid.loading { opacity: .4; pointer-events: none; transition: opacity .2s; }
        .badge-stock { font-size: .7rem; }
        .footer-links a { color: rgba(255,255,255,.7); text-decoration: none; }
        .footer-links a:hover { color: #fff; }
        @media (max-width: 768px) {
            .navbar-brand { font-size: 1rem; }
            .btn-sm { font-size: .75rem; padding: .25rem .5rem; }
            h1 { font-size: 1.5rem; }
        }

        /* Chat Widget */
        #chat-fab { position:fixed; bottom:28px; right:28px; z-index:1050; width:52px; height:52px; border-radius:50%; background:#6f42c1; color:#fff; border:none; font-size:1.4rem; box-shadow:0 4px 16px rgba(0,0,0,.25); cursor:pointer; display:flex; align-items:center; justify-content:center; }
        #chat-box { position:fixed; bottom:92px; right:28px; z-index:1050; width:320px; max-width:95vw; background:#fff; border-radius:16px; box-shadow:0 8px 32px rgba(0,0,0,.18); display:none; flex-direction:column; overflow:hidden; }
        #chat-header { background:#6f42c1; color:#fff; padding:12px 16px; font-weight:600; display:flex; justify-content:space-between; align-items:center; }
        #chat-messages { height:280px; overflow-y:auto; padding:12px; display:flex; flex-direction:column; gap:8px; }
        .chat-msg { max-width:80%; padding:8px 12px; border-radius:12px; font-size:.875rem; line-height:1.4; }
        .chat-msg.bot { background:#f0e6ff; align-self:flex-start; border-bottom-left-radius:4px; }
        .chat-msg.user { background:#6f42c1; color:#fff; align-self:flex-end; border-bottom-right-radius:4px; }
        #chat-input-row { display:flex; border-top:1px solid #eee; }
        #chat-input { flex:1; border:none; padding:10px 12px; font-size:.875rem; outline:none; }
        #chat-send { background:#6f42c1; color:#fff; border:none; padding:0 16px; cursor:pointer; font-size:1rem; }
        #chat-send:hover { background:#5a32a3; }
    </style>
    @yield('head')
</head>

<body>

{{-- Toast Container --}}
<div class="toast-container position-fixed top-0 end-0 p-3">
    @if(session('success'))
    <div class="toast align-items-center text-bg-success border-0 show" role="alert">
        <div class="d-flex">
            <div class="toast-body"><i class="fa fa-check-circle me-2"></i>{{ session('success') }}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    @endif
    @if(session('error'))
    <div class="toast align-items-center text-bg-danger border-0 show" role="alert">
        <div class="d-flex">
            <div class="toast-body"><i class="fa fa-exclamation-circle me-2"></i>{{ session('error') }}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    @endif
    @if(session('info'))
    <div class="toast align-items-center text-bg-info border-0 show" role="alert">
        <div class="d-flex">
            <div class="toast-body"><i class="fa fa-info-circle me-2"></i>{{ session('info') }}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    @endif
    <div id="js-toast-container"></div>
</div>

{{-- Announcement Banner --}}
<div id="announcement-bar" style="background:#BF0A30; color:#fff; font-size:.85rem; padding:8px 0; text-align:center; position:relative;">
  <span>🎉 Free delivery on orders over ₦10,000! Use code <strong>FREESHIP</strong> at checkout.</span>
  <button onclick="document.getElementById('announcement-bar').style.display='none'" style="position:absolute;right:16px;top:50%;transform:translateY(-50%);background:none;border:none;color:#fff;font-size:1.1rem;cursor:pointer;line-height:1;">&#x2715;</button>
</div>

<header>
  <div class="navbar navbar-dark shadow-sm custom">
    <div class="container">
      <a href="{{ route('shop.index') }}" class="navbar-brand d-flex align-items-center gap-2">
        <i class="fa fa-gift"></i>
        <strong>The Gift Shop</strong>
      </a>

      <div class="d-flex align-items-center gap-2">
        @auth
          @if(auth()->user()->is_admin)
            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-light">
              <i class="fa fa-tachometer-alt"></i> <span class="d-none d-md-inline">Admin</span>
            </a>
          @else
            <a href="{{ route('user.dashboard') }}" class="btn btn-sm btn-outline-light">
              <i class="fa fa-list"></i> <span class="d-none d-md-inline">My Orders</span>
            </a>
            <a href="{{ route('user.profile') }}" class="btn btn-sm btn-outline-light">
              <i class="fa fa-user"></i> <span class="d-none d-md-inline">Profile</span>
            </a>
          @endif
          <span class="text-white d-none d-sm-inline small">{{ auth()->user()->name }}</span>
          <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-light">
              <i class="fa fa-sign-out-alt"></i> <span class="d-none d-md-inline">Logout</span>
            </button>
          </form>
        @else
          <a href="{{ route('register') }}" class="btn btn-sm btn-outline-light">
            <i class="fa fa-user-plus"></i> <span class="d-none d-md-inline">Register</span>
          </a>
          <a href="{{ route('login') }}" class="btn btn-sm btn-outline-light">
            <i class="fa fa-sign-in-alt"></i> <span class="d-none d-md-inline">Login</span>
          </a>
        @endauth

        <a href="{{ route('cart.index') }}" class="btn text-white position-relative">
          <i class="fa fa-shopping-cart"></i>
          <span id="cart-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark {{ ($cartCount ?? 0) > 0 ? '' : 'd-none' }}">
            {{ $cartCount ?? 0 }}
          </span>
        </a>
      </div>
    </div>
  </div>
</header>

<main>
    @yield('content')
</main>

<footer class="py-5 custom text-white mt-5">
  <div class="container">
    <div class="row">
      <div class="col-md-4 mb-4">
        <h5><i class="fa fa-gift me-2"></i>The Gift Shop</h5>
        <p class="small text-white-50">Every moment, you can still show your loved ones that you care. Thoughtful gifts for every occasion.</p>
      </div>
      <div class="col-md-4 mb-4 footer-links">
        <h6 class="text-uppercase fw-bold mb-3">Quick Links</h6>
        <div class="d-flex flex-column gap-1">
          <a href="{{ route('shop.index') }}">Shop</a>
          <a href="{{ route('cart.index') }}">Cart</a>
          @auth
            <a href="{{ route('user.dashboard') }}">My Orders</a>
            @if(!auth()->user()->is_admin)
            <a href="{{ route('user.profile') }}">My Profile</a>
            @endif
          @else
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}">Register</a>
          @endauth
        </div>
      </div>
      <div class="col-md-4 mb-4 footer-links">
        <h6 class="text-uppercase fw-bold mb-3">Contact</h6>
        <p class="small text-white-50 mb-1"><i class="fa fa-envelope me-2"></i>hello@thegiftshop.ng</p>
        <p class="small text-white-50 mb-1"><i class="fa fa-phone me-2"></i>+234 802 249 9993 </p>
        <p class="small text-white-50"><i class="fa fa-map-marker-alt me-2"></i>Lagos, Nigeria</p>
        <div class="d-flex gap-3 mt-3">
          <a href="https://www.instagram.com/?hl=en"><i class="fab fa-instagram fa-lg"></i></a>
          <a href="https://x.com/"><i class="fab fa-twitter fa-lg"></i></a>
          <a href="https://www.facebook.com/"><i class="fab fa-facebook fa-lg"></i></a>
        </div>
      </div>
    </div>
    <hr class="border-secondary">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
      <small class="text-white-50">&copy; {{ date('Y') }} The Gift Shop. All rights reserved.</small>
      <a href="#" onclick="window.scrollTo({top:0,behavior:'smooth'});return false;" class="text-white-50 small">Back to top ↑</a>
    </div>
  </div>
</footer>

{{-- AI Chat Widget --}}
<button id="chat-fab" title="Chat with us"><i class="fa fa-comment-dots"></i></button>

<div id="chat-box">
    <div id="chat-header">
        <span><i class="fa fa-robot me-2"></i>Gift Shop Assistant</span>
        <button onclick="toggleChat()" style="background:none;border:none;color:#fff;font-size:1.1rem;cursor:pointer;">&#x2715;</button>
    </div>
    <div id="chat-messages">
        <div class="chat-msg bot">Hi! I'm your gift shop assistant. Ask me about products, prices, or availability!</div>
    </div>
    <div id="chat-input-row">
        <input id="chat-input" type="text" placeholder="Type a message..." maxlength="500" />
        <button id="chat-send"><i class="fa fa-paper-plane"></i></button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Global toast helper
function showToast(message, type = 'success') {
    const colors = { success: 'text-bg-success', error: 'text-bg-danger', warning: 'text-bg-warning', info: 'text-bg-info' };
    const icons  = { success: 'fa-check-circle', error: 'fa-exclamation-circle', warning: 'fa-exclamation-triangle', info: 'fa-info-circle' };
    const el = document.createElement('div');
    el.className = `toast align-items-center ${colors[type] || colors.success} border-0 show mb-2`;
    el.setAttribute('role', 'alert');
    el.innerHTML = `<div class="d-flex"><div class="toast-body"><i class="fa ${icons[type] || icons.success} me-2"></i>${message}</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>`;
    document.getElementById('js-toast-container').appendChild(el);
    setTimeout(() => el.remove(), 4000);
}

function updateCartBadge(count) {
    const badge = document.getElementById('cart-badge');
    if (!badge) return;
    if (count > 0) { badge.textContent = count; badge.classList.remove('d-none'); }
    else { badge.classList.add('d-none'); }
}

// Auto-dismiss flash toasts
document.querySelectorAll('.toast').forEach(t => {
    setTimeout(() => { if (t.parentNode) t.remove(); }, 4000);
});

// Chat Widget
function toggleChat() {
    const box = document.getElementById('chat-box');
    box.style.display = box.style.display === 'flex' ? 'none' : 'flex';
    if (box.style.display === 'flex') document.getElementById('chat-input').focus();
}

document.getElementById('chat-fab').addEventListener('click', toggleChat);

function appendMsg(text, type) {
    const msgs = document.getElementById('chat-messages');
    const el = document.createElement('div');
    el.className = 'chat-msg ' + type;
    el.textContent = text;
    msgs.appendChild(el);
    msgs.scrollTop = msgs.scrollHeight;
    return el;
}

async function sendMessage() {
    const input = document.getElementById('chat-input');
    const msg = input.value.trim();
    if (!msg) return;

    appendMsg(msg, 'user');
    input.value = '';

    const typing = appendMsg('...', 'bot');

    try {
        const res = await fetch('{{ route("chat.reply") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ message: msg })
        });
        const data = await res.json();
        typing.textContent = data.reply || 'Sorry, something went wrong.';
    } catch (e) {
        typing.textContent = 'Network error. Please try again.';
    }

    document.getElementById('chat-messages').scrollTop = document.getElementById('chat-messages').scrollHeight;
}

document.getElementById('chat-send').addEventListener('click', sendMessage);
document.getElementById('chat-input').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') sendMessage();
});
</script>
@yield('scripts')
</body>
</html>
