@extends('app.main')
@push('styles')
<style>
    body {
        background-color: #324154;
    }

    .card {
        color: transparent;
        border-radius: 10%;
        position: relative;
        overflow: visible;
    }

    .card-img-overlay {
        display: flex;
    }

    .card-title {
        color: #ffffff;
        text-align: center;
        justify-content: center;
        align-items: flex-start;
        height: 100%;
        vertical-align: top;
        display: flex;
        font-family: 'Montserrat';
        font-size: 20px;
        font-weight: bold;
        width: 100%;
    }

    .order-details {
        display: none;
        position: absolute;
        top: 10px;
        right: -220px;
        width: 200px;
        background: rgba(255, 255, 255, 0.97);
        color: #222;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        padding: 16px;
        z-index: 10;
        font-size: 14px;
    }

    .card:hover .order-details {
        display: block;
        animation: fadeIn 0.2s;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateX(20px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
</style>

@endpush
@section('content')

<div class="row" id="tables-container">
</div>
<div class="mb-4" id="floor-selector" style="text-align: center;">
    <!-- Buttons will be dynamically inserted here -->
</div>

<div class="modal-test" id="modal-test">
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        let allTables = [];

        function getAllDiningTables() {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: "{{ route('homepage.tableIndex.api') }}",
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        resolve(response.tables);
                    },
                    error: function(xhr, status, error) {
                        reject(error)
                    }
                });
            });

        }

        function getTableOrderDetails(table_id) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: "{{ route('homepage.tableOrderDetails.api') }}",
                    type: "GET",
                    data: {
                        'table_id': table_id
                    },
                    dataType: "json",
                    success: function(response) {
                        resolve(response);
                    },
                    error: function(xhr, status, error) {
                        reject(error)
                    }
                })
            })
        }

        function renderOrderDetails(response) {
            const container = $('#modal-test');
            container.empty();
            var orderHeader = `
                    <div class="fw-bold mb-1" id="order-title">Order #${response.order.id}<br>${response.order.customer_name} </div>
            `
            var menus = response.order.menus
            var detailContainer = `<ul class="ps-3 mb-1">`;
            $.each(menus, function(index, menuItem) {
                detailElement = `<li>${menuItem.name} <span class="text-muted">x${menuItem.pivot.quantity}</span>=${menuItem.pivot.subtotal}</li>`;
                detailContainer += detailElement;
            })
            detailContainer += `<ul>`
            container.append(orderHeader);
            container.append(detailContainer)
            var orderFooter = `
        <div class="mt-2">
            <strong>Total Price:</strong> ${response.order.total_price.toFixed(2)}<br>
            <strong>Status:</strong> ${response.order.status}<br>
            <strong>Payment Method:</strong> ${response.order.payment_method}<br>
            <strong>Payment Status:</strong> ${response.order.payment_status}
        </div>
    `;
            container.append(orderFooter);

        }


        function renderTables(tables) {
            const container = $('#tables-container');
            container.empty();

            tables.forEach(table => {
                let status = table.table_status.title;
                let imgSrc = "";
                if (status === "available") {
                    imgSrc = "{{ asset('icons/available-table.svg') }}";
                } else if (status === "occupied") {
                    imgSrc = "{{ asset('icons/occupied-table.svg') }}";
                } else {
                    imgSrc = "{{ asset('icons/reserved-table.svg') }}";
                }

                let html = `
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-3">
                        <div class="card position-relative table-card" id="table-${table.id}" data-table-id="${table.id}">
                            <img src="${imgSrc}" alt="table ${status}" class="card-img-top">
                            <div class="card-img-overlay">
                                <h5 class="card-title text-white">${table.number}</h5>
                            </div>
                        </div>
                    </div>
                `;
                container.append(html);
            });

            // // Attach hover event after rendering
            $('.table-card').hover(
                function() {
                    $('#modal-test').append(`                    
                    <div class="spinner-border text-dark" role="status">
                      <span class="visually-hidden">Loading...</span>
                    </div>`);
                    const tableId = $(this).data('table-id');

                    getTableOrderDetails(tableId).then(orderDetails => {
                        if (orderDetails.order !== null) {
                            $('#modal-test').empty();
                            renderOrderDetails(orderDetails);
                        } else {
                            $('#modal-test').empty();
                        }
                    });
                },
                function() {
                    $('#modal-test').empty();
                }
            );
        }

        function renderFloorButtons(floors) {
            const floorSelector = $('#floor-selector');
            floorSelector.empty();

            floors.forEach((floor, index) => {
                const btn = $(`<button class="btn btn-outline-secondary mx-1" data-floor="${floor}">Floor ${floor}</button>`);

                btn.on('click', function() {
                    // Remove active class from all buttons
                    $('#floor-selector button').removeClass('active btn-secondary').addClass('btn-outline-secondary');

                    // Add active class to clicked button
                    $(this).addClass('active btn-secondary').removeClass('btn-outline-secondary');

                    // Render tables for selected floor
                    renderTables(allTables.filter(t => t.floor == floor));
                });

                floorSelector.append(btn);
            });

            // Trigger click on the first floor by default to set initial active state
            $('#floor-selector button').first().trigger('click');
        }

        // getTableOrderDetails(3).then(orderDetails => {
        //     console.log(orderDetails);
        //     if (orderDetails.order !== null) {
        //         console.log("AAAAAAAAAAAAAAAAAAAAAAAAA");

        //         renderOrderDetails(orderDetails);
        //     }
        // })

        // getAllDiningTables().then(allTables => {
        //     console.log("All tables : ", allTables);
        // }).catch(error => {
        //     console.error("Error fetching data", error);
        // });
        getAllDiningTables()
            .then(tables => {
                allTables = tables;

                const uniqueFloors = [...new Set(tables.map(t => t.floor))].sort();
                renderFloorButtons(uniqueFloors);

                // Optionally render first floor by default
                renderTables(tables.filter(t => t.floor === uniqueFloors[0]));
            })
            .catch(error => {
                console.error("Error fetching data", error);
            });


    });
</script>
@endsection