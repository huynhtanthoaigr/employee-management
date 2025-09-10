@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
 <div class="container-fluid" style="padding-top: 60px;">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary">Dashboard Quản Lý Nhân Viên</h2>
        <p class="text-muted">Xem số lượng nhân viên theo vị trí và thông tin chi tiết</p>
    </div>

    <div class="row g-4">
        @foreach($positions as $position)
        <div class="col-md-3">
            <div class="card shadow-sm h-100 text-center position-card" 
                 style="background: #a5daa1ff; color: #000000ff; border-radius: 12px;">
                <div class="card-body d-flex flex-column justify-content-center align-items-center p-4">
                    <h5 class="card-title mb-2 fw-bold">{{ ucfirst($position->role) }}</h5>
                    <p class="card-text mb-3 text-muted">
                        Số lượng nhân viên: <strong>{{ $position->employee_count }}</strong>
                    </p>
                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-{{ $position->role }}">
                        Xem nhân viên
                    </button>
                </div>
            </div>
        </div>

        {{-- Modal chi tiết nhân viên --}}
        <div class="modal fade" id="modal-{{ $position->role }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered {{ $position->employee_count > 6 ? 'modal-lg modal-dialog-scrollable' : '' }}">
                <div class="modal-content rounded-4 border-0 shadow-sm">
                    <div class="modal-header" style="background: #e3f2fd; color: #333;">
                        <h5 class="modal-title">{{ ucfirst($position->role) }} - Danh sách nhân viên</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            @forelse($position->employees as $employee)
                                <div class="col-md-6">
                                    <div class="card border-light shadow-sm h-100 hover-card">
                                        <div class="card-body d-flex align-items-center">
                                            {{-- Avatar --}}
                                            @if($employee->avatar)
                                                <img src="{{ asset('storage/' . $employee->avatar) }}" class="rounded-circle me-3" width="50" height="50">
                                            @else
                                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                    style="width:50px; height:50px;">
                                                    <i class="fas fa-user fa-lg"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-1 fw-bold">{{ $employee->name }}</h6>
                                                <p class="mb-0 text-muted" style="font-size:0.85rem;">
                                                    Email: {{ $employee->email }}<br>
                                                    Phone: {{ $employee->phone ?? '-' }}<br>
                                                    Vai trò: {{ $employee->role }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center text-muted">
                                    Chưa có nhân viên
                                </div>
                            @endforelse
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- CSS tùy chỉnh --}}
<style>
.position-card {
    transition: transform 0.3s, box-shadow 0.3s;
}
.position-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.hover-card {
    transition: transform 0.2s, box-shadow 0.2s;
}
.hover-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
</style>
@endsection
