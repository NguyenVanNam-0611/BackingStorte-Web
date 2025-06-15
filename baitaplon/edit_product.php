<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM products WHERE id='$id'";
    $result = $conn->query($sql);
    $product = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $category = $_POST['category'];
        $image = $_FILES['image']['name'];

        // Kiểm tra xem người dùng có chọn ảnh mới không
        if ($_FILES['image']['error'] == 0) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (in_array($_FILES['image']['type'], $allowedTypes)) {
                // Di chuyển ảnh mới vào thư mục uploads
                $targetDir = "uploads/";
                $targetFile = $targetDir . basename($image);
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    // Cập nhật sản phẩm với ảnh mới
                    $sql = "UPDATE products SET name='$name', description='$description', price='$price', category='$category', image='$image' WHERE id='$id'";
                    if ($conn->query($sql) === TRUE) {
                        header("Location: product_list.php");
                        exit();
                    } else {
                        echo "Lỗi: " . $conn->error;
                    }
                } else {
                    echo "Không thể tải ảnh lên.";
                }
            } else {
                echo "Vui lòng chỉ tải lên tệp ảnh (JPG, PNG, GIF).";
            }
        } else {
            // Nếu không chọn ảnh mới, chỉ cập nhật thông tin còn lại
            $sql = "UPDATE products SET name='$name', description='$description', price='$price', category='$category' WHERE id='$id'";
            if ($conn->query($sql) === TRUE) {
                header("Location: product_list.php");
                exit();
            } else {
                echo "Lỗi: " . $conn->error;
            }
        }
    }
} else {
    header("Location: product_list.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa sản phẩm</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f8f8;
        }
        h1 {
            color: #333;
        }
        form {
            margin-bottom: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fff;
        }
        input[type="text"], input[type="number"], input[type="file"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <h1>Sửa sản phẩm</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
        <input type="text" name="description" value="<?php echo htmlspecialchars($product['description']); ?>" required>
        <input type="number" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
        <input type="text" name="category" value="<?php echo htmlspecialchars($product['category']); ?>" required>
        <div>
            <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image" width="100px">
            <input type="file" name="image" accept="image/*">
            <small>Chọn ảnh mới nếu bạn muốn thay đổi ảnh sản phẩm.</small>
        </div>
        <button type="submit">Cập nhật sản phẩm</button>
    </form>
    <a href="product_list.php">Trở về danh sách sản phẩm</a>
</body>
</html>
