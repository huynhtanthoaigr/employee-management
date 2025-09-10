<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Đăng nhập HDKids</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <style>
        body {
            transition: background 2s;
            font-family: 'Poppins', sans-serif;
        }

        .card-login {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            padding: 2rem;
            width: 400px;
        }

        .card-login h3 {
            color: #333;
            font-weight: 700;
        }

        .btn-login {
            background: linear-gradient(90deg, #6a11cb 0%, #2575fc 100%);
            border: none;
            color: #fff;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-login:hover {
            background: linear-gradient(90deg, #2575fc 0%, #6a11cb 100%);
        }

        .form-control:focus {
            border-color: #6a11cb;
            box-shadow: 0 0 0 0.2rem rgba(106, 17, 203, 0.25);
        }

        .icon-header {
            font-size: 3rem;
            color: #6a11cb;
            display: block;
            text-align: center;
            margin-bottom: 1rem;
        }

        .alert-custom {
            background-color: #ffcccc;
            border-color: #ff6666;
            color: #990000;
        }

        a#forgotPassword {
            cursor: pointer;
        }
    </style>
</head>

<body id="body-bg">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card card-login">
            <i class="fas fa-user-circle icon-header"></i>
            <h3 class="mb-4 text-center">HDKids - Đăng nhập</h3>

            {{-- Hiển thị lỗi --}}
            @if ($errors->any())
                <div class="alert alert-custom">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label fw-bold">Tên của bạn</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required
                        autofocus>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-bold">Mật khẩu</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-login w-100 mb-3">Đăng nhập <i
                        class="fas fa-sign-in-alt ms-2"></i></button>

                <div class="text-center">
                    <span id="forgotPassword" class="text-decoration-none text-primary">Quên mật khẩu?</span>
                </div>
            </form>

        </div>
    </div>

    <!-- Modal hài hước -->
    <div class="modal fade" id="funModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-header border-0">
                    <h5 class="modal-title w-100"><i class="fas fa-magic text-primary"></i> Thần thoại HDKids</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body fs-5">
                    ⚡ Hỏi thần thoại đi, nó biết đó 😏
                </div>
                <div class="modal-footer justify-content-center border-0">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">😂 Ok, tôi hiểu rồi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Show modal khi bấm quên mật khẩu
        document.getElementById('forgotPassword').addEventListener('click', function () {
            var funModal = new bootstrap.Modal(document.getElementById('funModal'));
            funModal.show();
        });

        // Background loop 5 màu
        const colors = [
            'linear-gradient(135deg, #FFDEE9, #B5FFFC)', // màu 1
            'linear-gradient(135deg, #A1FFCE, #FAFFD1)', // màu 2
            'linear-gradient(135deg, #FFD3B5, #FFAAA6)', // màu 3
            'linear-gradient(135deg, #434343, #000000)', // màu 4
            'linear-gradient(135deg, #FFB347, #FFCC33)'  // màu 5
        ];
        let index = 0;
        const body = document.getElementById('body-bg');
        function changeBg() {
            body.style.background = colors[index];
            index = (index + 1) % colors.length;
        }
        changeBg();
        setInterval(changeBg, 5000); // 5 giây
    </script>
</body>

</html>