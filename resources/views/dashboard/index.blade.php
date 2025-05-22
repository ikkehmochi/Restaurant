@extends('app.master')
@push('styles')

<style>
    a {
        text-decoration: none;
    }

    .card {
        height: 200px;
        align-items: center;
        justify-content: center;
        display: flex;
        text-decoration: none;
    }

    .card img {
        width: 128px;
        margin: 5%;
    }

    .card-title {
        display: flex;
        text-align: center;
        align-items: center;
        justify-content: center;
        height: 100%;
        width: 100%;
    }

    .title {
        text-align: center;
    }

    .title .text-title {
        font-size: 48px;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        font-weight: 600;
        margin-bottom: 0px;
    }

    .card {
        padding: 15px;
        /* JUST TO LOOK COOL */
        border: 1px solid #eee;
        /* JUST TO LOOK COOL */
        box-shadow: #324154 0px 2px 4px;
        transition: all .3s ease-in-out;
    }

    .card-title {
        font-size: 32px;
        font-weight: 500;
    }

    .card:hover {
        box-shadow: #324154 0px 19px 43px;

        transform: translate3d(0px, -1px, 0px);
        border: #324154;
        border-width: 10px;
    }

    .card-img-top {
        width: 128px;
    }
</style>
@endpush
@section('content')

<div class="row text-center">
    <div class="col col-md-3">
        <a href={{ route('tables.index') }}>
            <div class="card">
                <img src="{{ asset('icons/dining-tables.svg') }}" class="card-img-top" alt="...">
                <div class="card-body text-center">
                    <h5 class="card-title">Dining Tables</h5> <br>
                </div>
            </div>
        </a>
    </div>
    <div class="col col-md-3">
        <a href={{ route('menus.index') }}>
            <div class="card">
                <img src="{{ asset('icons/menu-book.svg') }}" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Menu</h5> <br>
                </div>
            </div>
        </a>
    </div>
    <div class="col col-md-3">
        <a href={{ route('orders.index') }}>
            <div class="card">
                <img src="{{ asset('icons/food-in.svg') }}" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title ">Orders</h5> <br>
                </div>
            </div>
        </a>
    </div>

    <div class="col col-md-3">
        <a href={{ route('ingredients.index') }}>
            <div class="card">
                <img src="{{ asset('icons/admin.svg') }}" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Ingredients</h5> <br>
                </div>
            </div>
        </a>
    </div>

</div>
@endsection