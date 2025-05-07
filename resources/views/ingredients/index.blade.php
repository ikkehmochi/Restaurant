@extends('app.master')
<style>
    .custom-tooltip {
        --bs-tooltip-bg: var(--bd-black-bg);
        --bs-tooltip-color: var(--bs-white);
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

            <div class="col-md-1">
                <button type="submit" title="Filter Search" data-bs-toggle="tooltip" class="btn btn-primary w-100">
                    <i class="fas fa-filter"></i>
                </button>
            </div>
            <div class="col-md-1">
                <a href="#" title="Reset Filter" data-bs-toggle="tooltip" id="reset-filter" name="reset-filter" type="button" class="btn btn-secondary w-100"> <i class="fas fa-undo"></i>
                </a>

            </div>
        </div>
    </form>
    <div class="col-md-12 mb-2">
        @include('ingredients.modal.create')
        <a href={{ route('ingredients.create') }} class="btn btn-primary w-100" type="button" data-bs-toggle="modal" data-bs-target="#createModal" data-bs-toggle="tooltip" title="Create New Ingredient">Create New Ingredients</a>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Description</th>
                <th scope="col">Stock</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ingredients as $ingredient)
            @include('ingredients.modal.edit', ['ingredient' => $ingredient])
            @include('ingredients.modal.stockEdit', ['ingredient'=>$ingredient])
            <tr>
                <th scope="row">{{$ingredient->name}}</th>
                <td>{{ $ingredient->description }}</td>
                <td><a href={{ route('ingredients.stockEdit', $ingredient) }}
                        data-bs-toggle="modal"
                        data-bs-target="#stockEditModal{{ $ingredient->id }}"
                        data-bs-toggle="tooltip"
                        data-bs-custom-class="custom-tooltip"
                        title="Edit Stock {{ $ingredient->name }}"
                        class="btn">
                        {{ $ingredient->stock }} </a></td>
                <td>
                    <div class="d-flex gap-2">
                        <a href={{ route('ingredients.edit', $ingredient) }}
                            data-bs-toggle="modal"
                            data-bs-target="#editModal{{ $ingredient->id }}"
                            class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button class="btn btn-danger btn-sm delete-button" id="delete-button" data-id="{{ $ingredient->id }}" type="button">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                        <form action={{ route('ingredients.destroy', $ingredient) }} method="POST" class="d-inline" id="delete-form-{{ $ingredient->id }}">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="d-flex justify-content-end">
    {{ $ingredients->links('pagination::bootstrap-5') }}
</div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Add filter functionality
    document.getElementById('filter').addEventListener('submit', function(e) {
        e.preventDefault();
        const search = document.getElementById('search').value;

        window.location.href = `${window.location.pathname}?search=${search}`;
    });

    // Add reset filter functionality
    document.getElementById('reset-filter').addEventListener('click', function() {
        document.getElementById('search').value = '';

        window.location.href = window.location.pathname;
    });
    document.querySelectorAll('.delete-button').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const id = this.getAttribute('data-id');
            const form = document.getElementById(`delete-form-${id}`);

            Swal.fire({
                title: "Yakin ingin Menghapus?",
                text: "Aksi ini akan menghapus Ingredients id " + id,
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