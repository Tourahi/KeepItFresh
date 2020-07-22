<?php
use App\InfoLivraison;
use Gloudemans\Shoppingcart\Facades\Cart;

function getPrice($price)
{
	$price = floatval(preg_replace("/[^-0-9\.]/","",$price)) / 100;
	return number_format($price , 2, ',' , ' ')." MAD";
}

function getPrixLivraison()
{
	if(Cart::subtotal() < OffLivraison())
	{
		return getPrice(InfoLivraison::select('type' , 'prix')->where('id', Auth()->user()->InfoLivraison_Id)->get()[0]->prix);
	}
	return getPrice(0);
}
function getTotalWhitLivr()
{
	$total = Cart::total();
	if(Cart::subtotal() < OffLivraison()){
		$liv   = InfoLivraison::select('type' , 'prix')->where('id', Auth()->user()->InfoLivraison_Id)->get()[0]->prix;
	}
	else
	{
		$liv   = 0;
	}
	if (Cart::count() > 10)
	{
		$total  = $total - ($total * 0.1);
	}
	return getPrice($total + $liv);
}

function OffLivraison()
{
	return 20000;
}


function getTotalWhitLivr2()
{
	$total = Cart::subtotal();
	if(Cart::subtotal() < OffLivraison()){
		$liv   = InfoLivraison::select('type' , 'prix')->where('id', Auth()->user()->InfoLivraison_Id)->get()[0]->prix;
	}
	else
	{
		$liv   = 0;
	}
	if (Cart::count() > 10)
	{
		$total  = $total - ($total * 0.1);
	}

	return $total + $liv;
}


