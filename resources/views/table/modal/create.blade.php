<!-- Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('tables.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-8 offset-md-2">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Create Table</h4>
                                    </div>
                                    <div class="card-body">


                                        <div class="mb-3">
                                            <label for="number" class="form-label">Table Number</label>
                                            <input type="text" class="form-control @error('number') is-invalid @enderror"
                                                id="number" name="number">
                                            @error('number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="capacity" class="form-label">Capacity</label>
                                            <select class="form-select @error('capacity') is-invalid @enderror"
                                                id="capacity" name="capacity">
                                                @foreach([2, 4, 6, 8] as $cap)
                                                <option value="{{ $cap }}">
                                                    {{ $cap }} People
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('capacity')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="status_id" class="form-label">Status</label>
                                            <select class="form-select @error('status_id') is-invalid @enderror"
                                                id="status_id" name="status_id">
                                                @foreach($statuses as $id=> $status)
                                                <option value="{{ $id }}">
                                                    {{ $status}}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('status_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('tables.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create Table</button>
                </div>
            </form>

        </div>
    </div>
</div>