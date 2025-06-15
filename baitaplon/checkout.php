<?php
session_start();
include 'db_connect.php';
$sql_categories = "SELECT DISTINCT category FROM products";
$categories = $conn->query($sql_categories);
// Kiểm tra giỏ hàng
if (empty($_SESSION['cart'])) {
    header("Location: cart.php"); // Nếu giỏ hàng trống, chuyển đến giỏ hàng
    exit;
}

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Nếu chưa đăng nhập, chuyển đến trang đăng nhập
    exit;
}

// Khởi tạo biến tổng số tiền
$totalAmount = 0;

// Tính tổng giá trị đơn hàng
foreach ($_SESSION['cart'] as $productId => $quantity) {
    // Lấy giá sản phẩm
    $productSql = "SELECT price FROM products WHERE id = ?";
    $productStmt = $conn->prepare($productSql);
    $productStmt->bind_param("i", $productId);
    $productStmt->execute();
    $productResult = $productStmt->get_result();
    if ($productResult->num_rows > 0) {
        $product = $productResult->fetch_assoc();
        $totalAmount += $product['price'] * $quantity;
    }
    $productStmt->close();
}

// Xử lý thông tin thanh toán khi người dùng gửi form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id']; // Lấy user_id từ session

    // Lưu thông tin đơn hàng vào cơ sở dữ liệu
    $sql = "INSERT INTO orders (user_id, total_amount) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("id", $userId, $totalAmount); 
    if ($stmt->execute()) {
        // Lấy ID của đơn hàng vừa tạo
        $orderId = $stmt->insert_id;

        // Lưu chi tiết đơn hàng vào bảng order_items
        foreach ($_SESSION['cart'] as $productId => $quantity) {
            $itemSql = "INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)";
            $itemStmt = $conn->prepare($itemSql);
            $itemStmt->bind_param("iii", $orderId, $productId, $quantity);
            $itemStmt->execute();
            $itemStmt->close();
        }

        // Giỏ hàng đã thanh toán, xóa giỏ hàng
        unset($_SESSION['cart']); // Xóa giỏ hàng
        echo "<script>alert('Thanh toán thành công!'); window.location.href='index.php';</script>";
    } else {
        echo "Lỗi: " . $stmt->error;
    }
    $stmt->close();
}
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
    display: flex;
    flex-direction: column;
    min-height: 100vh;
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
    font-size: 24px;
    font-weight: bold;
    color: #ff69b4;
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
        .container.mt-4 {
    margin-left: 280px;
    flex: 1;
    padding-bottom: 80px;
}

input.form-control {
    width: 60%; /* Hoặc giá trị thích hợp để làm cho các textbox ngắn lại */
    margin: 0 auto; /* Căn giữa */
}
   footer {
    background-color: #333;
    color: #fff;
    text-align: center;
    padding: 10px 0;
    position: relative;
    z-index: 10;
    width: 100%;
    margin-top: 700px; /* Khoảng cách từ trên cùng của trang đến footer */
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
                <a href="https://www.tiktok.com/@namthan.0611" target="_blank" title="TikTok">
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
    <div class="mb-3">
            <label for="name" class="form-label">Họ và tên</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Nhập họ và tên" required>
        </div>
            
        <div class="mb-3">
            <label for="address" class="form-label">Địa chỉ</label>
            <input type="text" name="address" id="address" class="form-control" placeholder="Nhập địa chỉ" required>
        </div>
        
        
        <div class="mb-3">
            <label for="phone" class="form-label">Số điện thoại</label>
            <input type="text" name="phone" id="phone" class="form-control" placeholder="Nhập số điện thoại" required pattern="[0-9]{10,11}">
        </div>

    <div class="container mt-4">
        <h1 class="text-center">Thanh toán</h1>
        <form action="checkout.php" method="POST">
            <p class="text-center">Tổng số tiền: <strong><?php echo number_format($totalAmount, 2); ?> VND</strong></p>
            <button type="submit" class="btn btn-success">Xác nhận thanh toán</button>
        </form>
    </div>
<footer class="bg-dark text-white text-center py-3">
        <p>&copy;  Nguyễn Văn Nam (06/11/2003)</p>       
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
