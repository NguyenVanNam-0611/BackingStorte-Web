<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Kiểm tra tính đúng đắn của dữ liệu
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email không hợp lệ.";
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        $error = "Số điện thoại phải bao gồm 10 chữ số.";
    } elseif ($password !== $confirm_password) {
        $error = "Mật khẩu nhập lại không khớp.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Kiểm tra xem tên người dùng hoặc email đã tồn tại chưa
        $sql_check = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("ss", $username, $email);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            $error = "Tên người dùng hoặc email đã tồn tại. Vui lòng chọn tên hoặc email khác.";
        } else {
            // Thêm người dùng vào cơ sở dữ liệu
            $sql_insert = "INSERT INTO users (full_name, username, email, phone, address, password) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("ssssss", $full_name, $username, $email, $phone, $address, $hashed_password);

            if ($stmt_insert->execute()) {
                header("Location: login.php");
                exit();
            } else {
                echo "Lỗi: " . $stmt_insert->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .form-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        button {
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
        }
        button:hover {
            background-color: #218838;
        }
        a {
            text-decoration: none;
            color: #007bff;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Đăng ký</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="mb-3">
                <input type="text" name="full_name" class="form-control" placeholder="Tên người dùng" required>
            </div>
            <div class="mb-3">
                <input type="text" name="username" class="form-control" placeholder="Tên tài khoản" required>
            </div>
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <input type="text" name="phone" class="form-control" placeholder="Số điện thoại" required>
            </div>
            <div class="mb-3">
                <input type="text" name="address" class="form-control" placeholder="Địa chỉ" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Mật khẩu" required>
            </div>
            <div class="mb-3">
                <input type="password" name="confirm_password" class="form-control" placeholder="Nhập lại mật khẩu" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Đăng ký</button>
        </form>
        <div class="text-center mt-3">
            <a href="login.php">Đã có tài khoản? Đăng nhập</a>
        </div>
    </div>
</body>
</html>
