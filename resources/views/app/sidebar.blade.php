<div class="sidebar-wrapper">
    <nav class="mt-2">
        <!--begin::Sidebar Menu-->
        <ul
            class="nav sidebar-menu flex-column"
            data-lte-toggle="treeview"
            role="menu"
            data-accordion="false">
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon bi bi-speedometer"></i>
                    <p>
                        Dashboard
                        <i class="nav-arrow bi bi-chevron-right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="../index.html" class="nav-link">
                            <i class="nav-icon bi bi-circle"></i>
                            <p>Dashboard v1</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../index2.html" class="nav-link">
                            <i class="nav-icon bi bi-circle"></i>
                            <p>Dashboard v2</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../index3.html" class="nav-link">
                            <i class="nav-icon bi bi-circle"></i>
                            <p>Dashboard v3</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href={{ route('tables.index') }} class="nav-link">
                    <img src="{{ asset("icons/seats.png") }}" alt="" style="width: 24px; height: 24px;">
                    <p>Tables</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class=" nav-icon fa-solid fa-book-open"></i>
                    <p>Menu</p>
                    <i class="nav-arrow bi bi-chevron-right"></i>

                </a>

                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href={{ route('menus.index') }} class="nav-link">
                            <i class="nav-icon bi bi-circle"></i>
                            <p>Menu Items</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href={{ route('menuCategories.index') }} class="nav-link">
                            <i class="nav-icon bi bi-circle"></i>
                            <p>Menu Categories</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href={{ route('orders.index') }} class="nav-link">
                    <i class=" nav-icon fa-solid fa-note-sticky"></i>
                    <p>Orders</p>
                </a>
            </li>
            <li class="nav-item">
                <a href={{route('ingredients.index')  }} class="nav-link">
                    <img src={{ asset('icons/ingredients-grey.png') }} style="height: 1.25rem; width: 1.25rem;" alt="ingredients icon">
                    <p>Ingredients</p>
                </a>

            </li>
        </ul>
        <!--end::Sidebar Menu-->
    </nav>
</div>