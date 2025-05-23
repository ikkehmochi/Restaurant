@extends('app.main')
@push('styles')
<style>
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

    .card-blurred {
        filter: blur(5px);
        transition: filter 0.2s;
    }

    .order-details {
        display: none;
        position: absolute;
        top: 10px;
        width: 200px;
        background: rgba(255, 255, 255, 0.97);
        color: #222;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        padding: 16px;
        z-index: 1000;
        font-size: 14px;
    }

    .order-details.right {
        right: -220px;
        left: auto;
    }

    .order-details.left {
        left: -220px;
        right: auto;
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

<!-- <div class="modal-test" id="modal-test"> -->
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

        function renderOrderDetails(response, targetContainer) {
            const container = $(targetContainer);
            console.log(`Container : ${container}`);

            container.empty();
            if (!response || !response.order) {
                container.html('<p class="text-muted p-2">No active order details.</p>');
                return;
            }
            var orderHeader = `
                    <div class="fw-bold mb-1" id="order-title">Order #${response.order.id}<br>${response.order.customer_name} </div>
            `
            var menus = response.order.menus
            var detailContainer = `<ul class="ps-3 mb-1">`;
            if (menus.length > 0) {
                $.each(menus, function(index, menuItem) {
                    detailElement = `<li>${menuItem.name} <span class="text-muted">x${menuItem.pivot.quantity}</span>=${menuItem.pivot.subtotal}</li>`;
                    detailContainer += detailElement;
                })
            } else {
                detailContainer += `<li>No items in this order.</li>`;
            }
            detailContainer += `</ul>`
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
            if (!tables || tables.length === 0) {
                container.html('<p class="text-white text-center">No tables available for this floor.</p>');
                return;
            }
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
                        <div class="card position-relative table-card table-${status}" id="table-${table.id}" data-table-id="${table.id}">
                            <img src="${imgSrc}" alt="table ${status}" class="card-img-top">
                            <div class="card-img-overlay">
                                <h5 class="card-title text-white">${table.number}</h5>
                            </div>
                            <div class="order-details"></div>
                        </div>
                    </div>
                `;
                container.append(html);
            });

            $('.table-card').click(function() {
                const tableId = $(this).data('table-id');
                const cardTarget = $(this).find('.card');
                console.log(cardTarget);
                cardTarget.addClass('card-blurred');
            });

            // Attach hover event after rendering
            $('.table-card').hover(
                function() {
                    const tableId = $(this).data('table-id');
                    const orderDetailsContainer = $(this).find('.order-details');
                    // Determine card position relative to viewport
                    const cardOffset = $(this).offset();
                    const cardWidth = $(this).outerWidth();
                    const windowWidth = $(window).width();
                    // Remove previous direction classes
                    orderDetailsContainer.removeClass('left right');
                    // If card is in the right half of the viewport, show details to the left
                    if (cardOffset.left + cardWidth / 2 > windowWidth / 2) {
                        orderDetailsContainer.addClass('left');
                    } else {
                        orderDetailsContainer.addClass('right');
                    }
                    $(orderDetailsContainer).append(`                    
                    <div class="spinner-border text-dark" role="status">
                      <span class="visually-hidden">Loading...</span>
                    </div>`);
                    getTableOrderDetails(tableId).then(response => {
                        if ($(this).is(':hover')) {
                            if (response.order !== null) {
                                renderOrderDetails(response, orderDetailsContainer)
                            } else {
                                orderDetailsContainer.html('<p class="text-muted p-2 text-center small">No active order.</p>');
                            }
                        }
                    }).catch(error => {
                        console.error("Error Fetching order details : ", error);
                        if ($(this).is(':hover')) {
                            orderDetailsContainer.html('<p class="text-danger p-2 text-center small">Error loading.</p>');
                        }
                    });
                },
                function() {
                    $(this).find('.order-details').empty();
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

        function fetchAndRenderTables() {
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
                })
        }
        fetchAndRenderTables()
        setInterval(fetchAndRenderTables, 30000) //refresh table every 30 seconds;
    });
</script>
@endsection