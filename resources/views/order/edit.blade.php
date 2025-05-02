@extends('app.master')
@section('content')
<div class="card card-primary card-outline mb-4">
    <div class="card-header">
        <div class="card-title">Edit Order</div>
    </div>
    <form action="{{ route('orders.update', $order) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="card-body">
            <div class="mb-3">
                <label for="customer_name" class="form-label">Nama Pelanggan</label>
                <input type="text" name="customer_name" id="customer_name" class="form-control @error('customer_name') is-invalid @enderror" value="{{ $order->customer_name }}">
                @error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="table_id" class="form-label">Meja</label>
                <select name="table_id" class="form-select @error('table_id') is-invalid @enderror">
                    <option selected disabled value="">Pilih Meja</option>
                    @foreach ($tables as $table)
                    <option value="{{ $table->id }}" {{ $order->table_id == $table->id ? 'selected' : '' }}>
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
                    <option value="{{ $menu->id }}" {{ in_array($menu->id, $order->menus->pluck('id')->toArray())?'selected':'' }}>
                        {{ $menu->name }}
                        @endforeach
                </select>
                <div id="menu_quantities"></div>
            </div>
            <div class="mb-3">
                <label for="note" class="form-label">Note</label>
                <textarea name="note" class="form-control @error('note') is-invalid @enderror">{{ $order->notes }}</textarea>
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

        let selectedMenusQuantities = @json($selectedMenus);
        let alreadySelected = [];

        Object.keys(selectedMenusQuantities).forEach(key => {
            alreadySelected.push(parseInt(key));
        });
        let allMenus = @json($menus);
        $('#menus').select2({
            placeholder: 'Select Menu....',
            allowClear: true
        });

        function initAlreadySelected() {
            let container = $('#menu_quantities');
            container.html('');
            $.each(selectedMenusQuantities, function(key, value) {
                let menu = allMenus.find(i => i.id == key);
                if (menu) {
                    container.append(`
                        <div class="form-group" id="menu-group-${menu.id}">
                        <label>${menu.name} Quantity : </label>
                        <input type="number" name="menus[${menu.id}][quantity]" class="form-control form-control-sm d-inline-block w-auto" placeholder="Enter ${menu.name} quantity" step="1" value="${value}" min="0">
                        <label> x ${menu.price} </label>   
                        </div>
                        `);
                }
            })
        }
        initAlreadySelected();

        function renderQuantities(selectedMenu) {
            let container = $('#menu_quantities');
            selectedMenu.forEach(function(id) {
                if (!alreadySelected.includes(id)) {

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
            container.append(`<input type="hidden" name="total_price" value="${total}">`);

        }

        $('#menu_quantities, #menus').on('change', function() {
            calculateTotal();
        });
        calculateTotal();

    });
</script>
@endsection