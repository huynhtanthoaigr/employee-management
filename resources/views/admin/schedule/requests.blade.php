@extends('layouts.app')

@section('title','Quản lý yêu cầu nghỉ & tạo lịch')

@section('content')
<div class="container-fluid" style="padding-top:80px; padding-bottom:50px;">
    <h3 class="mb-4">📅 Quản lý yêu cầu nghỉ & tạo lịch</h3>

    {{-- Chọn tuần --}}
    <form method="GET" action="{{ route('admin.schedule.requests') }}" class="row g-2 align-items-end mb-3">
        <div class="col-md-3">
            <label class="form-label">Ngày bắt đầu tuần</label>
            <input type="date" name="week_start" value="{{ $weekStart->format('Y-m-d') }}" class="form-control shadow-sm">
        </div>
        <div class="col-md-9 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-lg shadow-sm">🔍 Chọn tuần</button>
        </div>
    </form>

    {{-- Thông báo modal --}}
    @if(session('success') || session('error'))
        <div class="modal fade" id="alertModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow-lg">
                    <div class="modal-header {{ session('success') ? 'bg-success text-white' : 'bg-danger text-white' }}">
                        <h5 class="modal-title">{{ session('success') ? '✅ Thành công' : '⚠️ Lỗi' }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body fs-5">{{ session('success') ?? session('error') }}</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Form lưu yêu cầu nghỉ --}}
    <form action="{{ route('admin.schedule.requests.save') }}" method="POST">
        @csrf
        <input type="hidden" name="week_start" value="{{ $weekStart->format('Y-m-d') }}">
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-bordered table-hover align-middle text-center mb-0">
                <thead class="table-dark text-white">
                    <tr>
                        <th>👤 Nhân viên</th>
                        <th>📌 Vị trí</th>
                        @foreach($week as $day)
                            <th>{{ \Carbon\Carbon::parse($day)->format('D, d/m') }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $emp)
                        <tr>
                            <td class="fw-semibold">{{ $emp->name }}</td>
                            <td><span class="badge bg-info">{{ $emp->role ?? '---' }}</span></td>
                            @foreach($week as $day)
                                @php
                                    $req = \App\Models\ScheduleRequest::where('employee_id',$emp->id)
                                        ->where('date',$day)->first();
                                    $status = $req->status ?? 'present';
                                @endphp
                                <td>
                                    <select name="requests[{{ $emp->id }}][{{ $day }}]" class="form-select form-select-sm shadow-sm">
                                        <option value="present" {{ $status=='present'?'selected':'' }}>✅ Đi làm</option>
                                        <option value="morning_off" {{ $status=='morning_off'?'selected':'' }}>🌅 Nghỉ sáng</option>
                                        <option value="afternoon_off" {{ $status=='afternoon_off'?'selected':'' }}>🌆 Nghỉ chiều</option>
                                        <option value="full_day_off" {{ $status=='full_day_off'?'selected':'' }}>🛌 Nghỉ cả ngày</option>
                                    </select>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex gap-2 mt-3">
            <button type="submit" class="btn btn-success btn-lg rounded-pill shadow-sm">💾 Lưu yêu cầu nghỉ</button>
            <button type="button" class="btn btn-warning btn-lg rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#confirmGenerateModal">⚡ Tạo lịch tuần</button>
        </div>
    </form>

    {{-- Modal xác nhận tạo lịch --}}
    <div class="modal fade" id="confirmGenerateModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">⚡ Xác nhận tạo lịch tuần</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body fs-5">
                    Bạn có chắc chắn muốn tạo lịch tuần từ <b>{{ $weekStart->format('d/m/Y') }}</b> không?<br>
                    (Hãy chắc chắn bạn đã lưu yêu cầu nghỉ trước đó.)
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">❌ Hủy</button>
                    <form method="POST" action="{{ route('admin.schedule.generate') }}">
                        @csrf
                        <input type="hidden" name="week_start" value="{{ $weekStart->format('Y-m-d') }}">
                        <button type="submit" class="btn btn-warning rounded-pill">⚡ Đồng ý tạo lịch</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if(session('success') || session('error'))
<script>
    var alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
    alertModal.show();
</script>
@endif
@endpush
