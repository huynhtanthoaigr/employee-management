<div class="main-header">
    <div class="main-header-logo">
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

    <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
        <div class="container-fluid">
            {{-- Search --}}
            <nav class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <button type="submit" class="btn btn-search pe-1"><i
                                class="fa fa-search search-icon"></i></button>
                    </div>
                    <input type="text" placeholder="Search ..." class="form-control" />
                </div>
            </nav>

            {{-- Topbar Icons --}}
            <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                {{-- Notifications --}}
                <li class="nav-item dropdown">
                    <a class="nav-link position-relative" href="#" id="notifDropdown" data-bs-toggle="dropdown">
                        <i class="fa fa-bell"></i>
                        @if($notifications->count() > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $notifications->count() }}
                            </span>
                        @endif
                    </a>

                    <div class="dropdown-menu dropdown-menu-end p-0 shadow" aria-labelledby="notifDropdown"
                        style="width: 300px;">
                        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                            <span class="fw-bold">Thông báo</span>
                            <form action="{{ route('admin.notifications.markAllRead') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-primary">Đánh dấu tất cả</button>
                            </form>
                        </div>

                        <div class="notif-scroll" style="max-height: 300px; overflow-y: auto;">
                            @forelse($notifications as $notification)
                                <div class="d-flex align-items-start px-3 py-2 border-bottom">
                                    <div class="me-2">
                                        <i class="fa fa-calendar-check text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <small>
                                            Nhân viên <strong>{{ $notification->data['employee_name'] }}</strong>
                                            đăng ký lịch làm ngày
                                            {{ \Carbon\Carbon::parse($notification->data['date'])->format('d/m/Y') }}
                                        </small>
                                    </div>
                                    <form action="{{ route('admin.notification.destroy', $notification->id) }}"
                                        method="POST" class="ms-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger p-1"
                                            style="font-size: 10px;">Xóa</button>
                                    </form>
                                </div>
                            @empty
                                <div class="text-center text-muted py-3">Không có thông báo mới</div>
                            @endforelse
                        </div>

                        <a href="{{ route('admin.schedule.requests') }}"
                            class="dropdown-item text-center py-2 border-top">
                            Xem tất cả <i class="fa fa-angle-right"></i>
                        </a>
                    </div>
                </li>



                {{-- User Dropdown --}}
                <li class="nav-item topbar-user dropdown hidden-caret">
                    <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                        <div class="avatar-sm">
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="..."
                                class=" avatar-img rounded-circle" />
                        </div>
                        <span class="profile-username">
                            <span class="op-7">Hi,</span>
                            <span class="fw-bold">{{ auth()->user()->name }}</span>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <div class="dropdown-user-scroll scrollbar-outer">
                            <li>
                                <div class="user-box">
                                    <div class="avatar-lg">
                                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="image profile"
                                            class="avatar-img rounded-circle" />
                                    </div>
                                    <div class="u-text">
                                        <h4>{{ auth()->user()->name }}</h4>
                                        <p class="text-muted">{{ auth()->user()->email }}</p>
                                        <a href="{{ route('profile.edit') }}"
                                            class="btn btn-xs btn-secondary btn-sm">View Profile</a>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item text-danger" type="submit">Logout</button>
                                </form>
                            </li>
                        </div>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</div>