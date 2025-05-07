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
        let menuIngredients = {};
        let currentIngredientStocks = {};
        let hasIngredientWarnings = false;

        Object.keys(selectedMenusQuantities).forEach(key => {
            alreadySelected.push(parseInt(key));
        });
        let allMenus = @json($menus);
        $('#menus').select2({
            placeholder: 'Select Menu....',
            allowClear: true
        });

        function getMenuIngredients() {
            $.ajax({
                url: "{{ route('ingredients.api') }}",
                type: "GET",
                dataType: "json",
                success: function(response) {
                    menuIngredients = response.menuIngredients;
                    currentIngredientsStocks = response.ingredientStocks;

                    let selectedMenus = $('menus').val();
                    if (selectedMenus && selectedMenus.length > 0) {
                        checkIngredients();
                    }
                },
                error: function(xhr) {
                    console.error("Error fetching menu ingredients : ", xhr);
                }
            });
        }
        getMenuIngredients();

        function checkIngredients() {
            console.log(alreadySelected);
            console.log("ChekIng called");
            if (Object.keys(menuIngredients).length === 0) {
                return;
            }
            let warningsContainer = $('ingredient_warnings');
            warningsContainer.empty();
            hasIngredientWarnings = false;

            let tempIngredientUsage = {};
            for (let ingredientId in currentIngredientStocks) {
                tempIngredientUsage[ingredientId] = {
                    used: 0,
                    available: currentIngredientStocks[ingredientId].stock,
                    name: currentIngredientStocks[ingredientId].name
                }
            };

            alreadySelected.forEach(function(menuId) {
                let quantity = parseInt($(`input[name="menus[${menuId}][quantity]"]`).val()) || 0;
                let menuName = allMenus.find(m => m.id == menuId).name;
                let statusContainer = $(`.ingredient-status-${menuId}`);
                statusContainer.empty();
                if (quantity > 0 && menuIngredients[menuId]) {
                    menuIngredients[menuId].forEach(function(ingredient) {
                        let neededQuantity = ingredient.pivot_quantity * quantity;
                        if (!tempIngredientUsage[ingredient.id]) {
                            tempIngredientUsage[ingredient.id] = {
                                used: 0,
                                available: ingredient.stock,
                                name: ingredient.name
                            };
                        }
                        tempIngredientUsage[ingredient.id].used += neededQuantity;
                    });
                }
            });

            let warnings = [];
            for (let ingredientId in tempIngredientUsage) {
                let ingredient = tempIngredientUsage[ingredientId];
                if (ingredient.used > ingredient.available) {
                    warnings.push(`<div class="alert alert-danger">Not enough ${ingredient.name} (Need: ${ingredient.used}, Available: ${ingredient.available})</div>`);
                    hasIngredientWarnings = true;
                }
            }

            if (warnings.length > 0) {
                warningsContainer.html(`
                    <div class="alert alert-warning">
                        <strong>Warning: Ingredient Shortage</strong>
                        ${warnings.join('')}
                    </div>
                `);
                $('#submit-order').attr('disabled', true);

            } else {
                $('#submit-order').attr('disabled', false);
            }
            alreadySelected.forEach(function(menuId) {
                let quantity = parseInt($(`input[name="menus[${menuId}][quantity]"]`).val()) || 0;
                let statusContainer = $(`.ingredient-status-${menuId}`);
                statusContainer.empty();
                if (quantity > 0 && menuIngredients[menuId]) {
                    let menuWarnings = [];
                    menuIngredients[menuId].forEach(function(ingredient) {
                        let neededQuantity = ingredient.pivot_quantity * quantity;
                        let remainingAfterOthers = ingredient.stock - (tempIngredientUsage[ingredient.id].used - neededQuantity);
                        let maxPossible = Math.floor(remainingAfterOthers / ingredient.pivot_quantity);
                        if (quantity > maxPossible) {
                            menuWarnings.push(`<small class="text-danger">${ingredient.name} : Max ${maxPossible} serving possible (needs ${ingredient.pivot_quantity}/serving) </small>`);
                        }
                    });
                    if (menuWarnings.length > 0) {
                        statusContainer.html(`<div>${menuWarnings.join('<br>')}</div>`);

                    } else {
                        // statusContainer.html(`<small class="text-success">Ingredients available</small>`);
                    }
                }
            });
        }

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
            });
        }
        initAlreadySelected();
        checkIngredients();


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
                        <div class="ingredient-status-${menu.id} mt-1"></div>   
 
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
            checkIngredients();

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
            checkIngredients();
        });
        calculateTotal();

    });
</script>
@endsection