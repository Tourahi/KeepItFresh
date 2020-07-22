@extends('layouts.master')

@section('extra-meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('extra-script')
<script src="https://js.stripe.com/v3/"></script>
@endsection

@section('content')
<div class="col-md-12">
    <a href="{{ route('cart.index') }}" class="btn btn-sm btn-secondary mt-3">Revenir au panier</a>
    <div class="row">
        <div class="col-md-6 mx-auto">
            <h4 class="text-center pt-5">Vous paierez lorsque vous recevrez les produits.</h4>
            <form action="{{ route('checkout.storeLivr') }}" method="POST" class="my-4" id="payment-form">
                @csrf
                <button class="btn btn-success btn-block mt-3" id="submit">
                    <i class="fa fa-credit-card" aria-hidden="true"></i> confirmer ({{ getPrice($total) }})
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('extra-js')
<script>
    //Suppression de la barre de navigation

    var submitButton = document.getElementById('submit');

    submitButton.addEventListener('click', function(ev) {
        var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        var form = document.getElementById('payment-form');
        var url = form.action;

        fetch(
                url,
                    {
                        headers: {
                            "Content-Type": "application/json",
                             "Accept": "application/json, text-plain, */*",
                             "X-Requested-With": "XMLHttpRequest",
                             "X-CSRF-TOKEN": token
                            },
                            method: 'post',
                            body: JSON.stringify({
                                paymentIntent: paymentIntent
                            })
                        }).then((data) => {
                            if(data.status == 400)
                            {
                            var redirect = 'http://localhost/keepitFresh/public/boutique'
                            }
                            else
                            {
                                var redirect = 'http://localhost/keepitFresh/public/merci';
                            }
                            console.log(data);
                            form.reset();
                            window.location.href = redirect;
            }).catch((error) => {
              console.log(error)
             })

});
</script>
@endsection
