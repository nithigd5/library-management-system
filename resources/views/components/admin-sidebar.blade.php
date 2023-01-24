<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}">Library Management</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('admin.dashboard') }}">LMS</a>
        </div>
        <ul class="sidebar-menu">
            <li class='nav-item {{ Request::is('admin') ? 'active' : '' }}'>
                <a class="nav-link"
                   href="{{ route('admin.dashboard') }}"><i class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>
            <li class="menu-header">Books</li>
            <li class="nav-item dropdown {{ $type_menu === 'books' ? 'active' : '' }}">
                <a href="#"
                   class="nav-link has-dropdown"><i class="fa-solid fa-book"></i> <span>Books</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('books') ? 'active' : '' }}">
                        <a href="{{ route('books.index') }}">All Books</a>
                    </li>
                    <li class="{{ Request::is('books/create') ? 'active' : '' }}">
                        <a href="{{ route('books.create') }}">Add a Book</a>
                    </li>
                </ul>
            </li>
            <li class="menu-header">Users</li>
            <li class="nav-item dropdown {{ $type_menu === 'customers' ? 'active' : '' }}">
                <a href="#"
                   class="nav-link has-dropdown"><i class="far fa-user"></i> <span>Customers</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('customers') ? 'active' : '' }}">
                        <a href="{{ route('customers.index') }}">All Customers</a>
                    </li>
                    <li class="{{ Request::is('customers/create') ? 'active' : '' }}">
                        <a href="{{ route('customers.create') }}">Add a Customer</a>
                    </li>
                </ul>
            </li>
            <li class="menu-header">Purchases</li>
            <li class="nav-item dropdown {{ $type_menu === 'purchases' ? 'active' : '' }}">
                <a href="#"
                   class="nav-link has-dropdown"><i class="fa-solid fa-money-bill"></i><span>Purchases</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('purchases') ? 'active' : '' }}">
                        <a href="{{ route('purchases.index') }}">All Purchases</a>
                    </li>

                    <li class="{{ Request::is('purchases/open') ? 'active' : '' }}">
                        <a href="{{ route('purchases.open') }}">Open Purchases</a>
                    </li>

                    <li class="{{ Request::is('purchases/closed') ? 'active' : '' }}">
                        <a href="{{ route('purchases.closed') }}">closed Purchases</a>
                    </li>
                </ul>
            </li>
        </ul>
    </aside>
</div>
