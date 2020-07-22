<?php

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Gloudemans\Shoppingcart\Facades\Cart;


	function GetAppName()
	{
		return 	"🌱 KeepItFresh" ;
	}

	function _DebugAll()
	{
		dd('Cart :' ,Cart::content(),'Products : ',App\Product::all(),'Request : ',request());

	}

?>