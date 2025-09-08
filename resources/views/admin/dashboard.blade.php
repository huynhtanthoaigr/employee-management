@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">Dashboard Admin</h4>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Xin chào, {{ auth()->user()->name }} 👋</h4>
                </div>
                <div class="card-body">
                    <p>Chào mừng bạn đến với trang quản trị HD KIDS.</p>

                    <div class="mb-3">
                        <a href="{{ route('admin.employees.index') }}" class="btn btn-primary">
                            <i class="fas fa-users"></i> Quản lý nhân viên
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
