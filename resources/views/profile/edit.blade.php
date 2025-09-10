@extends('layouts.app')

@section('title', 'Thông tin cá nhân')

@section('content')
<div class="container-fluid" style="padding-top: 100px; padding-bottom: 60px;">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="row g-4">


            
                <!-- Sidebar / Avatar & Info -->
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 rounded-4 text-center p-4">
                        <!-- Avatar -->
                        <div class="mb-3">
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" 
                                     class="rounded-circle border" width="120" height="120" 
                                     alt="Avatar" style="box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                            @else
                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width:120px; height:120px; margin: 0 auto; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                                    <i class="fas fa-user fa-2x"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Name -->
                        <h4 class="mb-1">{{ auth()->user()->name }}</h4>

                        <!-- Role -->
                        <p class="text-muted mb-2" style="font-style: italic;">
                            Vị trí: 
                            @switch(auth()->user()->role)
                                @case('quanly') Quản lý @break
                                @case('truongca') Trưởng ca @break
                                @case('ky_thuat') Kỹ thuật @break
                                @case('ban_hang') Bán hàng @break
                                @case('nha_lien_hoan') Nhà liên hoàn @break
                                @default Khác
                            @endswitch
                        </p>

                        <!-- Email -->
                        <p class="mb-4 text-truncate">{{ auth()->user()->email }}</p>

                        <!-- Tabs -->
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary active" id="tab-info-btn">Thông tin cá nhân</button>
                            <button class="btn btn-outline-primary" id="tab-password-btn">Đổi mật khẩu</button>
                        </div>
                    </div>
                </div>

                <!-- Content / Forms -->
                <div class="col-md-8">
                    <div class="card shadow-sm border-0 rounded-4 p-4">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Tab: Thông tin cá nhân -->
                        <div id="tab-info">
                            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="avatar" class="form-label">Đổi avatar</label>
                                    <input type="file" name="avatar" id="avatar" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label for="name" class="form-label">Tên</label>
                                    <input type="text" name="name" id="name" value="{{ auth()->user()->name }}" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" id="email" value="{{ auth()->user()->email }}" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">Số điện thoại</label>
                                    <input type="text" name="phone" id="phone" value="{{ auth()->user()->phone }}" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label for="gender" class="form-label">Giới tính</label>
                                    <select name="gender" id="gender" class="form-select">
                                        <option value="">Chọn</option>
                                        <option value="male" {{ auth()->user()->gender == 'male' ? 'selected' : '' }}>Nam</option>
                                        <option value="female" {{ auth()->user()->gender == 'female' ? 'selected' : '' }}>Nữ</option>
                                        <option value="other" {{ auth()->user()->gender == 'other' ? 'selected' : '' }}>Khác</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">Cập nhật thông tin</button>
                            </form>
                        </div>

                        <!-- Tab: Đổi mật khẩu -->
                        <div id="tab-password" style="display: none;">
                           <form action="{{ route('profile.update') }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="password" class="form-label">Mật khẩu mới</label>
        <input type="password" name="password" id="password" class="form-control" placeholder="Để trống nếu không đổi">
    </div>

    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Để trống nếu không đổi">
    </div>

    <button type="submit" class="btn btn-primary w-100">Đổi mật khẩu</button>
</form>

                        </div>
                    </div>
                </div>

            </div> <!-- end row -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const tabInfoBtn = document.getElementById('tab-info-btn');
    const tabPasswordBtn = document.getElementById('tab-password-btn');
    const tabInfo = document.getElementById('tab-info');
    const tabPassword = document.getElementById('tab-password');

    tabInfoBtn.addEventListener('click', () => {
        tabInfo.style.display = 'block';
        tabPassword.style.display = 'none';
        tabInfoBtn.classList.add('active');
        tabPasswordBtn.classList.remove('active');
    });

    tabPasswordBtn.addEventListener('click', () => {
        tabInfo.style.display = 'none';
        tabPassword.style.display = 'block';
        tabPasswordBtn.classList.add('active');
        tabInfoBtn.classList.remove('active');
    });
</script>
@endpush
