<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService,
        private readonly CartService $cartService,
    ) {}

    public function show()
    {
        $items = $this->cartService->getCartItems(Auth::id());

        if (empty($items)) {
            return redirect()->route('frontend.cart')
                ->with('error', 'Your cart is empty.');
        }

        return view('frontend.checkout', ['cartItems' => $items]);
    }

    public function store(CheckoutRequest $request)
    {
        $items = $this->cartService->getCartItems(Auth::id());

        if (empty($items)) {
            return back()->withErrors(['items' => 'Your cart is empty.'])->withInput();
        }

        $order = $this->orderService->createOrderFromCart(Auth::user(), $request->validated(), $items);

        $this->cartService->clearCart(Auth::id());

        return redirect()->route('frontend.checkout.confirmation', $order->order_number)
            ->with('success', 'Order placed successfully! Pay on delivery.');
    }

    public function confirmation(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->with(['items.product', 'payments'])
            ->firstOrFail();

        return view('frontend.confirmation', compact('order'));
    }
}
