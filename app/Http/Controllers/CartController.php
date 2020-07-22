<?php

namespace App\Http\Controllers;

use App\Product;
use App\Remise;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('cart.index');
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
        //Recturn collection
        $duplicata = Cart::search(function ($cartItem, $rowId) use ($request) {
            return $cartItem->id == $request->product_id;
        });

        if ($duplicata->isNotEmpty()) {
            return redirect()->route('products.index')->with('success', 'Le produit a déjà été ajouté.');
        }

        $product = Product::find($request->product_id);

        Cart::add($product->id, $product->title, 1, $product->price)
            ->associate('App\Product');

        return redirect()->route('products.index')->with('success', 'Le produit a bien été ajouté.');
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
    public function update(Request $request, $rowId)
    {
        $data = $request->json()->all();
        
        $validates = Validator::make($request->all(), [
            'quantity' => 'numeric|required|between:1,10',
        ]);

        if ($validates->fails()) {
            Session::flash('error', 'La quantité doit est comprise entre 1 et 10.');
            return response()->json(['error' => 'Out of stock']);
        }

        if ($data['quantity'] > $data['stock'])
        {
            // store items in the session only for the next request
            Session::flash('error', 'Stock non disponible.');
            return response()->json(['error' => 'Cart Quantity Has Not Been Updated']);            
        }

        Cart::update($rowId, $data['quantity']);

        Session::flash('success', 'La quantité du produit est passée à ' . $data['quantity'] . '.');
        return response()->json(['success' => 'Cart Quantity Has Been Updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($rowId)
    {
        Cart::remove($rowId);

        return back()->with('success', 'Le produit a été supprimé.');
    }

    //Gestion remises

    public function storeremise(Request $request)
    {
        $code  = $request->get('code');
        $remiseCode      = Remise::where('code',$code)->first();
        $CurrentUserCode = Remise::where('id',Auth()->user()->Remise_Id)->first();

        if ($remiseCode == null && $code == "")//Test si champ vide 
        {
            request()->session()->forget('remise');
            return redirect()->back()->with('success', 'Le Code de remise a été retiré ou le champ est vide.');
        }

        if(!$remiseCode || !$CurrentUserCode || $CurrentUserCode->code != $remiseCode->code) //Si le code donner == CurrentUserCode 
        {
            return redirect()->back()->with('error' , 'Votre code est invalide Ou pas le votre.');
        }

        //add remise to the session
        $request->session()->put('remise' , [

            'code' => $remiseCode->code,
            'discount' =>$remiseCode->discount(Cart::subtotal())

        ]);

        return redirect()->back()->with('success', 'Le Code de remise est appliqué.');

    }

}
