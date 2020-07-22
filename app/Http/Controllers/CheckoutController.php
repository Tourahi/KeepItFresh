<?php

namespace App\Http\Controllers;

use DateTime;
use App\Order;
use App\Product;
use App\InfoLivraison;
use App\listeNoire;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Gloudemans\Shoppingcart\Facades\Cart;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $InfoLivraison = InfoLivraison::select('type' , 'prix')->where('id', Auth()->user()->InfoLivraison_Id)->get();
        $IsBanned      = listeNoire::select('user_name')->where('user_name' ,Auth()->user()->name)->get();

        if (Cart::subtotal() > OffLivraison())
        {
            $InfoLivraison[0]->prix = 0;
        }

        // dd(Cart::count());

        if(isset($IsBanned[0]->user_name))
        {
            return redirect()->route('products.index')->with('error', 'Vous êtes banni pour le moment merci de consulter un de nos admins.');
        }
        // dd(getPrixLivraison());
        if (Cart::count() <= 0) {
            return redirect()->route('products.index');
        }

        Stripe::setApiKey('sk_test_Xeqsz7wjiDw6VmPzlj2BvDJe00MVLMpfln');

        if(request()->session()->get('remise'))
        {
            $total = Cart::subtotal() - request()->session()->get('remise')['discount'] + (Cart::subtotal() - request()->session()->get('remise')['discount']) * (config('cart.tax') / 100 ) + $InfoLivraison[0]->prix;
        }
        else
        {
            $total = Cart::total() + $InfoLivraison[0]->prix;
        }

        $intent = PaymentIntent::create([
            'amount' => round($total),
            'currency' => 'mad'
        ]);

        $clientSecret = Arr::get($intent, 'client_secret');

        if (Cart::count() > 10)
        {
            $total  = $total - ($total * 0.1);
        }

        return view('checkout.index', [
            'clientSecret' => $clientSecret,
            'total'        => $total
        ]);
    }

    public function indexLivraison()
    {
        $InfoLivraison = InfoLivraison::select('type' , 'prix')->where('id', Auth()->user()->InfoLivraison_Id)->get();
        $IsBanned      = listeNoire::select('user_name')->where('user_name' ,Auth()->user()->name)->get();

        if (Cart::subtotal() > OffLivraison())
        {
            $InfoLivraison[0]->prix = 0;
        }

        // dd(Cart::count());

        if(isset($IsBanned[0]->user_name))
        {
            return redirect()->route('products.index')->with('error', 'Vous êtes banni pour le moment merci de consulter un de nos admins.');
        }
        // dd(getPrixLivraison());
        if (Cart::count() <= 0) {
            return redirect()->route('products.index');
        }

        Stripe::setApiKey('sk_test_Xeqsz7wjiDw6VmPzlj2BvDJe00MVLMpfln');

        if(request()->session()->get('remise'))
        {
            $total = Cart::subtotal() - request()->session()->get('remise')['discount'] + (Cart::subtotal() - request()->session()->get('remise')['discount']) * (config('cart.tax') / 100 ) + $InfoLivraison[0]->prix;
        }
        else
        {
            $total = Cart::total() + $InfoLivraison[0]->prix;
        }

        $intent = PaymentIntent::create([
            'amount' => round($total),
            'currency' => 'mad'
        ]);

        $clientSecret = Arr::get($intent, 'client_secret');

        if (Cart::count() > 10)
        {
            $total  = $total - ($total * 0.1);
        }

        return view('checkout.indexLivraison', [
            'clientSecret' => $clientSecret,
            'total'        => $total
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$this->isAvailable()) {
            Session::flash('error' , 'Un produit dans votre panier n\'est plus disponible.');
        return response()->json(['success' => false],400);
        }
        $data = $request->json()->all();

        $order = new Order();

        $order->payment_intent_id = $data['paymentIntent']['id'];
        $order->amount = $data['paymentIntent']['amount'];

        $order->payment_created_at = (new DateTime())
            ->setTimestamp($data['paymentIntent']['created'])
            ->format('Y-m-d H:i:s');

        $products = [];
        $i = 0;

        foreach (Cart::content() as $product) {
            $products['product_' . $i][] = $product->model->title;
            $products['product_' . $i][] = $product->model->price;
            $products['product_' . $i][] = $product->qty;
            $i++;
        }
        // dd($product);

        $order->products = serialize($products);
        $order->user_id = Auth()->user()->id;
        $order->save();

        if ($data['paymentIntent']['status'] === 'succeeded') {
            $this->updateStok();
            Cart::destroy();
            Session::flash('success', 'Votre commande a été traitée avec succès.');
            return response()->json(['success' => 'Payment Intent Succeeded']);
        } else {
            return response()->json(['error' => 'Payment Intent Not Succeeded']);
        }
    }

    public function storeLivr(Request $request)
    {
        if(!$this->isAvailable()) {
            Session::flash('error' , 'Un produit dans votre panier n\'est plus disponible.');
        return response()->json(['success' => false],400);
        }
        $data = $request->json()->all();

        $order = new Order();

        $order->payment_intent_id = getUniqueValue();

        $InfoLivraison = InfoLivraison::select('type' , 'prix')->where('id', Auth()->user()->InfoLivraison_Id)->get();
        if(request()->session()->get('remise'))
        {
            $total = Cart::subtotal() - request()->session()->get('remise')['discount'] + (Cart::subtotal() - request()->session()->get('remise')['discount']) * (config('cart.tax') / 100 ) + $InfoLivraison[0]->prix;
        }
        else
        {
            $total = Cart::total() + $InfoLivraison[0]->prix;
        }
        $order->amount = $total;


        $order->payment_created_at = (new DateTime())
            ->format('Y-m-d H:i:s');

        $products = [];
        $i = 0;

        foreach (Cart::content() as $product) {
            $products['product_' . $i][] = $product->model->title;
            $products['product_' . $i][] = $product->model->price;
            $products['product_' . $i][] = $product->qty;
            $i++;
        }
        // dd($product);

        $order->products = serialize($products);
        $order->user_id = Auth()->user()->id;
        $order->save();


        $this->updateStok();
        Cart::destroy();
        Session::flash('success', 'Votre commande a été traitée avec succès.');
        return redirect()->route('checkout.thankYou');
    }

    public function thankyou()
    {
        return Session::has('success') ? view('checkout.thankyou') : redirect()->route('products.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function isAvailable()
    {
        foreach (Cart::content() as $prd) {
            $product = Product::find($prd->model->id);

            if ($product->stock < $prd->qty) {
                return false;
            }
        }

        return true;
    }

    private function updateStok()
    {
        foreach (Cart::content() as $prd) {
            $product = Product::find($prd->model->id);

            $product->update(['stock' => $product->stock - $prd->qty]);
        }
    }
}


function getUniqueValue()
{
	return rand(10,100000000000000000);
}