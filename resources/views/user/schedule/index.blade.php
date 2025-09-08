@extends('layouts.app')

@section('content')
    <div class="container-fluid" style="padding-top: 60px;">
        <h3 class="mb-3">📅 Lịch làm việc của tôi</h3>

        {{-- Form chọn tuần và điều hướng tuần trước/sau --}}
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <form action="{{ route('user.schedule') }}" method="GET" class="d-flex align-items-center gap-2 mb-2 mb-md-0">
                <label for="week_start" class="fw-bold mb-0">Chọn tuần:</label>
                <input type="date" id="week_start" name="week_start" class="form-control"
                    value="{{ $weekStart->format('Y-m-d') }}" style="max-width: 150px;">
                <button type="submit" class="btn btn-primary">Xem</button>
            </form>

            <div class="d-flex gap-2">
                <a href="{{ route('user.schedule', ['week_start' => $weekStart->copy()->subWeek()->toDateString()]) }}"
                    class="btn btn-outline-primary">⬅️ Tuần trước</a>
                <a href="{{ route('user.schedule', ['week_start' => $weekStart->copy()->addWeek()->toDateString()]) }}"
                    class="btn btn-outline-primary">Tuần sau ➡️</a>
            </div>
        </div>

        {{-- Bảng lịch làm việc --}}
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>Ngày / Thứ</th>
                        <th>Ca làm</th>
                        <th>Trạng thái chấm công</th>
                        <th>Tăng ca</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $daysOfWeekVi = ['Monday' => 'Thứ 2', 'Tuesday' => 'Thứ 3', 'Wednesday' => 'Thứ 4', 'Thursday' => 'Thứ 5', 'Friday' => 'Thứ 6', 'Saturday' => 'Thứ 7', 'Sunday' => 'Chủ nhật'];
                    @endphp
                    @foreach($week as $day)
                        @php
                            $schedule = $schedules->firstWhere('date', $day);
                            $attendance = $schedule ? $schedule->attendances()->where('employee_id', auth()->id())->first() : null;
                            $dayOfWeek = \Carbon\Carbon::parse($day)->format('l');
                            $dayFormatted = \Carbon\Carbon::parse($day)->format('d/m/Y');
                        @endphp
                        <tr @if(\Carbon\Carbon::parse($day)->isToday()) class="table-success fw-bold" @endif>
                            <td>{{ $dayFormatted }} <br><small>{{ $daysOfWeekVi[$dayOfWeek] ?? $dayOfWeek }}</small></td>

                            {{-- Ca làm --}}
                            <td>
                                @if($schedule)
                                    <span class="badge bg-success">{{ $schedule->shift->name }}</span>
                                @else
                                    <span class="badge bg-danger">Nghỉ</span>
                                @endif
                            </td>

                            {{-- Trạng thái chấm công --}}
                            <td>
                                @if(!$schedule)
                                    -
                                @elseif(!$attendance || !$attendance->check_in)
                                    <form action="{{ route('user.attendance.checkin') }}" method="POST" class="mb-1">
                                        @csrf
                                        <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                                        <button class="btn btn-sm btn-success w-10">✅ Check-in</button>
                                    </form>
                                @elseif(!$attendance->check_out)
                                    <span class="text-warning d-block mb-1">⏳ Đợi Check-out</span>
                                    <form action="{{ route('user.attendance.checkout') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                                        <button class="btn btn-sm btn-warning w-10">⏹️ Check-out</button>
                                    </form>
                                @else
                                    ✔️ Đã chấm công
                                @endif
                            </td>

                            {{-- Tăng ca --}}
                            <td>
                                @if($attendance && $attendance->check_out)
                                    @if(!$attendance->overtime_hours)
                                        <button class="btn btn-sm btn-info w-10" data-bs-toggle="modal" data-bs-target="#overtimeModal"
                                            data-schedule-id="{{ $schedule->id }}">
                                            ⏱️ Tăng ca
                                        </button>
                                    @else
                                        {{ $attendance->overtime_hours }} giờ ({{ $attendance->overtime_status }})
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal tăng ca --}}
    <div class="modal fade" id="overtimeModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('user.attendance.overtime') }}" method="POST">
                    @csrf
                    <input type="hidden" name="schedule_id" id="modal_schedule_id">
                    <div class="modal-header">
                        <h5 class="modal-title">⏱️ Đăng ký tăng ca</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label for="hours">Chọn số giờ:</label>
                        <select name="hours" id="hours" class="form-select">
                            @for($i = 1; $i <= 6; $i++)
                                <option value="{{ $i }}">{{ $i }} giờ</option>
                            @endfor
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Xác nhận</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
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
        // Modal tăng ca
        var overtimeModal = document.getElementById('overtimeModal');
        overtimeModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var scheduleId = button.getAttribute('data-schedule-id');
            var input = overtimeModal.querySelector('#modal_schedule_id');
            input.value = scheduleId;
        });

        // Modal thông báo
        @if(session('success') || session('error'))
            var alertModal = new bootstrap.Modal(document.getElementById('alertModal'), { backdrop: 'static', keyboard: false });
            alertModal.show();
        @endif
    </script>
@endpush

<style>
    .table-success {
        background-color: #d4edda !important;
    }

    table tbody tr:hover {
        background-color: #f1f1f1;
    }

    .badge {
        font-size: 0.9rem;
        padding: 0.4em 0.6em;
    }

    .btn-sm {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }

    @media (max-width: 576px) {
        .table-responsive {
            overflow-x: auto;
        }

        td {
            font-size: 0.85rem;
            padding: 0.35rem;
        }

        .btn {
            font-size: 0.7rem;
            padding: 0.2rem 0.4rem;
        }

        select.form-select {
            font-size: 0.75rem;
            padding: 0.2rem 0.3rem;
        }
    }
</style>