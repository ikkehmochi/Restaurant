@extends('app.main')
@push('styles')
<style>
    body {
        font-family: 'Montserrat';
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
        right: -210px;
        left: auto;
    }

    .order-details.left {
        left: -210px;
        right: auto;
    }

    .order-details::after {
        content: '';
        position: absolute;
        top: 15px;
        width: 0;
        height: 0;
        border-style: solid;
    }

    /* Arrow pointing left (when details are on the right of the card) */
    .order-details.right::after {
        left: -10px;
        /* Position arrow outside the left edge */
        border-width: 10px 10px 10px 0;
        /* Triangle shape */
        border-color: transparent rgba(255, 255, 255, 0.97) transparent transparent;
        /* Arrow color matches background */
        /* To match box-shadow slightly, you can add a subtle drop shadow if needed, but it can be tricky */
    }

    /* Arrow pointing right (when details are on the left of the card) */
    .order-details.left::after {
        right: -10px;
        /* Position arrow outside the right edge */
        border-width: 10px 0 10px 10px;
        /* Triangle shape */
        border-color: transparent transparent transparent rgba(255, 255, 255, 0.97);
        /* Arrow color matches background */
    }

    .card:hover .order-details {
        display: block;
        animation: fadeIn 0.2s;
    }

    .table-card .table-icon-action {
        opacity: 0;
        transition: opacity 0.2s ease-in-out;
    }

    .table-card:hover {
        cursor: pointer;
    }

    .table-card:hover .table-icon-default,
    .table-card:hover .card-img-overlay {
        opacity: 0;
    }

    .table-card:hover .table-icon-action {
        display: block !important;
        opacity: 1;
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

    .selected-floor {
        font-weight: bolder;
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
        const HIDE_DELAY = 250;

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
        </div>
    `;
            let editButton = `<div class="d-grid gap-2">
<a class="btn btn-primary btn-sm" href="#" role="button">Edit</a>

</div>`
            container.append(orderFooter);
            container.append(editButton);


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
                let actionSrc = "";
                let actionRoute = "";
                let activeOrderId = table.active_order_id || null;
                if (status === "available") {
                    imgSrc = "{{ asset('icons/available-table.svg') }}";
                    actionSrc = "{{ asset('icons/Dine in.png') }}";
                    actionRoute = "{{ route('orders.create') }}";

                } else if (status === "occupied") {
                    imgSrc = "{{ asset('icons/occupied-table.svg') }}";
                    actionSrc = "{{ asset('icons/complete.png') }}";
                    actionRoute = "#";

                } else {
                    imgSrc = "{{ asset('icons/reserved-table.svg') }}";
                    actionSrc = "{{ asset('icons/Dine in.png') }}";
                    actionRoute = "{{ route('orders.create') }}";

                }

                let html = `
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-3">
                        <div class="card position-relative table-card table-${status}" id="table-${table.id}" data-table-id="${table.id}" data-action-route="${actionRoute}">
                            <img src="${imgSrc}" alt="table ${status}" class="card-img-top">
                            <img src="${actionSrc}" alt="Action for Table ${table.number}" class="card-img-top table-icon-action" 
                         style="display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit:cover; background-color: white;">
                            <div class="card-img-overlay">
                                <h5 class="card-title text-white">${table.number}</h5>
                            </div>
                            <div class="order-details"></div>
                        </div>
                    </div>
                `;

                container.append(html);
            });
            $('#tables-container').off('.cardHover .detailsHover .tableCardAction');


            $('#tables-container').off('mouseenter.cardHover mouseleave.cardHover', '.table-card');
            $('#tables-container').off('mouseenter.detailsHover mouseleave.detailsHover', '.order-details');
            // Attach hover event after rendering

            // FIX: Use #tables-container instead of .table-container
            $('#tables-container').on('mouseenter.cardHover', '.table-card', function() {
                const $card = $(this)
                const tableId = $card.data('table-id');
                const orderDetailsContainer = $card.find('.order-details');
                clearTimeout($card.data('hideTimer'));
                const cardOffset = $card.offset();
                const cardWidth = $card.outerWidth();
                const windowWidth = $(window).width();
                // Remove previous direction classes
                orderDetailsContainer.removeClass('left right');
                // If card is in the right half of the viewport, show details to the left
                if (cardOffset.left + cardWidth / 2 > windowWidth / 2) {
                    orderDetailsContainer.addClass('left');
                } else {
                    orderDetailsContainer.addClass('right');
                }
                if (!orderDetailsContainer.data('loaded') || orderDetailsContainer.children().length === 0 || orderDetailsContainer.find('.spinner-border').length > 0) {
                    orderDetailsContainer.html('<div class="d-flex justify-content-center align-items-center p-3" style="min-height: 50px;"><div class="spinner-border text-dark" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                    orderDetailsContainer.data('loaded', false); // Mark as not loaded/loading
                    getTableOrderDetails(tableId).then(response => {
                        if ($card.is(':hover') || orderDetailsContainer.is(':hover')) {
                            renderOrderDetails(response, orderDetailsContainer);
                            orderDetailsContainer.data('loaded', true); // Mark as loaded
                        }
                    }).catch(error => {
                        console.error("Error Fetching order details: ", error);
                        if ($card.is(':hover') || orderDetailsContainer.is(':hover')) {
                            orderDetailsContainer.html('<p class="text-danger p-2 text-center small">Error loading details.</p>');
                            orderDetailsContainer.data('loaded', false); // Mark as error in loading
                        }
                    });
                }
            });
            $('#tables-container').on('mouseleave.cardHover', '.table-card', function() {
                const $card = $(this);
                const orderDetailsContainer = $card.find('.order-details');

                const timer = setTimeout(function() {
                    if (!orderDetailsContainer.is(':hover')) {
                        orderDetailsContainer.empty();
                        orderDetailsContainer.data('loaded', false);
                    }
                    // CSS will make it display: none when .card is no longer hovered
                }, HIDE_DELAY);
                $card.data('hideTimer', timer); // Store timer on the card
            });

            // When mouse enters the .order-details popup itself
            $('#tables-container').on('mouseenter.detailsHover', '.order-details', function() {
                const $card = $(this).closest('.table-card'); // Get the parent card
                clearTimeout($card.data('hideTimer')); // Cancel the hide timer initiated by card's mouseleave
            });

            // When mouse leaves the .order-details popup
            $('#tables-container').on('mouseleave.detailsHover', '.order-details', function() {
                const $orderDetails = $(this);
                const $card = $orderDetails.closest('.table-card');

                const timer = setTimeout(function() {
                    $orderDetails.empty();
                    $orderDetails.data('loaded', false);
                }, HIDE_DELAY);
                $card.data('hideTimer', timer); // Store timer on the card (related to this action)
            });
            $('#tables-container').on('click.tavleCardAction', '.table-card', function() {
                const $clickedCard = $(this);
                const route = $clickedCard.data('action-route');
                const tableId = $clickedCard.data('table-id');
                // Prevent navigation if the click originated from within the order-details popup
                // or on any interactive element inside order-details (like its edit button).
                if ($(event.target).closest('.order-details').length > 0) {
                    return;
                }

                if (route && route !== '#' && !route.endsWith('PLACEHOLDER')) { // Check for valid route
                    console.log(`Table card clicked for table ID: ${tableId}. Navigating to: ${route}`);
                    window.location.href = route;
                } else {
                    console.warn(`No valid actionRoute or placeholder not replaced for table ID: ${tableId}. Route was: ${route}`);
                    // Optionally, provide a default behavior or an alert if the route is missing.
                    // alert('This action is not yet configured.');
                }
            });
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
                    $(this).addClass('active btn-secondary selected-floor').removeClass('btn-outline-secondary');

                    // Render tables for selected floor
                    renderTables(allTables.filter(t => t.floor == floor));
                });

                floorSelector.append(btn);
            });

            // Trigger click on the first floor by default to set initial active state
            // $('#floor-selector button').first().trigger('click');
        }

        function fetchAndRenderTables() {
            // 1. Get the 'data-floor' value of the currently active button
            let currentActiveFloorValue = $('#floor-selector button.active').data('floor');
            // Convert to string for consistent comparison later, as data attributes can sometimes be numbers or strings
            if (currentActiveFloorValue !== undefined) {
                currentActiveFloorValue = String(currentActiveFloorValue);
            }

            getAllDiningTables()
                .then(tables => {
                    allTables = tables;

                    // Sort floors numerically if they are numbers, or default sort if strings
                    const uniqueFloors = [...new Set(tables.map(t => t.floor))].sort((a, b) => {
                        // Basic numeric sort, can be enhanced if floors are non-numeric
                        return Number(a) - Number(b);
                    });

                    // 2. Re-render floor buttons (they lose their 'active' state here)
                    renderFloorButtons(uniqueFloors); // This function no longer auto-clicks

                    let floorToMakeActive = null;

                    // 3. Determine which floor to make active
                    if (currentActiveFloorValue !== undefined && uniqueFloors.map(String).includes(currentActiveFloorValue)) {
                        // If the previously active floor still exists, make it active
                        floorToMakeActive = currentActiveFloorValue;
                    } else if (uniqueFloors.length > 0) {
                        // Otherwise, if there are any floors, default to the first one
                        floorToMakeActive = String(uniqueFloors[0]);
                    }

                    if (floorToMakeActive !== null) {
                        // 4. Find the button for the floor to make active and trigger its click
                        // This will set its 'active' class and call renderTables via its own click handler
                        const targetButton = $(`#floor-selector button[data-floor="${floorToMakeActive}"]`);
                        if (targetButton.length) {
                            targetButton.trigger('click');
                        } else {
                            // Should not happen if floorToMakeActive was derived from uniqueFloors
                            console.warn("Target floor button not found after refresh:", floorToMakeActive);
                            $('#tables-container').html('<p class="text-white text-center">Error restoring floor view.</p>');
                        }
                    } else {
                        // No floors available at all
                        $('#tables-container').html('<p class="text-white text-center">No floors available.</p>');
                        // floorSelector is already emptied by renderFloorButtons if floors array is empty
                    }
                })
                .catch(error => {
                    console.error("Error fetching data", error);
                    // Display error in the tables container
                    $('#tables-container').html('<p class="text-danger text-center">Could not load table data. Please try again.</p>');
                });
        }
        fetchAndRenderTables()
        setInterval(fetchAndRenderTables, 30000) //refresh table every 30 seconds;
    });
</script>
@endsection