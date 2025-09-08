@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding-top: 70px;">
    <div class="row mb-3" style="margin-top:10px; margin-bottom:10px;">
        <div class="col-12 d-flex flex-wrap justify-content-between align-items-center">
            <h2 class="fw-bold mb-2 mb-md-0">Sửa nhân viên</h2>
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
            <form action="{{ route('admin.employees.update', $employee) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Tên</label>
                        <input type="text" name="name" class="form-control" value="{{ $employee->name }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $employee->email }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ $employee->phone }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Giới tính</label>
                        <select name="gender" class="form-select">
                            <option value="">Chọn</option>
                            <option value="male" {{ $employee->gender == 'male' ? 'selected' : '' }}>Nam</option>
                            <option value="female" {{ $employee->gender == 'female' ? 'selected' : '' }}>Nữ</option>
                            <option value="other" {{ $employee->gender == 'other' ? 'selected' : '' }}>Khác</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="truongca" {{ $employee->role == 'truongca' ? 'selected' : '' }}>Trưởng ca</option>
                            <option value="ky_thuat" {{ $employee->role == 'ky_thuat' ? 'selected' : '' }}>Kỹ thuật</option>
                            <option value="ban_hang" {{ $employee->role == 'ban_hang' ? 'selected' : '' }}>Bán hàng</option>
                            <option value="nha_lien_hoan" {{ $employee->role == 'nha_lien_hoan' ? 'selected' : '' }}>Nhà liên hoàn</option>
                            <option value="thu_ngan" {{ $employee->role == 'thu_ngan' ? 'selected' : '' }}>Thu ngân</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Avatar</label>
                        <input type="file" name="avatar" class="form-control" onchange="previewAvatar(event)">
                        @if($employee->avatar)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $employee->avatar) }}" id="avatarPreview" width="80" height="80" class="rounded-circle border">
                            </div>
                        @else
                            <div class="mt-2">
                                <img src="" id="avatarPreview" width="80" height="80" class="rounded-circle border" style="display:none;">
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Mật khẩu mới (để trống nếu không đổi)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Xác nhận mật khẩu</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                </div>

                <button class="btn btn-primary shadow-sm">
                    <i class="fas fa-save"></i> Cập nhật nhân viên
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Script preview avatar --}}
<script>
    function previewAvatar(event) {
        const output = document.getElementById('avatarPreview');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.style.display = 'block';
        output.onload = function() {
            URL.revokeObjectURL(output.src) // free memory
        }
    }
</script>
@endsection
