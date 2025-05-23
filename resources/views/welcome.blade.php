@extends('app.main')
@push('styles')

<style>
    a {
        text-decoration: none;
    }

    .card {
        height: 300px;
        width: 400px;
        align-items: center;
        display: flex;
        text-decoration: none;
    }

    .card img {
        height: 35%;
        margin: 5%;
    }

    .card-title {
        text-align: center;
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
</style>
@endpush

@section('content')

<div class="container title">
    <p class="text-title">Welcome to Enshoku Restaurant</p>
    <p class="text-muted textsubtitle">Please select a module.</p>
</div>
<div class="row text-center">
    <div class="col col-md-3">
        <a href={{ route('homepage.tableIndex') }}>
            <div class="card" style="width: 18rem;">
                <img src="{{ asset('icons/dining-tables.svg') }}" class="card-img-top" alt="...">
                <div class="card-body text-center">
                    <h5 class="card-title w-100">Dining Tables</h5> <br>
                    <p class="card-text">Manage dining tables, reservation, and seating</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col col-md-3">
        <a href="">
            <div class="card" style="width: 18rem;">
                <img src="{{ asset('icons/food-in.svg') }}" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title  w-100">Orders</h5> <br>
                    <p class="card-text">Track, modify, and process customers orders</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col col-md-3">
        <a href="">
            <div class="card" style="width: 18rem;">
                <img src="{{ asset('icons/chef-hat.svg') }}" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title w-100">Kitchen</h5> <br>
                    <p class="card-text">Monitor kitchen and order progress.</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col col-md-3">
        <a href={{ route('dashboard') }}>
            <div class="card" style="width: 18rem;">
                <img src="{{ asset('icons/admin.svg') }}" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title w-100">Admin</h5> <br>
                    <p class="card-text">Admin Dashboard.</p>
                </div>
            </div>
        </a>
    </div>

</div>

@endsection