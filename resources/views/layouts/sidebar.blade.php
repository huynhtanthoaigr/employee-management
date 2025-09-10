<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="{{ route('admin.dashboard') }}" class="logo">
                <img src="{{ asset('assets/img/kaiadmin/logo_light.svg') }}" alt="navbar brand" class="navbar-brand"
                    height="20" />
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar"><i class="gg-menu-right"></i></button>
                <button class="btn btn-toggle sidenav-toggler"><i class="gg-menu-left"></i></button>
            </div>
            <button class="topbar-toggler more"><i class="gg-more-vertical-alt"></i></button>
        </div>
    </div>

    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                {{-- Dashboard cho tất cả người dùng --}}
                <li class="nav-item active">
                    @if(auth()->user()->role === 'quanly')
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-home"></i>
                            <p>Dashboard</p>
                        </a>
                    @else
                        <a href="{{ route('user.dashboard') }}" >
                            <i class="fas fa-home"></i> <!-- Icon Home -->
                            <span>Dashboard</span>
                        </a>

                        <a href="{{ route('user.schedule') }}" >
                            <i class="fas fa-calendar-check"></i> <!-- Icon Lịch làm việc -->
                            <span>Lịch làm việc</span>
                        </a>

                        <a href="{{ route('user.schedule.requests') }}">
                            <i class="fas fa-bed"></i> <!-- Icon Đăng ký nghỉ -->
                            <span>Đăng ký lịch nghỉ</span>
                        </a>

                    @endif

                </li>

                {{-- Quản lý nhân viên chỉ cho quản lý --}}
                @if(auth()->user()->role === 'quanly')
                    <li class="nav-item">
                        <a href="{{ route('admin.employees.index') }}">
                            <i class="fas fa-users"></i>
                            <p>Quản lý nhân viên</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.schedule.view') }}">
                            <i class="fas fa-calendar-alt"></i>
                            <p>Lịch làm việc</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.attendance.index') }}">
                            <i class="fas fa-check-circle"></i>
                            <p>Chấm công</p>
                        </a>
                    </li>
                    {{-- Nút mới: Chấm công tuần --}}
                    <li class="nav-item">
                        <a href="{{ route('admin.attendance.weekly') }}">
                            <i class="fas fa-chart-bar"></i>
                            <p>Xem chấm công tuần</p>
                        </a>
                    </li>
                @endif

            </ul>
        </div>
    </div>
</div>