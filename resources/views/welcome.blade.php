<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Game Management - Trang Chủ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="#">Game Management</a>
    <div>
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="btn btn-outline-light me-2" href="{{ route('login') }}">Đăng nhập</a>
        </li>
        <li class="nav-item">
          <a class="btn btn-light" href="{{ route('register') }}">Đăng ký</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<header class="bg-light text-center py-5">
  <div class="container">
    <h1 class="display-4 mb-3">Chào mừng đến với Game Management</h1>
    <p class="lead mb-4">Quản lý và theo dõi các trò chơi một cách dễ dàng và hiệu quả.</p>
    <a href="{{ route('login') }}" class="btn btn-primary btn-lg me-2">Đăng nhập</a>
    <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg">Đăng ký</a>
  </div>
</header>

<footer class="bg-primary text-white text-center py-3 mt-auto">
  <div class="container">
    &copy; {{ date('Y') }} Game Management. All rights reserved.
  </div>
</footer>

</body>
</html>
