<?php
session_start();
include 'db_connect.php';

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Nếu chưa đăng nhập, chuyển đến trang đăng nhập
    exit;
}

// Lấy danh sách đơn hàng cùng tên sản phẩm và số lượng
$userId = $_SESSION['user_id'];
$sql = "
    SELECT orders.id AS order_id, orders.total_amount, orders.created_at, 
           products.name AS product_name, order_items.quantity 
    FROM orders 
    JOIN order_items ON orders.id = order_items.order_id 
    JOIN products ON order_items.product_id = products.id 
    WHERE orders.user_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

// Kiểm tra kết quả
$orderItems = [];
while ($row = $result->fetch_assoc()) {
    $orderItems[$row['order_id']]['total_amount'] = $row['total_amount'];
    $orderItems[$row['order_id']]['created_at'] = $row['created_at'];
    $orderItems[$row['order_id']]['products'][] = [
        'name' => $row['product_name'],
        'quantity' => $row['quantity']
    ];
}
$sql_categories = "SELECT DISTINCT category FROM products";
$categories = $conn->query($sql_categories);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
       body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .top-bar {
            background-color: #ff69b4;
            color: white;
            padding: 10px 0;
            font-size: 20px;
            font-weight: bold;
        }

        .top-bar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-container img {
            width: 150px;
            height: auto;
        }

        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            font-size: 18px;
        }

        .navbar-brand, .nav-link {
            font-weight: bold;
            color: #333333;
        }

        .navbar-nav .nav-link {
            margin-left: 15px;
        }

        .content-container {
            margin-left: 240px;
            padding: 20px;
        }

        .welcome-text {
            color: #ff69b4; 
            font-weight: bold; 
            text-align: center; 
            margin-bottom: 20px; 
            font-size: 24px; 
            text-transform: uppercase; 
            letter-spacing: 1px; 
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .social-icons i {
            font-size: 20px;
            color: white;
            margin-right: 15px;
        }

        .social-icons i:hover {
            color: #0d6efd;
        }

        .phone-number, .social-icons {
            display: flex;
            align-items: center;
        }

        .btn-primary, .btn-success {
            width: 100%;
            margin-top: 10px;
            font-size: 16px;
        }
        .category-sidebar {
            width: 220px;
            position: absolute;
            top:200px;
            left: 0;
            background-color: #f1f1f1;
            border-right: 1px solid #ddd;
            padding: 20px;
            font-size: 18px;
        }
        .category-sidebar .category-title {
            font-size: 24px; /* Tăng kích thước chữ "Danh mục SP" */
            font-weight: bold;
            color: #ff69b4; /* Màu hồng cho tiêu đề */
            margin-bottom: 20px;
        }
        .category-sidebar .nav-link {
            display: block;
            padding: 10px 15px;
            color: #333;
            text-decoration: none;
            font-size: 18px; /* Kích thước chữ cho các mục nhỏ hơn */
        }
        .category-sidebar .nav-link:hover {
            background-color: #e2e2e2;
            color: #0d6efd;
        }
        footer {
    background-color: #333;
    color: #fff;
    text-align: center;
    padding: 10px 0;
    position: relative;
    z-index: 10;
    width: 100%;
    margin-top: 800px; 
}
    </style>
</head>
<body>
    <!-- Thanh trên -->
    <div class="top-bar">
        <div class="container">
            <div class="logo-container">
                <img src="uploads/nam.jpg" alt="Logo cửa hàng">
                <span>Địa chỉ cửa hàng: 729 Trương Định, Giáp Bát, Hoàng Mai, Hà Nội</span>
            </div>
            <div class="phone-number">
                <span>Số điện thoại: <strong>0865693162</strong></span>
            </div>
            <div class="social-icons">
                <a href="https://www.facebook.com/nguyen.nam" target="_blank" title="Facebook">
                    <i class="fab fa-facebook"></i>
                </a>
                <a href="https://www.tiktok.com/@namthaan.0611" target="_blank" title="TikTok">
                    <i class="fab fa-tiktok"></i>
                </a>
                <a href="mailto:your.email@example.com" title="Email">
                    <i class="fas fa-envelope"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Trang Chủ</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Danh Sách Sản Phẩm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">Giỏ Hàng</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="order_historys.php">Lịch Sử Mua Hàng</a> 
                    </li>
                </ul> 
                 <form action="search.php" method="GET" class="d-flex ms-2">
                    <input type="text" name="keyword" class="form-control" placeholder="Tìm kiếm sản phẩm..." required>
                    <button type="submit" class="btn btn-outline-success ms-2">Tìm kiếm</button>
                </form>
                <div class="ms-3">
                    <?php if (isset($_SESSION['username'])): ?>
                        <span class="navbar-text">Xin chào, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                        <a href="logout.php" class="btn btn-outline-danger ms-2">Đăng xuất</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline-primary">Đăng nhập</a>
                        <a href="register.php" class="btn btn-outline-success ms-2">Đăng ký</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
<div class="category-sidebar">
        <span class="category-title">Danh Mục SP</span>
        <ul class="navbar-nav">
            <?php if ($categories->num_rows > 0): ?>
                <?php while ($category = $categories->fetch_assoc()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="search.php?keyword=<?php echo urlencode($category['category']); ?>">
                            <?php echo htmlspecialchars($category['category']); ?>
                        </a>
                    </li>
                <?php endwhile; ?>
            <?php else: ?>
                <li class="nav-item">
                    <span class="nav-link">Chưa có danh mục nào.</span>
                </li>
            <?php endif; ?>
        </ul>
    </div>
    <div class="container mt-4">
        <h1 class="text-center">Lịch sử đơn hàng của bạn </h1>
        <a href="cart.php" class="btn btn-info mb-3">Trở về Giỏ Hàng </a>
        <a href="index.php" class="btn btn-info mb-3">Trở về Trang Chủ </a>
        <?php if (!empty($orderItems)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID Đơn hàng</th>
                        <th>Sản phẩm</th>
                        <th>Tổng tiền</th>
                        <th>Ngày tạo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orderItems as $orderId => $order): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($orderId); ?></td>
                            <td>
                                <?php foreach ($order['products'] as $product): ?>
                                    <p><?php echo htmlspecialchars($product['name']) . " - Số lượng: " . htmlspecialchars($product['quantity']); ?></p>
                                <?php endforeach; ?>
                            </td>
                            <td><?php echo number_format($order['total_amount'], 2); ?> VND</td>
                            <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Hiện tại bạn chưa có đơn hàng nào.</p>
        <?php endif; ?>
    </div>
     <footer class="bg-dark text-white text-center py-3">
        <p>&copy;  Nguyễn Văn Nam (06/11/2003)</p>       
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
