@extends('app.master')
@section('content')
<!--begin::Quick Example-->
<div class="card card-primary card-outline mb-4">
    <div class="card-header">
        <div class="card-title">Create New Order</div>
    </div>
    <form action="{{ route('orders.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="mb-3">
                <label for="name" class="form-label">Nama Pelanggan</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="table_id" class="form-label">Meja</label>
                <select name="table_id" class="form-select @error('table_id') is-invalid @enderror">
                    <option selected disabled value="">Pilih Meja</option>
                    @foreach ($tables as $table)
                    <option value="{{ $table->id }}" {{ old('table_id') == $table->id ? 'selected' : '' }}>
                        {{ $table->number }}
                    </option>
                    @endforeach
                </select>
                @error('table_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="menus" class="form-label">Menus</label><br>
                <select id="menus" name="menus[]" class="form-select form-select-lg" multiple>
                    @foreach ($menus as $menu)
                    <option value="{{ $menu->id }}" {{ old('menu_id')==$menu->id ? 'selected' : '' }}>
                        {{ $menu->name }}
                        @endforeach
                </select>
                <div id="menu_quantities"></div>
            </div>
            <div class="mb-3">
                <label for="note" class="form-label">Note</label>
                <textarea name="note" class="form-control @error('note') is-invalid @enderror">{{ old('note') }}</textarea>
                @error('note')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3" style="font-weight: bolder; font-size: 24px;">
                <label for="total_price" class="form-label">Total</label>
                <div id="total_price">
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
    $(document).ready(function() {

        let alreadySelected = [];
        let allMenus = @json($menus);
        $('#menus').select2({
            placeholder: 'Select Menu....',
            allowClear: true
        });

        function renderQuantities(selectedMenu) {
            let container = $('#menu_quantities');
            selectedMenu.forEach(function(id) {
                if (!alreadySelected.includes(id)) {
                    // console.log("MASUK IF");

                    let menu = allMenus.find(i => i.id == id);
                    if (menu) {
                        container.append(`
                        <div class="form-group" id="menu-group-${menu.id}">
                        <label>${menu.name} Quantity : </label>
                        <input type="number" name="menus[${menu.id}][quantity]" class="form-control form-control-sm d-inline-block w-auto" placeholder="Enter ${menu.name} quantity" step="1" value="1" min="0">
                        <label> x ${menu.price} </label>   
                        </div>
                        `);
                    }
                    alreadySelected.push(id);
                }


            });

            let difference = alreadySelected.filter(x => !selectedMenu.includes(x));
            if (difference.length !== 0) {
                difference.forEach(function(id) {
                    $(`#menu-group-${id}`).remove();
                })
                alreadySelected = alreadySelected.filter(x => !difference.includes(x));

            }
            difference = alreadySelected.filter(x => !selectedMenu.includes(x));

        }
        $('#menus').on('change', function() {
            let selected = $(this).val();

            renderQuantities(selected || []);
            // console.log(alreadySelected);

            // calculateTotal(selected || []);

        });

        function calculateTotal() {
            let container = $('#total_price');
            let total = 0;
            // let selectedMenu = $('#menus').val();
            let selectedMenu = alreadySelected;

            // console.log(selectedMenu)
            if (!selectedMenu || selectedMenu.length === 0) {
                total = 0;

            } else {
                selectedMenu.forEach(function(id) {
                    let menu = allMenus.find(i => i.id == id);
                    let quantity = $(`input[name="menus[${id}][quantity]"]`).val() || 0;
                    total += menu.price * quantity;

                });
            }
            let formatted = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 2
            }).format(total);
            container.html(formatted);

        }

        $('#menu_quantities, #menus').on('change', function() {
            calculateTotal();
        });
        calculateTotal();

    });
</script>

@endsection