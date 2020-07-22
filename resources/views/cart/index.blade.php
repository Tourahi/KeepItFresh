@extends('layouts.master')

@section('extra-meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection


@section('style')
  <style>
      .shadow
      {
        text-shadow: 0 1px 0 #ccc, 
               0 2px 0 #c9c9c9,
               0 3px 0 #bbb,
               0 4px 0 #b9b9b9,
               0 5px 0 #aaa,
               0 6px 1px rgba(0,0,0,.1),
               0 0 5px rgba(0,0,0,.1),
               0 1px 3px rgba(0,0,0,.3),
               0 3px 5px rgba(0,0,0,.2),
               0 5px 10px rgba(0,0,0,.25),
               0 10px 10px rgba(0,0,0,.2),
               0 20px 20px rgba(0,0,0,.15);
      }
  </style>
@endsection

@section('content')
@if (Cart::count() > 0)
<div class="px-4 px-lg-0">
    <div class="pb-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 p-5 bg-white rounded shadow-sm mb-5">
                    <!-- Shopping cart table -->
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>

                                <div class="card-header shadow ">
                                <th scope="col" class="border-0 bg-light shadow">
                                    <div class="p-2 px-3 text-uppercase">Produit</div>
                                </th>
                                <th scope="col" class="border-0 bg-light shadow">
                                    <div class="py-2 text-uppercase">Prix</div>
                                </th>
                                <th scope="col" class="border-0 bg-light shadow">
                                    <div class="py-2 text-uppercase">Quantité</div>
                                </th>
                                <th scope="col" class="border-0 bg-light shadow">
                                    <div class="py-2 text-uppercase">Supprimer</div>
                                </th>
                                </div>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (Cart::content() as $product)
                                <tr>
                                    <th scope="row" class="border-0">
                                        <div class="p-2">
                                            <div class="ml-3 d-inline-block align-middle">
                                                <h5 class="mb-0"> <a href="{{ route('products.show', ['slug' => $product->model->slug]) }}" class="text-dark d-inline-block align-middle" width="70">{{ $product->model->title }}</a></h5><span class="text-muted font-weight-normal font-italic d-block">KG</span>
                                            </div>
                                        </div>
                                    </th>
                                    <td class="border-0 align-middle"><strong>{{ getPrice($product->subtotal()) }}</strong></td>
                                    <td class="border-0 align-middle">
                                        <select class="custom-select" name="qty" id="qty" data-id="{{ $product->rowId }}" data-stock = "{{ $product->model->stock }}">
                                            @for ($i = 1; $i <= 10; $i++)
                                                <option value="{{ $i }}" {{ $product->qty == $i ? 'selected' : ''}}>
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </td>
                                    <td class="border-0 align-middle">
                                        <form action="{{ route('cart.destroy', $product->rowId) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- End -->
                </div>
            </div>
            <div class="row p-4 bg-white rounded shadow-sm">
                <div class="col-lg-6">
                    <div class="bg-light rounded-pill px-4 py-3 text-uppercase font-weight-bold">Code promo
                    </div>
                    @if(!request()->session()->has('remise'))
                       <div class="p-4">
                        <p class="font-italic mb-4">Si vous détenez un Code promo, entrez-le dans le champ ci-dessous</p>
                        <form action="{{ route('cart.store.remise') }}" methode="POST">

                        @csrf

                        <div class="input-group mb-4 border rounded-pill p-2">  
                          <input type="text" placeholder="Entrer votre code ici" name="code"
                              aria-describedby="button-addon3" class="form-control border-0">
                            <div class="input-group-append border-0">
                                <button id="button-addon3" type="submit" class="btn btn-dark px-4 rounded-pill">Appliquer le Code</button>
                            </div>
                        </div>
                      </form>
                    </div>
                    @else
                      <div class="p-4">
                        <p class="font-italic mb-4">
                          Un Code est deja appliqué.
                        </p>
                      </div>
                    @endif
                </div>
                <div class="col-lg-6">
                    <div class="bg-light rounded-pill px-4 py-3 text-uppercase font-weight-bold">Détails de la commande
                    </div>
                    <div class="p-4">
                        <p class="font-italic mb-4">livraison gratuite si vous achetez plus de 200 MAD de nos produits 😁.</p>
                        <p class="font-italic mb-4">Une remise de 10% si vous achetez une Quantité plus de 10 de nos produits.</p>
                        <ul class="list-unstyled mb-4">
                        <li class="d-flex justify-content-between py-3 border-bottom"><strong class="text-muted">Sous-total </strong><strong>{{ getPrice(Cart::subtotal()) }}</strong></li>

                        @if(request()->session()->has('remise'))
                        <li class="d-flex justify-content-between py-3 border-bottom"><strong class="text-muted">Code</strong><strong>{{ request()->session()->get('remise')['code'] }}</strong>
                        @endif
                        
                        <form action="{{ route('cart.destroy.remise') }}" methode="post">
                        @method('delete')

                        @if(request()->session()->has('remise'))

                          <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="fa fa-trash"></i>
                          </button>
                        </form>
                        </li>                    
                        <li class="d-flex justify-content-between py-3 border-bottom"><strong class="text-muted">Montant de la remise</strong><strong>{{ getPrice(request()->session()->get('remise')['discount'])}}</strong></li>
                        <li class="d-flex justify-content-between py-3 border-bottom"><strong class="text-muted">Taxe</strong>
                          <strong>
                              {{ getPrice((Cart::subtotal() - request()->session()->get('remise')['discount']) * (config('cart.tax') / 100 ) )}}
                          </strong></li>
                          <li class="d-flex justify-content-between py-3 border-bottom"><strong class="text-muted">Prix Livraison</strong>
                          <strong>
                                  {{ getPrixLivraison() }}
                          </strong></li>
                        <li class="d-flex justify-content-between py-3 border-bottom"><strong class="text-muted">Nouveau Total - ({{ getPrice(request()->session()->get('remise')['discount']) }})</strong>
                            <h5 class="font-weight-bold">{{ getPrice(getTotalWhitLivr2() - request()->session()->get('remise')['discount'] + (Cart::subtotal()- request()->session()->get('remise')['discount']) * (config('cart.tax') / 100 ))}}</h5>
                        @else
                        <li class="d-flex justify-content-between py-3 border-bottom"><strong class="text-muted">Taxe</strong><strong>{{ getPrice(Cart::tax()) }}</strong></li>
                         <li class="d-flex justify-content-between py-3 border-bottom"><strong class="text-muted">Prix Livraison</strong>
                          <strong>
                                {{ getPrixLivraison() }}
                          </strong></li>
                        <li class="d-flex justify-content-between py-3 border-bottom"><strong class="text-muted">Total</strong>
                            <h5 class="font-weight-bold">{{ getTotalWhitLivr() }}</h5>
                        </li>
                        @endif
                        </ul><a href="{{ route('checkout.index') }}" class="btn btn-dark rounded-pill py-2 btn-block"><i class="fa fa-credit-card" aria-hidden="true"></i> Passer à la caisse</a>
                      </ul><a href="{{ route('checkout.indexLivraison') }}" class="btn btn-dark rounded-pill py-2 btn-block">Paiement à la livraison</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="col-md-12">
    <h5>Votre panier est vide pour le moment.</h5>
    <p>Mais vous pouvez visiter la <a href="{{ route('products.index') }}">boutique</a> pour faire votre shopping.
    </p>
</div>
@endif

@endsection

@section('extra-js')

<script src="{{ asset('js/app.js') }}"></script> {{-- So i can use axios  --}}

<script>
    var qty = document.querySelectorAll('#qty');
    Array.from(qty).forEach((element) => {
        element.addEventListener('change', function () {
           var rowId = element.getAttribute('data-id');
           var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
           var stk = element.getAttribute('data-stock');
           
              axios.patch(`panier/${rowId}`, {
                  quantity : this.value,
                  stock : stk
              })
              .then(function (response) {
                console.log(response);
                location.reload();   
              })
              .catch(function (error) {
                    console.log(error);        
              });
           });
        });
</script>
@endsection
