<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use NunoMaduro\Collision\Provider;
use App\Models\Cart;
use App\Models\Product;
use DB;


class KhaltiController extends Controller
{
    public function verify(Request $request)
    {
        // $cart = Cart::where('user_id', auth()->user()->id)->where('order_id', null)->get()->toArray();

        // $data = [];

        // // return $cart;
        // $data['items'] = array_map(function ($item) use ($cart) {
        //     $name = Product::where('id', $item['product_id'])->pluck('title');
        //     return [
        //         'name' => $name,
        //         'price' => $item['price'],
        //         'desc'  => 'Thank you for using khalti',
        //         'qty' => $item['quantity']
        //     ];
        // }, $cart);

        // $data['invoice_id'] = 'ORD-' . strtoupper(uniqid());
        // $data['invoice_description'] = "Order #{$data['invoice_id']} Invoice";
        // $data['return_url'] = route('payment.success');
        // $data['cancel_url'] = route('payment.cancel');

        // $total = 0;
        // foreach ($data['items'] as $item) {
        //     $total += $item['price'] * $item['qty'];
        // }

        // $data['total'] = $total;
        // if (session('coupon')) {
        //     $data['shipping_discount'] = session('coupon')['value'];
        // }
        // Cart::where('user_id', auth()->user()->id)->where('order_id', null)->update(['order_id' => session()->get('id')]);

        // // return session()->get('id');
        // $provider = new ExpressCheckout;

        // $response = $provider->setExpressCheckout($data);

        // return redirect($response['paypal_link']);

        $args = http_build_query(array(
            'token' => $request->token,
            'amount'  => 1000
        ));

        $url = "https://khalti.com/api/v2/payment/verify/";

        # Make the call using API.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $headers = ['Authorization: Key test_secret_key_3a41767d2d2d4dda85e840bb72ef4291'];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Response
        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($status_code == 2000) {
            return response()->json(['success' => 1, 'redirecto' => route('orders')]);
        } else {
            return response()->json(['error' => 1, 'message' => 'Payement Failed']);
        }
    }
}
