@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding-top: 60px;">
    <h3 class="mb-3">📋 Chấm công nhân viên - {{ $date }}</h3>

    {{-- Chọn ngày --}}
    <form action="{{ route('admin.attendance.index') }}" method="GET"
        class="mb-3 d-flex gap-2 align-items-center flex-wrap">
        <input type="date" name="date" value="{{ $date }}" class="form-control" style="max-width: 150px;">
        <button type="submit" class="btn btn-primary btn-sm">Xem</button>
    </form>

    {{-- Bảng chấm công --}}
    <div class="table-responsive">
        <table class="table table-bordered text-center align-middle">
            <thead class="table-primary">
                <tr>
                    <th>Nhân viên</th>
                    <th>Ca làm</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Trạng thái</th>
                    <th>Tăng ca</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $employee)
                    @php
                        $schedule = $schedules->firstWhere('employee_id', $employee->id);
                        $attendance = $schedule ? $schedule->attendances->first() : null;
                    @endphp
                    <tr>
                        <td>{{ $employee->name }}</td>
                        <td>{{ $schedule->shift->name ?? '-' }}</td>
                        <td>{{ $attendance->check_in ?? '-' }}</td>
                        <td>{{ $attendance->check_out ?? '-' }}</td>
                        <td>
                            @if($attendance && $attendance->check_in && $attendance->check_out)
                                ✔️ Đã chấm công
                            @else
                                ⏳ Chưa chấm công
                            @endif
                        </td>
                        <td>
                            @if($attendance && $attendance->overtime_hours)
                                {{ $attendance->overtime_hours }} giờ - 
                                <span class="badge 
                                    @if($attendance->overtime_status == 'Chờ duyệt') bg-warning
                                    @elseif($attendance->overtime_status == 'Đã duyệt') bg-success
                                    @endif">
                                    {{ $attendance->overtime_status }}
                                </span>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            {{-- Chấm công thủ công --}}
                            <form action="{{ route('admin.attendance.mark') }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="schedule_id" value="{{ $schedule->id ?? 0 }}">
                                <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                                <button type="submit" class="btn btn-sm btn-success">Chấm công</button>
                            </form>

                            {{-- Nếu có tăng ca nhưng chờ duyệt --}}
                            @if($attendance && $attendance->overtime_hours && $attendance->overtime_status == 'Chờ duyệt')
                                <form action="{{ route('admin.attendance.approve') }}" method="POST" class="d-inline ms-1">
                                    @csrf
                                    <input type="hidden" name="attendance_id" value="{{ $attendance->id }}">
                                    <button type="submit" class="btn btn-sm btn-info">Duyệt tăng ca</button>
                                </form>
                            @elseif($attendance && $attendance->check_out && (!$attendance->overtime_hours || $attendance->overtime_hours == 0))
                                {{-- Nút tăng ca mở modal --}}
                                <button type="button" class="btn btn-sm btn-warning ms-1" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#overtimeModal" 
                                    data-attendance-id="{{ $attendance->id }}"
                                    data-current-hours="{{ $attendance->overtime_hours ?? 1 }}">
                                    💪 Tăng ca
                                </button>
                            @endif

                            {{-- Xóa chấm công --}}
                            @if($attendance)
                                <form action="{{ route('admin.attendance.delete') }}" method="POST" class="d-inline ms-1">
                                    @csrf
                                    <input type="hidden" name="attendance_id" value="{{ $attendance->id }}">
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa chấm công này?')">Xóa</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Tăng ca --}}
<div class="modal fade" id="overtimeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">⏱️ Chọn số giờ tăng ca (1 - 6 giờ)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.attendance.addOvertime') }}" method="POST" id="overtimeForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="attendance_id" id="modalAttendanceId">
                    <select name="overtime_hours" id="modalOvertimeHours" class="form-select">
                        @for($i=1; $i<=6; $i++)
                            <option value="{{ $i }}">{{ $i }} giờ</option>
                          
                        @endfor
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">💪 Cập nhật tăng ca</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal thông báo --}}
<div class="modal fade" id="alertModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">📢 Thông báo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body fs-5">
                @if(session('success'))
                    {{ session('success') }}
                @elseif(session('error'))
                    {{ session('error') }}
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Hiển thị alert modal nếu có session
    @if(session('success') || session('error'))
        var alertModal = new bootstrap.Modal(document.getElementById('alertModal'), { backdrop: 'static', keyboard: false });
        alertModal.show();
    @endif

    // Truyền dữ liệu sang modal khi bấm nút tăng ca
    var overtimeModal = document.getElementById('overtimeModal');
    overtimeModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var attendanceId = button.getAttribute('data-attendance-id');
        var currentHours = button.getAttribute('data-current-hours');

        document.getElementById('modalAttendanceId').value = attendanceId;
        document.getElementById('modalOvertimeHours').value = currentHours || 1;
    });
});
</script>
@endpush

<style>
table tbody tr:hover {
    background-color: #f1f1f1;
}

.badge {
    font-size: 0.9rem;
    padding: 0.4em 0.6em;
}

@media (max-width: 576px) {
    .table-responsive {
        overflow-x: auto;
    }
}
</style>
