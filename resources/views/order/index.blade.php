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
                    <a href={{ route('orders.printPDF', $order) }} type="button" class="btn btn-primary"><i class="fa-solid fa-eye"></i></a>
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