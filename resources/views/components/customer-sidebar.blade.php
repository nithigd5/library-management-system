<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('dashboard') }}">Library Management</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('dashboard') }}">LMS</a>
        </div>
        <ul class="sidebar-menu">
            <li class='nav-item {{ Request::is('admin') ? 'active' : '' }}'>
                <a class="nav-link"
                   href="{{ route('dashboard') }}"><i class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>
            <li class="menu-header">Books</li>
            <li class="nav-item dropdown {{ $type_menu === 'books' ? 'active' : '' }}">
                <a href="#"
                   class="nav-link has-dropdown"><i class="fa-solid fa-book"></i> <span>Books</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('books') ? 'active' : '' }}">
                        <a href="{{ route('book.index') }}">All Books</a>
                    </li>
                </ul>
            </li>
            <li class="menu-header">Purchases</li>
            <li class="nav-item dropdown {{ $type_menu === 'purchases' ? 'active' : '' }}">
                <a href="#"
                   class="nav-link has-dropdown"><i class="fa-solid fa-money-bill"></i><span>Purchases</span></a>
{{--                <ul class="dropdown-menu">--}}
{{--                    <li class="{{ Request::is('purchases') ? 'active' : '' }}">--}}
{{--                        <a href="{{ route('admin.purchases.index') }}">All Purchases</a>--}}
{{--                    </li>--}}

{{--                    <li class="{{ Request::is('purchases/open') ? 'active' : '' }}">--}}
{{--                        <a href="{{ route('admin.purchases.open') }}">Open Purchases</a>--}}
{{--                    </li>--}}

{{--                    <li class="{{ Request::is('purchases/closed') ? 'active' : '' }}">--}}
{{--                        <a href="{{ route('admin.purchases.closed') }}">closed Purchases</a>--}}
{{--                    </li>--}}
{{--                </ul>--}}
            </li>
        </ul>
    </aside>
</div>
