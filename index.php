<?php
session_start();
require_once "db.php"; // Kết nối cơ sở dữ liệu

// Kiểm tra nếu form đã được submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {  // Đăng nhập
        $email = $_POST['inputEmail'];
        $password = $_POST['inputPassword'];

        // Query kiểm tra email và mật khẩu
        $query = "SELECT * FROM login WHERE email = ? AND password = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Lưu thông tin vào session nếu đăng nhập thành công
            $_SESSION['email'] = $email;
            header("Location: dashboard.php"); // Chuyển đến trang dashboard
            exit();
        } else {
            $error = "Email hoặc mật khẩu không đúng!";
        }
    } elseif (isset($_POST['register'])) {  // Đăng ký
        $username = $_POST['inputUsername'];
        $email = $_POST['inputEmail'];
        $password = $_POST['inputPassword'];
        $confirmPassword = $_POST['inputConfirmPassword'];

        if ($password === $confirmPassword) {
            // Query đăng ký người dùng
            $query = "INSERT INTO login (username, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sss", $username, $email, $password);
            $stmt->execute();

            // Thông báo thành công hoặc chuyển hướng
            echo "<script>alert('Đăng ký thành công!'); window.location.href='index.php';</script>";
        } else {
            $error = "Mật khẩu xác nhận không khớp!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <style>
    body,
    html {
        height: 100%;
        margin: 0;
    }

    .vh-100 {
        height: 100vh;
    }

    .container {
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .card {
        display: flex;
        height: 100%;
        border-radius: 1rem;
        width: 100%;
    }

    .col-md-6.col-lg-5 {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 0;
    }

    .col-md-6.col-lg-7 {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .img-fluid {
        border-radius: 1rem 0 0 1rem;
        width: 90%;
    }

    .card-body {
        width: 100%;
    }
    </style>
</head>

<body>
    <div>
        <section class="vh-100" style="background-color: #9A616D;">
            <div class="container">
                <div class="row w-100 d-flex justify-content-center align-items-center">
                    <div class="col-xl-10">
                        <div class="card">
                            <div class="row g-0">
                                <!-- Hình ảnh -->
                                <div class="col-md-6 col-lg-5 d-none d-md-block text-center">
                                    <img src="https://img.freepik.com/free-vector/learning-concept-illustration_114360-6186.jpg"
                                        alt="login form" class="img-fluid" />
                                </div>

                                <!-- Form đăng nhập -->
                                <div class="col-md-6 col-lg-7 d-flex align-items-center">
                                    <div class="card-body p-4 p-lg-5 text-black">
                                        <!-- Tab chọn giữa đăng nhập và đăng ký -->
                                        <div class="d-flex justify-content-between mb-3">
                                            <button id="loginBtn" class="btn btn-link"
                                                onclick="toggleForm('login')">Đăng Nhập</button>
                                            <button id="registerBtn" class="btn btn-link"
                                                onclick="toggleForm('register')">Đăng Ký</button>
                                        </div>

                                        <!-- Form đăng nhập -->
                                        <form id="loginForm" class="form-signin" action="index.php" method="POST">
                                            <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Đăng nhập vào
                                                hệ thống</h5>

                                            <?php if (isset($error)) echo "<p class='alert alert-danger'>$error</p>"; ?>

                                            <div class="form-outline mb-4">
                                                <input type="text" id="inputEmail" name="inputEmail"
                                                    class="form-control form-control-lg" />
                                                <label class="form-label" for="inputEmail">Email</label>
                                            </div>

                                            <div class="form-outline mb-4">
                                                <input type="password" name="inputPassword" id="inputPassword"
                                                    class="form-control form-control-lg" />
                                                <label class="form-label" for="inputPassword">Mật khẩu</label>
                                            </div>

                                            <div class="pt-1 mb-4">
                                                <button name="login" class="btn btn-dark btn-lg btn-block"
                                                    type="submit">Đăng nhập</button>
                                            </div>

                                            <p class="mb-5 pb-lg-2" style="color: #393f81;">Bạn chưa có tài khoản? <a
                                                    href="#!" id="registerLink" style="color: #393f81;">Đăng ký tại
                                                    đây</a></p>
                                        </form>

                                        <!-- Form đăng ký -->
                                        <form id="registerForm" class="form-signin" action="index.php" method="POST"
                                            style="display: none;">
                                            <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Đăng ký tài
                                                khoản</h5>

                                            <?php if (isset($error)) echo "<p class='alert alert-danger'>$error</p>"; ?>

                                            <div class="form-outline mb-4">
                                                <input type="text" id="inputUsername" name="inputUsername"
                                                    class="form-control form-control-lg" required />
                                                <label class="form-label" for="inputUsername">Tên người dùng</label>
                                            </div>

                                            <div class="form-outline mb-4">
                                                <input type="email" id="inputEmail" name="inputEmail"
                                                    class="form-control form-control-lg" required />
                                                <label class="form-label" for="inputEmail">Email</label>
                                            </div>

                                            <div class="form-outline mb-4">
                                                <input type="password" name="inputPassword" id="inputPassword"
                                                    class="form-control form-control-lg" required />
                                                <label class="form-label" for="inputPassword">Mật khẩu</label>
                                            </div>

                                            <div class="form-outline mb-4">
                                                <input type="password" name="inputConfirmPassword"
                                                    id="inputConfirmPassword" class="form-control form-control-lg"
                                                    required />
                                                <label class="form-label" for="inputConfirmPassword">Xác nhận mật
                                                    khẩu</label>
                                            </div>

                                            <div class="pt-1 mb-4">
                                                <button name="register" class="btn btn-dark btn-lg btn-block"
                                                    type="submit">Đăng ký</button>
                                            </div>

                                            <p class="mb-5 pb-lg-2" style="color: #393f81;">Đã có tài khoản? <a
                                                    href="#!" id="loginLink" style="color: #393f81;">Đăng nhập tại
                                                    đây</a></p>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
    function toggleForm(formType) {
        if (formType === 'login') {
            document.getElementById('loginForm').style.display = 'block';
            document.getElementById('registerForm').style.display = 'none';
            document.getElementById('loginBtn').classList.add('active');
            document.getElementById('registerBtn').classList.remove('active');
        } else {
            document.getElementById('loginForm').style.display = 'none';
            document.getElementById('registerForm').style.display = 'block';
            document.getElementById('loginBtn').classList.remove('active');
            document.getElementById('registerBtn').classList.add('active');
        }
    }
    </script>
</body>

</html>