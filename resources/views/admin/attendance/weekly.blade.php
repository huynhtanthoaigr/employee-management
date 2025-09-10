@extends('layouts.app')

@section('title', 'Chấm công tuần')

@section('content')
    <div class="container-fluid" style="padding-top: 60px;">

        {{-- Chọn tuần --}}
        <form method="GET" action="{{ route('admin.attendance.weekly') }}" class="mb-4 d-flex align-items-center gap-2">
            <label for="week_start" class="mb-0">Chọn ngày bắt đầu tuần:</label>
            <input type="date" id="week_start" name="week_start" value="{{ $weekStart->format('Y-m-d') }}"
                class="form-control" style="max-width:200px;">
            <button type="submit" class="btn btn-primary">Xem tuần</button>
        </form>

        @if($employees->isEmpty())
            <div class="alert alert-warning text-center">Chưa có nhân viên hoặc lịch tuần này!</div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nhân viên</th>
                            @foreach($week as $day)
                                <th>{{ \Carbon\Carbon::parse($day)->locale('vi')->translatedFormat('l d/m') }}</th>
                            @endforeach
                            <th>Tổng giờ tăng ca</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($employees as $emp)
                            @php $totalOvertime = 0; @endphp
                            <tr>
                                <td>{{ $emp->name }}</td>

                                @foreach($week as $day)
                                    @php
                                        $attendance = $attendances->where('employee_id', $emp->id)
                                            ->where('date', $day)
                                            ->first();
                                        $status = '-';
                                        if ($attendance) {
                                            $status = ($attendance->check_in && $attendance->check_out) ? '✔️' : '⏳';
                                            if ($attendance->overtime_hours) {
                                                $totalOvertime += $attendance->overtime_hours;
                                            }
                                        }
                                    @endphp
                                    <td>
                                        {{ $status }}
                                        @if($attendance && $attendance->overtime_hours)
                                            <br><small>{{ $attendance->overtime_hours }}h</small>
                                        @endif
                                    </td>
                                @endforeach

                                <td>{{ $totalOvertime }}h</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection

<style>
    table tbody tr:hover {
        background-color: #f1f1f1;
    }

    .table-light th {
        font-weight: 600;
    }

    @media (max-width: 576px) {
        .table-responsive {
            overflow-x: auto;
        }
    }
</style>