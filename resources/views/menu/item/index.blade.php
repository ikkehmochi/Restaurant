@extends('app.master')

<style>
    /* Only blur the image and card-body, not the show-button */
    .card-main {
        position: relative;
        /* Add this line */
    }

    .card-main:hover img,
    .card-main:hover .card-body {
        filter: blur(0.5rem);
        opacity: 0.5;
        transition: 0.2s;
        backface-visibility: visible;
    }

    /* Remove blur from the whole card-main */
    .card-main:hover {
        filter: none;
        opacity: 1;
    }

    .card-main:hover .show-button {
        opacity: 1;
        filter: initial;
    }

    .show-button {
        filter: initial;
        transition: .5s ease;
        opacity: 0;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%);
        text-align: center;
        z-index: 2;
        /* Add this line for stacking above blur */
    }
</style>
@section('content')

<div class="container">
    <form id="filter" method="GET" class="mb-3">
        <div class="row g-3">
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input name="search" type="text" autocomplete="off" class="form-control" id="search" placeholder="Search Menu..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-filter"></i></span>
                    <select name="categories" class="form-select" id="categories">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('categories')==$category->id?'selected':'' }}>{{ $category->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-1">
                <button type="submit" title="Filter Search" data-bs-toggle="tooltip" class="btn btn-primary w-100">
                    <i class="fas fa-filter"></i>
                </button>
            </div>
            <div class="col-md-1">
                <a href="{{ route('menus.index')}}" title="Reset Filter" data-bs-toggle="tooltip" id="reset-filter" name="reset-filter" type="button" class="btn btn-secondary w-100"> <i class="fas fa-undo"></i>
                </a>

            </div>
        </div>
    </form>
    <div class="col-md-12 mb-2">
        <a href={{ route('menus.create') }} class="btn btn-primary w-100" type="button" data-bs-toggle="tooltip" title="Create New Menu">Tambahkan Menu baru</a>
    </div>
    <div class="row">
        @foreach ($menus as $menu)
        @include('menu.item.modal.show', ['menu' => $menu])
        <div class="col-md-2 mb-4">
            <div class="card" style="width: 100%">
                <div class="row">
                    <div class="col-12 card-main" style="text-align: center; height: 300px;">
                        <img src={{ $menu->image? asset($menu->image):asset('icons/healthy-food.png') }} class="card-img-top img-fluid mx-auto mt-2 w-75" alt="..." style="max-height: 150px; max-width: 150px;">
                        <div class="card-body">
                            <h5 class="text-center fw-bold">{{ $menu->name }}</h5>
                            <h6 class="text-center fw-medium text-muted">
                                {{ $menu->category->title }}
                            </h6>

                            <p>{{Str::limit( $menu->description, 20 )}}</p>
                        </div>
                        <button class="btn btn-primary btn-lg me-1 show-button" data-bs-toggle="modal" data-bs-target="#showModal{{ $menu->id }}" data-bs-toggle="tooltip" title="Show Details" data-bs-placement="top"> <i class="fa-solid fa-eye"></i></button>

                    </div>

                    <div class="col-12" style="text-align: center; margin-bottom: 10px;">
                        <a href="{{ route('menus.edit', parameters: $menu) }}" title="Edit Menu" data-bs-toggle="tooltip" data-bs-placement="top" class="btn btn-success btn-md ms-1 me-1">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-danger btn-md  delete-button ms-1" title="Delete Menu" data-bs-toggle="tooltip" data-bs-placement="top" id="delete-button" data-id="{{ $menu->id }}" type="button">
                            <i class="fas fa-trash"></i>
                        </button>
                        <form action="{{ route('menus.destroy', $menu) }}" method="POST" class="d-inline" id="delete-form-{{ $menu->id }}">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>
        </div>


        @endforeach
    </div>
</div>
<div class="d-flex justify-content-end">
    {{ $menus->links('pagination::bootstrap-5') }}
</div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Ensure tooltips are initialized after DOM is fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function(tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl, {
                boundary: 'window',
                placement: 'auto',
                offset: [0, 5]
            });
        });

        // Add filter functionality
        document.getElementById('filter').addEventListener('submit', function(e) {
            e.preventDefault();
            const search = document.getElementById('search').value;
            const categories = document.getElementById('categories').value;

            window.location.href = `${window.location.pathname}?search=${search}&categories=${categories}`;
        });

        // Add reset filter functionality
        document.getElementById('reset-filter').addEventListener('click', function() {
            document.getElementById('search').value = '';
            document.getElementById('categories').value = '';

            window.location.href = window.location.pathname;
        });

        document.querySelectorAll('.delete-button').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                const id = this.getAttribute('data-id');
                const form = document.getElementById(`delete-form-${id}`);

                Swal.fire({
                    title: "Yakin ingin Menghapus?",
                    text: "Aksi ini akan menghapus Menu id " + id,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Tidak, Batalkan!",
                    reverseButtons: true
                }).then(function(result) {
                    if (result.isConfirmed) {
                        form.submit(); // Submit form yang sesuai

                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire(
                            'Cancelled',
                            'Batal Menghapus',
                            'error'
                        );
                    }
                });
            });
        });
    });
</script>
@endsection