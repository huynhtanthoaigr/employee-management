@extends('layouts.app')

@section('title', 'Dashboard Nhân viên')

@section('content')
    <div class="container-fluid" style="padding-top: 50px; padding-bottom: 50px;">

        <div class="d-flex justify-content-center align-items-center" style="height: 70px;">
            <h2 class="fw-bold text-center">
                Chào mừng, <strong>{{ auth()->user()->name }}</strong>!
            </h2>
        </div>


        <div class="row g-3 mb-4">
            {{-- Thông tin cá nhân --}}
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm h-100 mb-3">
                    <div class="card-body d-flex align-items-center">
                        <i class="fas fa-user fa-2x text-primary me-3"></i>
                        <div>
                            <h5 class="card-title mb-1">Thông tin cá nhân</h5>
                            <p class="card-text mb-0">Xem và cập nhật thông tin cá nhân.</p>
                            <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-primary mt-2">Xem</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Lịch làm việc --}}
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm h-100 mb-3">
                    <div class="card-body d-flex align-items-center">
                        <i class="fas fa-calendar-alt fa-2x text-success me-3"></i>
                        <div>
                            <h5 class="card-title mb-1">Lịch làm việc</h5>
                            <p class="card-text mb-0">Xem lịch ca, ngày nghỉ và công việc.</p>
                            <a href="{{ route('user.schedule') }}" class="btn btn-sm btn-outline-success mt-2">Xem</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Check-in/out --}}
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm h-100 mb-3">
                    <div class="card-body d-flex align-items-center">
                        <i class="fas fa-clock fa-2x text-warning me-3"></i>
                        <div>
                            <h5 class="card-title mb-1">Đăng ký lịch làm việc </h5>
                            <p class="card-text mb-0">Quản lý lịch làm các lịch làm tuần tiếp theo</p>
                            <a href="{{ route('user.schedule.requests') }}" class="btn btn-sm btn-outline-warning mt-2">Quản lý</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection