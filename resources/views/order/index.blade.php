@extends('app.master')

<style>
    .user-image {
        width: 2rem;
        height: 2rem;
    }

    .order-pending {
        background-color: #6b757c;
        color: #ffffff;
    }

    .order-preparing {
        background-color: #fec200;
        color: black;
    }

    .order-served {
        background-color: #6BA8F2;
        color: #ffffff;
    }

    .order-completed {
        background-color: #198652;
        color: #ffffff;
    }

    .order-cancelled {
        background-color: #db3642;
        color: #ffffff;
    }

    .payment-paid {
        background-color: #198652;
        color: #ffffff;
    }

    .payment-unpaid {
        background-color: #6b757c;
        color: #ffffff;
    }

    .payment-failed {
        background-color: #db3642;
        color: #ffffff;
    }

    .payment-refunded {
        background-color: #fec200;
        color: black;
    }
</style>

@section('content')
<form id="filter" method="GET" class="mb-3">
    <div class="row g-3">
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input name="customer_name" type="text" autocomplete="off" class="form-control" id="customer_name" placeholder="Search By Customer..." value="{{ request('customer_name') }}">
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-filter"></i></span>
                <select name="table_id" class="form-select" id="table_id">
                    <option value="">All Tables</option>
                    @foreach($tables as $table)
                    <option value="{{ $table->id }}" {{ request('table_id')==$table->id?'selected':'' }}>{{ $table->number }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-filter"></i></span>
                <select name="status" class="form-select" id="status">
 
                    <option value="">All Status</option>
                    <option value="Pending" {{ request('status')=='Pending'?'selected':'' }}>Pending</option>
                    <option value="Preparing" {{ request('status')=='Preparing'?'selected':'' }}>Preparing</option>
                    <option value="Served" {{ request('status')=='Served'?'selected':'' }}>Served</option>
                    <option value="Completed" {{ request('status')=='Completed'?'selected':'' }}>Completed</option>
                    <option value="Cancelled" {{ request('status')=='Cancelled'?'selected':'' }}>Cancelled</option>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-filter"></i></span>
                <select name="payment_method" class="form-select" id="payment_method">
                    <option value="">All Payment Methods</option>
                    <option value="Cash" {{ request('payment_method')=='Cash'?'selected':'' }}>Cash</option>
                    <option value="Credit" {{ request('payment_method')=='Credit'?'selected':'' }}>Credit</option>
                    <option value="Debit" {{ request('payment_method')=='Debit'?'selected':'' }}>Debit</option>
                    <option value="QRIS" {{ request('payment_method')=='QRIS'?'selected':'' }}>QRIS</option>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-filter"></i></span>
                <select name="payment_status" class="form-select" id="payment_status">
                    <option value="">All Payment Statuses</option>
                    <option value="Unpaid" {{ request('payment_status')=='Unpaid'?'selected':'' }}>Unpaid</option>
                    <option value="Paid" {{ request('payment_status')=='Paid'?'selected':'' }}>Paid</option>
                    <option value="Refunded" {{ request('payment_status')=='Refunded'?'selected':'' }}>Refunded</option>
                    <option value="Failed" {{ request('payment_status')=='Failed'?'selected':'' }}>Failed</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                <input name="start_date" type="date" class="form-control" id="start_date" placeholder="Start Date" value="{{ request('start_date') }}">
                <span class="input-group-text"><i class="fas fa-arrow-right"></i></span>
                <input name="end_date" type="date" class="form-control" id="end_date" placeholder="End Date" value="{{ request('end_date') }}">
            </div>
        </div>
        <div class="col-md-1">
            <button type="submit" title="Filter Search" data-bs-toggle="tooltip" class="btn btn-primary w-100 h-100">
                <i class="fas fa-filter"></i>
            </button>
        </div>
        <div class="col-md-1">
            <a href="{{ route('orders.index')}}" title="Reset Filter" data-bs-toggle="tooltip" id="reset-filter" name="reset-filter" type="button" class="btn btn-secondary w-100 h-100"> <i class="fas fa-undo"></i>
            </a>

        </div>
    </div>
</form>
<div class="col-md-12 mb-2">
    <a href={{ route('orders.create') }} class="btn btn-primary w-100" type="button" data-bs-toggle="tooltip" title="Create New Order">Tambahkan Pesanan Baru</a>
</div>
<!-- /.card-header -->
<div class="card-body p-0">
    <table class="table text-center">
        <thead class="text-center">
            <tr>
                <th style="width: 20px">#</th>
                <th>Customer</th>
                <th>Table</th>
                <th>Status</th>
                <th>Total Price</th>
                <th>Payment</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order )
            <tr class="align-middle">
                <td>{{ $order->id }}</td>
                <td class="text-start">
                    {{ $order->customer_name }}
                </td>
                <td>{{ $order->tables->number }}</td>
                <td class="text-center"><span class="badge rounded-pill order-{{Str::lower( $order->status_label) }}" style="width: 100px; font-size: 12px;">{{Str::upper( $order->status) }}</span></td>
                <td>{{ $order->total_price }}</td>
                <td>Pembayaran : {{$order->payment_method=='qris'?Str::upper($order->payment_method):Str::ucfirst($order->payment_method) }} <br> <span class="badge rounded-pill payment-{{ Str::lower($order->payment_status) }}" style="width: 100px">{{ Str::upper($order->payment_status)}}</span></td>
                <td>
                    Created : {{ $order->created_at }} <br>
                    Updated : {{ $order->updated_at }}
                </td>
                <td>
                    <a href={{ route('orders.printPDF', $order) }} type="button" class="btn btn-primary" target="_blank"><i class="fa-solid fa-eye"></i></a>
                    <a href={{ route('orders.edit', $order) }} type="button" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></a>

                    <button class="btn btn-danger delete-button" title="Delete Order" data-bs-toggle="tooltip" data-id="{{ $order->id }}" type="button">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                    <form action="{{ route('orders.destroy', $order) }}" method="POST" class="d-inline" id="delete-form-{{ $order->id }}">
                        @csrf
                        @method('DELETE')
                    </form>
                </td>
            </tr>
            @endforeach


        </tbody>
    </table>
</div>
<div class="d-flex justify-content-end">
    {{ $orders->links('pagination::bootstrap-5') }}
</div>
<!-- /.card-body -->

@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Add filter functionality
    document.querySelectorAll('.delete-button').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            const form = document.getElementById(`delete-form-${id}`);

            Swal.fire({
                title: "Yakin ingin Menghapus?",
                text: "Aksi ini akan menghapus Order id " + id,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Tidak, Batalkan!",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    })

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>

@endsection