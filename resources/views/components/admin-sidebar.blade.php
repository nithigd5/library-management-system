<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{url('  dashboard')}}">Library Management</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{url('dashboard')}}">LMS</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="nav-item dropdown {{ $type_menu === 'dashboard' ? 'active' : '' }}">
                <a href="#"
                   class="nav-link has-dropdown"><i class="fas fa-fire"></i><span>Dashboard</span></a>
                <ul class="dropdown-menu">
                    <li class='{{ Request::is('dashboard') ? 'active' : '' }}'>
                        <a class="nav-link"
                           href="{{ url('dashboard') }}">Dashboard</a>
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
        </ul>
    </aside>
</div>
