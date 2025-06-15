<?php
session_start();
include 'db_connect.php';

// Lấy danh sách sản phẩm
$sql = "SELECT * FROM products";
$products = $conn->query($sql);

// Lấy danh mục từ cơ sở dữ liệu
$sql_categories = "SELECT DISTINCT category FROM products";
$categories = $conn->query($sql_categories);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ - Cửa hàng dụng cụ làm bánh</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
     <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .product-card {
            margin-bottom: 20px;
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s;
        }
        .product-card:hover {
            transform: scale(1.05);
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
            margin-right: 10px;
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
            font-size: 18px; 
        }
        .category-sidebar .nav-link:hover {
            background-color: #e2e2e2;
            color: #0d6efd;
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

        .card-img-top {
    height: 200px;
    object-fit: contain; 
    width: 100%; 
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
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
            <div class="phone-number">
                <span>Số điện thoại: <strong>0865693162</strong></span>
            </div>
            <div class="social-icons">
                <a href="https://www.facebook.com/profile.php?id=100011354759354" target="_blank" title="Facebook">
                    <i class="fab fa-facebook"></i>
                </a>
                <a href="https://www.tiktok.com/@namthan.0611" target="_blank" title="TikTok">
                <i class="fab fa-tiktok"></i>
                  </a>
                <a href="mailto:nam06112k3@gmail.com" title="Email">
                <i class="fas fa-envelope"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Navbar chính -->
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

    <!-- Sidebar danh mục -->
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

    <div class="content-container">
        <div class="container mt-4">
            <h1 class="welcome-text">Chào mừng đến với Cửa hàng dụng cụ làm bánh</h1>
            <h2 class="welcome-text">Đây là các sản phẩm của cửa hàng</h2>
            <div class="product-list">
                <div class="row">
                    <?php if ($products->num_rows > 0): ?>
                        <?php while ($product = $products->fetch_assoc()): ?>
                            <div class="col-md-4">
                                <div class="card product-card">
                                    <img src="<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                        <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p>
                                        <p class="card-text"><strong>Giá: <?php echo number_format($product['price'], 2); ?> VND</strong></p>
                                        <a href="product_detail.php?id=<?php echo $product['id']; ?>" class="btn btn-primary">Xem chi tiết</a>
                                        <a href="cart.php?add=<?php echo $product['id']; ?>" class="btn btn-success">Thêm vào giỏ</a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>Không có sản phẩm nào trong cửa hàng.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy;  Nguyễn Văn Nam (06/11/2003)</p>       
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
