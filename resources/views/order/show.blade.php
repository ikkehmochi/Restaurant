@extends('app.master')
<style>
    .order-info {
        font-size: 16px;
    }

    .order-info td {
        height: 10px;
    }
</style>
@section('content')
<table class="table table-borderless m-0 order-info">
    <thead>
        <tr>
            <th style="width: 20%"></th>
            <th style="width: 30%"></th>
            <th style="width: 20%"></th>
            <th style="width: 30%"></th>
        </tr>
    </thead>

    <tr>
        <td>Order Id</td>
        <td>: {{ $order->id }}</td>
        <td>Tanggal</td>
        <td>: {{ ($order->updated_at)->toDateString() }}</td>
    </tr>
    <tr>
        <td>Nama</td>
        <td>: {{ $order->customer_name }}</td>
        <td>Metode Pembayaran</td>
        <td>: {{ucfirst( $order->payment_method )}}</td>
    </tr>
    <tr>
        <td>Meja</td>
        <td>: {{$table->number }}</td>
        <td>Status Pembayaran</td>
        <td>: {{ucfirst( $order->payment_status) }}</td>
    </tr>
</table>

<table class="table text-center">
    <thead class="text-center">
        <tr>
            <th style="width: 20px">#</th>
            <th>Item</th>
            <th>Quantity</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($menus as $menu)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{ $menu->name }}</td>
            <td>{{ $menu->pivot->quantity }}</td>
            <td>{{ $menu->pivot->subtotal }}</td>
        </tr>

        @endforeach
    </tbody>
</table>

@endsection