<!-- Modal -->
<div class="modal fade" id="stockEditModal{{ $ingredient->id }}" tabindex="-1" aria-labelledby="stockEditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Editing {{ str(ucfirst($ingredient->name)) }} Stock</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('ingredients.update', $ingredient) }}" method="POST">
                @csrf
                @method("PATCH")
                <div class="modal-body">
                    <div class="container">
                        <div class="mb-3">
                            <label for="stock" class="form-label">Ingredient Stock</label>
                            <input type="number" class="form-control stockInput @error('stock') is-invalid @enderror"
                                id="stock" name="stock" value={{ $ingredient->stock }} min="0">
                            @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="text-center">
                            <button class="btn btn-primary btn-lg increaseQuantity" type="button">+</button>
                            <button class="btn btn-danger btn-lg decreaseQuantity" type="button">-</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('ingredients.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>

        </div>
    </div>
</div>
@section('scripts')
<script>
    $(document).ready(function() {
        $('.increaseQuantity').on('click', function() {
            let stock = parseInt($('.stockInput').val(), 10);
            stock += 1;
            $('.stockInput').val(stock);
        });
        $('.decreaseQuantity').on('click', function() {
            let stock = parseInt($('.stockInput').val(), 10);
            stock -= 1;
            $('.stockInput').val(stock);
        });
    });
</script>

@endsection