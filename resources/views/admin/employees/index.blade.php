@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding-top: 60px;"> {{-- tăng padding-top bằng chiều cao header --}}
    
    {{-- Nút thêm nhân viên với khoảng cách trên-dưới 10px --}}
    <div class="row mb-3" style="margin-top:10px; margin-bottom:10px;">
        <div class="col-12 d-flex flex-wrap justify-content-between align-items-center">
            <h2 class="fw-bold mb-2 mb-md-0">Quản lý nhân viên</h2>
            <a href="{{ route('admin.employees.create') }}" class="btn btn-success shadow-sm">
                <i class="fas fa-plus"></i> Thêm nhân viên
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle" id="employeesTable" style="min-width: 900px;">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Ảnh</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Giới tính</th>
                        <th>Vị trí</th>
                        <th class="text-center" style="width: 150px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $emp)
                    <tr>
                        <td>{{ $emp->id }}</td>
                        <td>
                            @if($emp->avatar)
                                <img src="{{ asset('storage/' . $emp->avatar) }}" width="50" height="50" class="rounded-circle border">
                            @else
                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                        </td>
                        <td>{{ $emp->name }}</td>
                        <td>{{ $emp->email }}</td>
                        <td>{{ $emp->phone }}</td>
                        <td>
                            @switch($emp->gender)
                                @case('male')
                                    Nam
                                    @break
                                @case('female')
                                    Nữ
                                    @break
                                @default
                                    Khác
                            @endswitch
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ $emp->role }}</span>
                        </td>
                        <td class="text-center">
                            <div class="d-none d-md-flex justify-content-center gap-1">
                                <a href="{{ route('admin.employees.edit', $emp) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <form action="{{ route('admin.employees.destroy', $emp) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Xóa nhân viên này?')">
                                        <i class="fas fa-trash"></i> Xóa
                                    </button>
                                </form>
                            </div>

                            {{-- Dropdown cho màn hình nhỏ --}}
                            <div class="d-md-none dropdown">
                                <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="actionDropdown{{ $emp->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="actionDropdown{{ $emp->id }}">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.employees.edit', $emp) }}">
                                            <i class="fas fa-edit"></i> Sửa
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('admin.employees.destroy', $emp) }}" method="POST" onsubmit="return confirm('Xóa nhân viên này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="dropdown-item text-danger" type="submit">
                                                <i class="fas fa-trash"></i> Xóa
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- DataTables --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#employeesTable').DataTable({
            responsive: true,
            autoWidth: false,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Tìm kiếm nhân viên..."
            }
        });
    });
</script>
@endsection
