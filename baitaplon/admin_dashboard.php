<?php
session_start();
include 'db_connect.php';

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'nam') {
    header("Location: login.php"); // Nếu chưa đăng nhập hoặc không phải admin, chuyển đến trang đăng nhập
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f1f5f9;
            font-family: 'Arial', sans-serif;
        }
        h1 {
            color: #343a40;
            margin-bottom: 30px;
            text-align: center;
        }
        .container {
            max-width: 900px;
        }
        .btn {
            width: 100%;
            margin-bottom: 15px;
            padding: 15px;
            font-size: 16px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-secondary {
            background-color: #6c757d;
            border: none;
        }
        .btn-warning {
            background-color: #ffc107;
            border: none;
        }
        .btn-outline-danger {
            border-color: #dc3545;
            color: #dc3545;
        }
        .btn-outline-danger:hover {
            background-color: #dc3545;
            color: white;
        }
        .card {
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        .card-body {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1>Trang Chủ Admin</h1>

        <div class="card">
            <div class="card-body">
                <a href="user_list.php" class="btn btn-success">Danh sách Tài Khoản</a>
                <a href="product_list.php" class="btn btn-primary">Danh sách sản phẩm</a>
                <a href="order_history.php" class="btn btn-secondary">Lịch sử đơn hàng</a>
                <a href="new_reviews.php" class="btn btn-warning">Xem đánh giá mới</a>
            </div>
        </div>

        <a href="logout.php" class="btn btn-outline-danger">Đăng xuất</a>
    </div>
</body>
</html>
