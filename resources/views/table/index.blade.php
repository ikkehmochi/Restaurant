@extends("app.master")

@section("content")
<div class="row">
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
                                <tr>
                                    <th scope="row">{{$table->number}}</th>
                                    <td>{{ $table->capacity }}</td>
                                    <td>
                                        <span class="badge text-bg-{{ $table->status_color }}">{{ $table->status_name }}</span>
                                    </td>
                                    <td><a href="{{ route('tables.edit', parameters: [$table]) }}" data-bs-toggle="modal" data-bs-target="#editModal" class="btn btn-primary btn-sm">Edit</a>
                                        <a type="button" class="btn btn-danger btn-sm">Delete</a>
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

    </div>
    <!-- /.col -->
    <!-- /.col -->
    @include('table.modal.edit')
</div>
@endsection