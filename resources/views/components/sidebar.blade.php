<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{url('dashboard')}}">Library Management</a>
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
                            href="{{ url('dashboard') }}">General Dashboard</a>
                    </li>
                </ul>
            </li>
            <li class="menu-header">Stisla</li>
            <li class="menu-header">Pages</li>
            <li class="nav-item dropdown {{ $type_menu === 'auth' ? 'active' : '' }}">
                <a href="#"
                    class="nav-link has-dropdown"><i class="far fa-user"></i> <span>Auth</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('auth-login') ? 'active' : '' }}">
                        <a href="{{ url('auth-login') }}">Login</a>
                    </li>
                    <li class="{{ Request::is('auth-forgot-password') ? 'active' : '' }}">
                        <a href="{{ url('auth-forgot-password') }}">Forgot Password</a>
                    </li>
                </ul>
            </li>
        </ul>

        <div class="hide-sidebar-mini mt-4 mb-4 p-3">
            <a href="https://getstisla.com/docs"
                class="btn btn-primary btn-lg btn-block btn-icon-split">
                <i class="fas fa-rocket"></i> Documentation
            </a>
        </div>
    </aside>
</div>
