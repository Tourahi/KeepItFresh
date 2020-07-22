@extends('layouts.master')


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
  @foreach ($products as $product)
    <div class="col-md-6">
      <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
        <div class="col p-4 d-flex flex-column position-static">
          <small class="d-inline-block mb-2">

      <div class="card-header shadow">
            @foreach ($product->categories as $category)
                {{ $category->name }}{{ $loop->last ? '' : ', '}}
            @endforeach
      </div>

          </small>
          <h5 class="mb-0">{{ $product->title }}</h5>
          <p class="mb-auto text-muted">{{ $product->subtitle }}</p>
          <strong class="mb-auto font-weight-bald text-success">{{ $product->getPrice() }}</strong>
          <a href="{{ route('products.show', $product->slug) }}" class="stretched-link btn btn-secondary">Consulter le produit</a>
        </div>
        <div class="col-auto d-none d-lg-block">
          <img src="{{ file_exists(public_path('storage/'.$product->image)) ?  asset('storage/'.$product->image) : $product->image }}" alt="">
        </div>
      </div>
    </div>
  @endforeach
  {{ $products->appends(request()->input())->links() }}


@endsection
