<form action="{{ route('products.search') }}" class="d-flex">
    <div class="form-group mb-0 mr-1">
        <input type="text" name="query" class="form-control" value="{{ request()->q ?? '' }}">
    </div>
    <button type="submit" class="btn btn-dark"><i class="fa fa-search" aria-hidden="true"></i></button>
</form>
