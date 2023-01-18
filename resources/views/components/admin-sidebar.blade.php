<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}">Library Management</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('admin.dashboard') }}">LMS</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Admin</li>
            <li class="nav-item dropdown {{ $type_menu === 'dashboard' ? 'active' : '' }}">
                <a href="#"
                   class="nav-link has-dropdown"><i class="fas fa-fire"></i><span>Dashboard</span></a>
                <ul class="dropdown-menu">
                    <li class='{{ Request::is('admin') ? 'active' : '' }}'>
                        <a class="nav-link"
                           href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                </ul>
            </li>
            <li class="menu-header">Books</li>
            <li class="nav-item dropdown {{ $type_menu === 'books' ? 'active' : '' }}">
                <a href="#"
                   class="nav-link has-dropdown"><i class="far fa-user"></i> <span>Books</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('books') ? 'active' : '' }}">
                        <a href="{{ route('books.index') }}">View All Books</a>
                    </li>
                    <li class="{{ Request::is('books/create') ? 'active' : '' }}">
                        <a href="{{ route('books.create') }}">Add a Book</a>
                    </li>
                </ul>
            </li>
            <li class="menu-header">Users</li>
            <li class="nav-item dropdown {{ $type_menu === 'users' ? 'active' : '' }}">
                <a href="#"
                   class="nav-link has-dropdown"><i class="far fa-user"></i> <span>Customers</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('customers') ? 'active' : '' }}">
                        <a href="{{ route('customers.index') }}">View All Customers</a>
                    </li>
                    <li class="{{ Request::is('users/create') ? 'active' : '' }}">
                        <a href="{{ route('customers.create') }}">Add a Customer</a>
                    </li>
                </ul>
            </li>
        </ul>
    </aside>
</div>
