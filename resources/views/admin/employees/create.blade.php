@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding-top: 70px;">
    <div class="row mb-3" style="margin-top:10px; margin-bottom:10px;">
        <div class="col-12 d-flex flex-wrap justify-content-between align-items-center">
            <h2 class="fw-bold mb-2 mb-md-0">Thêm nhân viên</h2>
            <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.employees.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Tên</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Giới tính</label>
                        <select name="gender" class="form-select">
                            <option value="">Chọn</option>
                            <option value="male">Nam</option>
                            <option value="female">Nữ</option>
                            <option value="other">Khác</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="truongca">Trưởng ca</option>
                            <option value="ky_thuat">Kỹ thuật</option>
                            <option value="ban_hang">Bán hàng</option>
                            <option value="nha_lien_hoan">Nhà liên hoàn</option>
                            <option value="thu_ngan">Thu ngân</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Avatar</label>
                        <input type="file" name="avatar" class="form-control">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Mật khẩu</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Xác nhận mật khẩu</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>

                <button class="btn btn-success shadow-sm">
                    <i class="fas fa-plus"></i> Thêm nhân viên
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
