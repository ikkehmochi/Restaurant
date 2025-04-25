@extends('app.master')
@section('content')
<!--begin::Quick Example-->
<div class="card card-primary card-outline mb-4">
    <div class="card-header">
        <div class="card-title">Create New Menu</div>
    </div>
    <form action="{{ route('menus.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="mb-3">
                <label for="name" class="form-label">Nama Menu</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Harga Menu</label>
                <div class="input-group mb-3">
                    <span class="input-group-text">Rp</span>
                    <input type="text" name="price" class="form-control @error('price') is-invalid @enderror"
                        value="{{ old('price') ? number_format(old('price'), 0, ',', '.') : '' }}"
                        data-type="currency">
                    @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Kategori</label>
                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                    <option selected disabled value="">Pilih Kategori</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->title }}
                    </option>
                    @endforeach
                </select>
                @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="ingredients" class="form-label">Ingredients</label>
                <select id="ingredients" name="ingredients[]" class="form-control" multiple>
                    @foreach ($ingredients as $ingredient)
                    <option value="{{ $ingredient->id }}" {{ in_array($ingredient->id, old('ingredients', [])) ? 'selected' : '' }}>
                        {{ $ingredient->name }}
                        @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label" for="image">Upload Foto Menu</label>
                <div class="input-group">
                    <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror"
                        accept="image/*" onchange="previewImage(this);">
                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mt-2">
                    <img id="preview" src="{{ asset('icons/healthy-food.png') }}" alt="Preview" style="max-width: 200px; display: block;">
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function previewImage(input) {
        const preview = document.getElementById('preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Format currency
    document.querySelector('input[data-type="currency"]').addEventListener('keyup', function(e) {
        let value = this.value.replace(/\./g, '');
        value = value.replace(/[^\d]/g, '');
        this.value = value ? new Intl.NumberFormat('id-ID').format(value) : '';
    });
</script>
<script>
    $(document).ready(function() {
        $("#ingredients").select2({
            placholder: "Choose Ingredients",
            allowClear: true,
        })
    })
</script>
@endsection