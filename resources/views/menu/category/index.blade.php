@extends("app.master")

@section("content")
<div class="row">
    <form id="filter" method="GET" class="mb-3">
        <div class="row g-3">
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input name="search" type="text" autocomplete="off" class="form-control" id="search" placeholder="Search menu category..." value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-md-1">
                <button type="submit" title="Filter Search" data-bs-toggle="tooltip" class="btn btn-primary w-100">
                    <i class="fas fa-filter"></i>
                </button>
            </div>
            <div class="col-md-1">
                <a href="{{ route('menuCategories.index')}}" title="Reset Filter" data-bs-toggle="tooltip" id="reset-filter" name="reset-filter" type="button" class="btn btn-secondary w-100"> <i class="fas fa-undo"></i>
                </a>

            </div>
        </div>
    </form>

    <div class="col-md-12 mb-2">
        @include('menu.category.modal.create')
        <a href="javascript:void(0)" class="btn btn-primary w-100" type="button" data-bs-toggle="modal" data-bs-target="#createModal">Tambahkan Kategori</a>
    </div>
    <div class="col-md-12">
        <div class="card mb-4">
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">id</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Slug</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($menuCategories as $category)

                                @include('menu.category.modal.edit', ['category' => $category])
                                <tr>
                                    <th scope="row">{{$category->id}}</th>
                                    <td>{{ $category->title}}</td>
                                    <td>{{ $category->slug }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('menuCategories.edit', $category) }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $category->id }}"
                                                class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <button class="btn btn-danger btn-sm delete-button" id="delete-button" data-id="{{$category->id}}" type="button">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                            <form action="{{ route('menuCategories.destroy', $category) }}" method="POST" class="d-inline" id="delete-form-{{ $category->id }}">
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
                </div>

            </div>
        </div>
        <!-- /.card -->
        <div class="d-flex justify-content-end">
            {{ $menuCategories->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
<!-- /.col -->
<!-- /.col -->
</div>
@endsection

@section("scripts")
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
                text: "Aksi ini akan menghapus Menu Category " + id,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Tidak, Batalkan!",
                reverseButtons: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    form.submit();
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
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>

@endsection