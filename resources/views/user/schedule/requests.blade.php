@extends('layouts.app')

@section('title','Đăng ký nghỉ')

@section('content')
 <div class="container-fluid" style="padding-top:80px; padding-bottom:50px;">
    <h3 class="mb-4 text-center">🛌 Đăng ký nghỉ trong tuần</h3>

    {{-- chọn tuần --}}
    <form method="GET" action="{{ route('user.schedule.requests') }}" class="row g-2 mb-3">
        <div class="col-8 col-md-4">
            <label class="form-label">Ngày bắt đầu tuần</label>
            <input type="date" name="week_start" value="{{ $weekStart->format('Y-m-d') }}" class="form-control">
        </div>
        <div class="col-4 col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">🔍 Xem</button>
        </div>
    </form>

    {{-- Thông báo --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Form đăng ký nghỉ --}}
    <form method="POST" action="{{ route('user.schedule.requests.save') }}">
        @csrf
        <input type="hidden" name="week_start" value="{{ $weekStart->format('Y-m-d') }}">

        <div class="row g-3">
            @foreach($week as $day)
                @php
                    $status = $requests[$day]->status ?? 'present';
                    $date = \Carbon\Carbon::parse($day)->locale('vi')->translatedFormat('l, d/m');
                @endphp
                <div class="col-6 col-md-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <h6 class="card-title fw-bold">{{ $date }}</h6>
                            <select name="requests[{{ $day }}]" class="form-select form-select-sm mt-2">
                                <option value="present" {{ $status=='present'?'selected':'' }}>✅ Đi làm</option>
                                <option value="morning_off" {{ $status=='morning_off'?'selected':'' }}>🌅 Nghỉ sáng</option>
                                <option value="afternoon_off" {{ $status=='afternoon_off'?'selected':'' }}>🌆 Nghỉ chiều</option>
                                <option value="full_day_off" {{ $status=='full_day_off'?'selected':'' }}>🛌 Nghỉ cả ngày</option>
                            </select>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-success px-4">💾 Lưu đăng ký nghỉ</button>
        </div>
    </form>
</div>
@endsection
