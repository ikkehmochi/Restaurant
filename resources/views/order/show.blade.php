@extends('app.master')
<style>
    .order-info {
        font-size: 10px;
    }

    .order-info td {
        height: 10px;
    }
</style>
@section('content')
<table class="table table-borderless m-0 order-info">
    <thead>
        <tr>
            <th style="width: 15%"></th>
            <th style="width: 35%"></th>
            <th style="width: 15%"></th>
            <th style="width: 35%"></th>
        </tr>
    </thead>
    <tr>
        <td>Order Id</td>
        <td>: 01</td>
        <td>Tanggal</td>
        <td>: 21-09-2003</td>
    </tr>
    <tr>
        <td>Nama</td>
        <td>: Anon</td>
        <td>Metode Pembayaran</td>
        <td>: Cash</td>
    </tr>
    <tr>
        <td>Meja</td>
        <td>: TR02</td>
        <td>Status Pembayaran</td>
        <td>: Berhasil</td>
    </tr>
    <tr>
        <td>Order Status</td>
        <td>: YESSIR</td>
        <td></td>
        <td></td>
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
        <tr>
            <td>waw</td>
        </tr>


    </tbody>
</table>
@endsection