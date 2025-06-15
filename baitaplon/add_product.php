<?php

session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    // Xử lý upload ảnh
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Kiểm tra file có phải ảnh không
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File không phải là ảnh.";
        $uploadOk = 0;
    }

    // Kiểm tra kích thước file (giới hạn 500KB)
    if ($_FILES["image"]["size"] > 500000) {
        echo "File quá lớn.";
        $uploadOk = 0;
    }

    // Chỉ cho phép một số định dạng file
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        echo "Chỉ chấp nhận các định dạng JPG, JPEG, PNG & GIF.";
        $uploadOk = 0;
    }

    // Kiểm tra nếu có lỗi trong quá trình upload
    if ($uploadOk == 0) {
        echo "File không được upload.";
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Lưu vào cơ sở dữ liệu
            $stmt = $conn->prepare("INSERT INTO products (name, description, price, image, category) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdss", $name, $description, $price, $target_file, $category);

            if ($stmt->execute()) {
                echo "Thêm sản phẩm thành công!";
            } else {
                echo "Có lỗi xảy ra: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Có lỗi xảy ra khi upload file ảnh.";
        }
    }
}

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
        .content-container {
            margin-left: 240px;
            padding: 20px;
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
         footer {
    background-color: #333;
    color: #fff;
    text-align: center;
    padding: 10px 0;
    position: relative;
    z-index: 10;
    width: 100%;
    margin-top: 500px; 
}
form input, form button {
    width: 100%;
    margin-bottom: 15px;
}

form input[type="text"], form input[type="number"] {
    padding: 10px;
    border-radius: 4px;
    border: 1px solid #ddd;
    font-size: 16px;
}

form input[type="file"] {
    padding: 10px;
    font-size: 16px;
}

form button {
    background-color: #ff69b4;
    color: white;
    border: none;
    padding: 10px;
    font-size: 18px;
    border-radius: 4px;
    cursor: pointer;
}

form button:hover {
    background-color: #ff3385;
}

form a {
    display: inline-block;
    margin-top: 20px;
    color: #007bff;
    font-size: 16px;
    text-decoration: none;
}

form a:hover {
    text-decoration: underline;
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
        <h1>Thêm sản phẩm</h1>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Tên sản phẩm" required>
            <input type="text" name="description" placeholder="Mô tả sản phẩm" required>
            <input type="number" name="price" placeholder="Giá (VND)" required>
             <input type="text" name="category" placeholder="Loại sản phẩm" required>
            <input type="file" name="image" accept="image/*" required>        
            <button type="submit">Thêm sản phẩm</button>
        </form>
        <a href="product_list.php">Trở về danh sách sản phẩm</a>
    </div>
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy;  Nguyễn Văn Nam (06/11/2003)</p>
      
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
