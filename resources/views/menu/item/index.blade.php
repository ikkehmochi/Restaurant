@extends('app.master')
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
            <div class="card h-100">
                <img src={{ $menu->image? asset($menu->image):asset('icons/healthy-food.png') }} class="card-img-top img-fluid mx-auto mt-2 w-75" alt="...">

                <div class="card-body">
                    <h5 class="text-center fw-bold">{{ $menu->name }}</h5>
                    <h6 class="text-center fw-medium text-muted">
                        <a href="{{ route('menus.indexByCat', $menu->category_id) }}" class="text-decoration-none text-muted">
                            {{ $menu->category->title }}
                        </a>
                    </h6>

                    <p>{{ $menu->description }}</p>
                </div>
                <div class="card-footer align-items-center d-flex justify-content-between</div>">
                    <button class="btn btn-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#showModal{{ $menu->id }}" data-bs-toggle="tooltip" title="Show"> <i class="fa-solid fa-eye"></i></button>
                    <a href="{{ route('menus.edit', parameters: $menu) }}" title="Edit Menu" data-bs-toggle="tooltip" class="btn btn-success btn-sm ms-1 me-1">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button class="btn btn-danger btn-sm  delete-button ms-1" title="Delete Menu" data-bs-toggle="tooltip" id="delete-button" data-id="{{ $menu->id }}" type="button">
                        <i class="fas fa-trash"></i>
                    </button>
                    <form action="{{ route('menus.destroy', $menu) }}" method="POST" class="d-inline" id="delete-form-{{ $menu->id }}">
                        @csrf
                        @method('DELETE')
                    </form>
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
    })

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>

@endsection