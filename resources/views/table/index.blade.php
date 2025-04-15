@extends("app.master")

@section("content")
@include('sweetalert::alert')
<div class="row">
    <form id="filter" method="GET" class="mb-3">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input name="search" type="text" autocomplete="off" class="form-control" id="search" placeholder="Search table number...">
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-filter"></i></span>
                    <select name="status" class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        @foreach($statuses as $id => $status)
                        <option value="{{ $id }}">{{ $status }}</option>

                        @endforeach
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

                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter"></i>
                </button>
            </div>
        </div>
    </form>

    <div class="col-md-12 mb-2">
        @include('table.modal.create')
        <a href="javascript:void(0)" class="btn btn-primary w-100" type="button" data-bs-toggle="modal" data-bs-target="#createModal">Tambahkan Table</a>
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
                                    <th scope="col">Table Number</th>
                                    <th scope="col">Capacity</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tables as $table)

                                @include('table.modal.edit', ['table' => $table])
                                <tr>
                                    <th scope="row">{{$table->number}}</th>
                                    <td>{{ $table->capacity }}</td>
                                    <td>
                                        <span class="badge text-bg-{{ $table->status_color }}">{{ $table->status_name }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('tables.edit', parameters: $table->id) }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $table->id }}"
                                                class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <button class="btn btn-danger btn-sm patek" id="patek" data-id="{{ $table->id }}" type="button">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                            <form action="{{ route('tables.destroy', $table) }}" method="POST" class="d-inline" id="delete-form-{{ $table->id }}">
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
            {{ $tables->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
<!-- /.col -->
<!-- /.col -->
</div>
@endsection

@section("script")
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Add filter functionality
    document.getElementById('filter').addEventListener('submit', function(e) {
        e.preventDefault();
        const search = document.getElementById('search').value;
        const status = document.getElementById('status').value;
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;

        window.location.href = `${window.location.pathname}?search=${search}&status=${status}&start_date=${startDate}&end_date=${endDate}`;
    });

    document.querySelectorAll('.patek').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const id = this.getAttribute('data-id');
            const form = document.getElementById(`delete-form-${id}`);

            Swal.fire({
                title: "Yakin ingin Menghapus?",
                text: "Aksi ini akan menghapus Table id " + id,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Tidak, Batalkan!",
                reverseButtons: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Deleted!',
                        text: "Table id " + id + ' has been deleted!',
                        icon: 'success'
                    }).then(function() {
                        form.submit(); // Submit form yang sesuai
                    });
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
</script>

@endsection