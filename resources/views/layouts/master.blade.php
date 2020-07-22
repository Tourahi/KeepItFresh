<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @yield('extra-meta')  {{-- For added meta --}}

    <title>{{getAppName()}}</title>
    <script src="{{ asset('js/app.js') }}" defer></script>
    @yield('extra-script')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/ecommerce.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:700,900" rel="stylesheet">
    @yield('style')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  </head>

  <body>


  
</div>
  <div class="container" class="app">
    <header class="blog-header pt-3">
      <div class="row flex-nowrap justify-content-between align-items-center">
        <div class="col-4 pt-1">
          <a class="text-muted" href="{{ route('cart.index') }}">Panier <span class="badge badge-pill badge-dark text-white">{{ Cart::count() }}</span></a>
        </div>
        <div class="col-4 text-center">
          <a class="text-dark" style="font-size: 300%; text-decoration:none; " href="{{ route('products.index') }}">{{GetAppName()}}</a>
          <div style="position:absolute; left:70px;">
              @include('partials.search')
          </div>
          
        </div>
        <div class="col-4 d-flex justify-content-end align-items-center">
          @include('partials.auth')
        </div>
      </div>
    </header>

  <div class="nav-scroller py-1 mb-2">
    <nav class="nav d-flex justify-content-between">
      @foreach (App\Category::all() as $category)
      <a class="p-2 text-muted" href="{{ route('products.index', ['categorie' => $category->slug]) }}">{{ $category->name }}</a>
      @endforeach
    </nav>
  </div>

  @if (session('success'))
      <div class="alert alert-success">
          {{ session('success') }}
      </div>
  @endif

    @if (session('error'))
      <div class="alert alert-danger">
          {{ session('error') }}
      </div>
    @endif


    @if (count($errors) > 0)
      <div class="alert alert-danger">
         <ul>
            @foreach ($errors->all() as $error)
                  <li>
                      {{$error}}
                  </li>
            @endforeach
         </ul>
      </div>
    @endif


  {{-- <div class="jumbotron p-4 p-md-5 text-white rounded bg-dark">
    <div class="col-md-6 px-0">
      <h1 class="display-4 font-italic">Title of a longer featured blog post</h1>
      <p class="lead my-3">Multiple lines of text that form the lede, informing new readers quickly and efficiently about what’s most interesting in this post’s contents.</p>
      <p class="lead mb-0"><a href="#" class="text-white font-weight-bold">Continue reading...</a></p>
    </div>
  </div> --}}


  {{-- Resultat de recherche --}}
  @if(request()->input())
      <h6>{{ $products->total() }} résultat(s) trouver</h6>
  @endif

  <div class="row mb-2">
  @yield('content')

  <footer>
        @yield('footer')
  </footer>
  </div>
</div>
@yield('extra-js')
</body>
</html>
