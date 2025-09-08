@extends('layouts.app')

@section('title', 'Lịch tuần')

@section('content')
    <div class="container-fluid" style="padding-top: 60px;"> {{-- tăng padding-top bằng chiều cao header --}}

        {{-- Thanh chọn tuần --}}
        <form method="GET" action="{{ route('admin.schedule.view') }}" class="mb-4 d-flex align-items-center gap-2">
            <label for="week_start" class="mb-0">Chọn ngày bắt đầu tuần:</label>
            <input type="date" id="week_start" name="week_start" value="{{ $weekStart->format('Y-m-d') }}"
                class="form-control" style="max-width:200px;">
            <button type="submit" class="btn btn-primary">Xem tuần</button>
            <a href="{{ route('admin.schedule.requests') }}" class="btn btn-success ms-auto">Check nghỉ / Đánh dấu nghỉ</a>
        </form>

        {{-- Thông báo --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($schedules->isEmpty())
            <div class="alert alert-warning text-center">
                Chưa tạo lịch tuần này!
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Nhân viên</th>
                            <th>Vị trí</th>
                            @foreach($week as $day)
                                <th>{{ \Carbon\Carbon::parse($day)->format('D d/m') }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $employees = $schedules->pluck('employee')->unique('id');
                        @endphp

                        @foreach($employees as $emp)
                            <tr>
                                <td>{{ $emp->name }}</td>
                                <td>{{ ucfirst($emp->role) ?? '---' }}</td>

                                @foreach($week as $day)
                                    @php
                                        $sch = $schedules->where('employee_id', $emp->id)->where('date', $day)->first();
                                        $req = \App\Models\ScheduleRequest::where('employee_id', $emp->id)
                                            ->where('date', $day)->first();
                                        $status = $req->status ?? 'present';

                                        // Màu nền
                                        $bgColor = match ($status) {
                                            'full_day_off' => '#f8d7da',
                                            'morning_off' => '#d1e7dd',   // vàng nhạt cho nghỉ sáng
                                            'afternoon_off' => '#d1e7dd', // xanh nhạt cho nghỉ chiều
                                            default => '#d1e7dd',
                                        };

                                        // Xác định ca làm khi nghỉ nửa ngày
                                        $shiftLabel = '';
                                        $shiftTime = '';
                                        if ($status == 'morning_off') {
                                            $shiftLabel = 'Chiều';
                                            $shiftTime = '14:00 - 22:00'; // tùy chỉnh thời gian ca chiều
                                        } elseif ($status == 'afternoon_off') {
                                            $shiftLabel = ' Sáng';
                                            $shiftTime = '08:00 - 16:00'; // thời gian ca sáng
                                        } elseif ($status == 'present' && $sch) {
                                            $shiftLabel = $sch->shift->name;
                                            $shiftTime = \Carbon\Carbon::parse($sch->shift->start_time)->format('H:i') . ' - ' . \Carbon\Carbon::parse($sch->shift->end_time)->format('H:i');
                                        }
                                    @endphp

                                    <td style="background-color: {{ $bgColor }}">
                                        @if($status == 'full_day_off')
                                            Off
                                        @else
                                            {{ $shiftLabel }}
                                            @if($shiftTime)
                                                <br><small>{{ $shiftTime }}</small>
                                            @endif
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        @endif
    </div>
@endsection