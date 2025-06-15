<?php
session_start();

// Khởi tạo giỏ hàng nếu chưa có
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Kiểm tra nếu có yêu cầu thêm sản phẩm vào giỏ hàng
if (isset($_GET['add'])) {
    $productId = intval($_GET['add']);

    // Thêm sản phẩm vào giỏ hàng
    if (array_key_exists($productId, $_SESSION['cart'])) {
        $_SESSION['cart'][$productId] += 1; // Tăng số lượng nếu sản phẩm đã có trong giỏ
    } else {
        $_SESSION['cart'][$productId] = 1; // Khởi tạo số lượng sản phẩm
    }
    header("Location: cart.php"); // Chuyển hướng đến trang giỏ hàng
    exit();
}

// Kiểm tra nếu có yêu cầu xóa sản phẩm khỏi giỏ hàng
if (isset($_GET['remove'])) {
    $productId = intval($_GET['remove']);
    
    // Xóa sản phẩm khỏi giỏ hàng
    if (array_key_exists($productId, $_SESSION['cart'])) {
        unset($_SESSION['cart'][$productId]); // Xóa sản phẩm
    }
    header("Location: cart.php"); // Chuyển hướng đến trang giỏ hàng
    exit();
}
include 'db_connect.php';
$sql_categories = "SELECT DISTINCT category FROM products";
$categories = $conn->query($sql_categories);
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

   footer {
    background-color: #333;
    color: #fff;
    text-align: center;
    padding: 10px 0;
    position: relative;
    z-index: 10;
    width: 100%;
    margin-top: 550px; /* Khoảng cách từ trên cùng của trang đến footer */
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
    <!-- Nội dung giỏ hàng -->
    <div class="container mt-4">
        <h2>Giỏ hàng của bạn</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Tổng</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $totalAmount = 0;
                if (!empty($_SESSION['cart'])) {
                    include 'db_connect.php';
                    foreach ($_SESSION['cart'] as $productId => $quantity) {
                        $sql = "SELECT * FROM products WHERE id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $productId);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $product = $result->fetch_assoc();

                        if ($product) {
                            $totalPrice = $product['price'] * $quantity;
                            $totalAmount += $totalPrice;
                            echo "<tr>
                                <td>" . htmlspecialchars($product['name']) . "</td>
                                <td>" . htmlspecialchars($quantity) . "</td>
                                <td>" . number_format($product['price'], 2) . " VND</td>
                                <td>" . number_format($totalPrice, 2) . " VND</td>
                                <td>
                                    <a href='cart.php?remove=" . $productId . "' class='btn btn-danger'>Xóa</a>
                                </td>
                            </tr>";
                        }
                    }
                } else {
                    echo "<tr><td colspan='5'>Giỏ hàng của bạn trống.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <h4>Tổng cộng: <?php echo number_format($totalAmount, 2); ?> VND</h4>
        <div class="d-flex justify-content-between">
            <a href="index.php" class="btn btn-primary">Tiếp tục mua sắm</a>
            <a href="checkout.php" class="btn btn-success">Xác nhận thanh toán</a>
        </div>
    </div>
<footer class="bg-dark text-white text-center py-3">
        <p>&copy;  Nguyễn Văn Nam (06/11/2003)</p>     
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
