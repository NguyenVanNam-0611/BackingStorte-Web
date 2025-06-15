-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 15, 2025 at 03:42 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `baking_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `created_at`) VALUES
(8, 6, 10000000.00, '2024-11-30 00:56:38'),
(9, 6, 5000000.00, '2024-11-30 01:50:17'),
(10, 6, 2500000.00, '2024-11-30 01:51:19'),
(11, 6, 60000.00, '2025-06-15 13:37:17');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `created_at`) VALUES
(8, 8, 2, 1, 0.00, '2024-11-30 00:56:38'),
(9, 9, 3, 2, 0.00, '2024-11-30 01:50:17'),
(10, 10, 3, 1, 0.00, '2024-11-30 01:51:19'),
(11, 11, 13, 1, 0.00, '2025-06-15 13:37:17');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `category` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `created_at`, `category`) VALUES
(2, 'Lò nướng công nghiệp', 'Lò nướng bánh công nghiệp là thiết bị dung tích lớn, có công suất cao và thường có nhiều tầng để nướng số lượng lớn bánh cùng lúc. Lò trang bị quạt đối lưu giúp nhiệt phân bố đều, cho bánh chín đều và đẹp. Bảng điều khiển điện tử hiện đại giúp cài đặt nhiệt độ, thời gian và độ ẩm chính xác, đảm bảo chất lượng bánh nướng ổn định cho các tiệm và cơ sở sản xuất lớn.', 10000000.00, 'uploads/lò công nghiệp.jpg', '2024-10-27 14:25:00', 'Lò nướng'),
(3, 'Lò nướng gia đình', 'Lò nướng gia đình là thiết bị nhỏ gọn, phù hợp với nhu cầu nướng bánh, quay thịt, và nấu ăn tại nhà. Với dung tích từ 20 đến 60 lít, lò nướng gia đình có thể đặt vừa trên kệ bếp mà không chiếm nhiều diện tích. Lò thường có các chức năng điều chỉnh nhiệt độ, hẹn giờ, quạt đối lưu để giúp thực phẩm chín đều, và nhiều chế độ nướng khác nhau như nướng trên, nướng dưới, và nướng cả hai. Điều khiển lò có thể bằng núm xoay hoặc bảng điện tử, rất dễ sử dụng và phù hợp với đa số gia đình.', 2500000.00, 'uploads/lò gia đình.jpg', '2024-10-27 14:26:22', 'Lò nướng'),
(4, 'Bàn xoay ', 'Bàn xoay bánh kem là một dụng cụ hỗ trợ trong việc trang trí bánh, đặc biệt là bánh kem. Bàn có thiết kế tròn, gắn trên một trục xoay mượt mà, giúp thợ làm bánh dễ dàng xoay bánh để phủ kem, vẽ họa tiết, hoặc trang trí xung quanh mà không cần di chuyển nhiều. Bàn thường làm từ nhựa, inox hoặc kim loại, có bề mặt phẳng để đặt bánh ổn định và đế chống trượt để đảm bảo an toàn khi xoay. Dụng cụ này rất hữu ích để tạo ra lớp kem đều và trang trí tinh tế, chuyên nghiệp.', 150000.00, 'uploads/Bàn xoay.jpg', '2024-10-27 14:27:19', 'Bàn xoay'),
(5, 'Cân điện tử', 'Cân điện tử là thiết bị đo lường chính xác, hiển thị trọng lượng qua màn hình số. Phù hợp cân nguyên liệu trong nhà bếp với độ chính xác đến từng gram và có chức năng trừ bì để loại trừ trọng lượng bao bì.', 500000.00, 'uploads/Cân điện tử.jpg', '2024-10-27 14:28:20', 'Cân'),
(6, 'Cốc đong', 'Cốc đong thủy tinh là dụng cụ đo lường dung tích, làm từ thủy tinh trong suốt với các vạch chia rõ ràng. Nó giúp đo lường chính xác chất lỏng hoặc bột, không phản ứng với thực phẩm và dễ dàng vệ sinh.', 40000.00, 'uploads/Cốc đong.jpg', '2024-10-27 14:30:16', 'Cốc'),
(8, 'Dao Chà Lang', 'Dao chà láng dài 30 cm, được làm từ inox chất lượng cao, mang lại độ bền và sắc bén tối ưu. Lưỡi dao rộng 5 cm, thiết kế phẳng giúp dễ dàng làm mịn và trang trí bề mặt bánh kem. Tay cầm bằng nhựa chống trượt, tạo cảm giác thoải mái và an toàn khi sử dụng. Sản phẩm nhẹ chỉ 200g, dễ dàng vệ sinh và bảo quản. Dao chà láng là dụng cụ không thể thiếu trong việc tạo ra những chiếc bánh kem đẹp mắt và hoàn hảo cho mọi dịp.', 70000.00, 'uploads/Dao chà láng.jpg', '2024-10-27 14:33:21', 'Dao'),
(9, 'Giấy Bạc', 'Giấy bạc có kích thước 30 cm x 5 m, làm từ nhôm cao cấp, an toàn cho sức khỏe. Với khả năng chịu nhiệt tốt, giấy bạc chống thấm nước và dầu mỡ, giúp bảo quản thực phẩm hiệu quả. Sản phẩm lý tưởng để bọc thực phẩm, nướng bánh, nướng thịt, và bảo quản thức ăn trong tủ lạnh. Ngoài ra, giấy bạc còn có thể được sử dụng để trang trí bánh hoặc làm khuôn trong quá trình nướng. Dễ dàng cắt theo kích thước mong muốn, không dính, dễ vệ sinh và bảo quản. Giấy bạc 30cm x 5m là công cụ tiện lợi cho nhà bếp, giúp tiết kiệm thời gian và công sức trong việc chuẩn bị và bảo quản thực phẩm.', 50000.00, 'uploads/Giáy bạc.jpg', '2024-10-27 14:34:30', 'Giấy'),
(10, 'Giấy Nến', 'Giấy nến có kích thước 30 cm x 5 m, được làm từ chất liệu giấy chống dính, an toàn cho sức khỏe. Với khả năng chịu nhiệt lên đến 220°C, giấy nến lý tưởng cho việc nướng bánh, hấp, và chiên mà không cần sử dụng dầu mỡ. Sản phẩm giúp ngăn thức ăn dính vào bề mặt, dễ dàng vệ sinh sau khi sử dụng. Giấy nến cũng có thể được sử dụng để bọc thực phẩm, giữ độ ẩm cho bánh, hoặc làm lớp lót cho khuôn nướng. Dễ dàng cắt theo kích thước mong muốn, giấy nến 30cm x 5m là trợ thủ đắc lực cho những ai yêu thích nấu nướng và làm bánh.', 60000.00, 'uploads/giấy nến.jpg', '2024-10-27 14:35:02', 'Giấy'),
(11, 'Chổi Quét Bơ', 'Chổi quét bơ gỗ được làm từ gỗ tự nhiên, với thiết kế tay cầm chắc chắn và đầu chổi mềm mại. Kích thước khoảng 25 cm, đầu chổi rộng 4 cm, lý tưởng để quét bơ, dầu, hoặc nước sốt lên bề mặt bánh, thịt và rau củ. Chổi gỗ mang lại cảm giác cầm nắm thoải mái, dễ sử dụng và chịu nhiệt tốt. Sản phẩm dễ dàng vệ sinh và bảo quản, là dụng cụ cần thiết cho những ai yêu thích nấu nướng và làm bánh, giúp tạo ra những món ăn hấp dẫn và thơm ngon.', 30000.00, 'uploads/chổi quét bơ.jpg', '2024-10-27 14:36:21', 'Chổi'),
(12, 'Dụng Cụ Cắt Bột Mì', 'Dụng cụ cắt bột mì inox được làm từ chất liệu inox cao cấp, bền bỉ và chống gỉ. Kích thước khoảng 25 cm, với lưỡi dao sắc bén và thiết kế tay cầm tiện dụng, giúp bạn dễ dàng cắt và chia bột thành các phần đều nhau khi làm bánh hoặc pasta. Lưỡi dao có thể cắt qua bột một cách mượt mà mà không làm hỏng cấu trúc của bột. Sản phẩm dễ dàng vệ sinh và bảo quản, không hấp thụ mùi hay chất lỏng, đảm bảo an toàn cho sức khỏe. Đây là dụng cụ không thể thiếu trong bếp cho những người yêu thích làm bánh và nấu ăn.', 40000.00, 'uploads/cắt bột.jpg', '2024-10-27 14:37:14', 'Dụng cụ'),
(13, 'Dao Cắt Bánh Chuyên Dụng', 'Dao cắt bánh chuyên dụng được thiết kế đặc biệt để cắt bánh một cách dễ dàng và chính xác. Lưỡi dao dài khoảng 20 cm, làm từ inox sắc bén, giúp cắt qua các loại bánh mềm như bánh kem, bánh mousse hay bánh ngọt mà không làm hỏng hình dạng của bánh. Tay cầm ergonomics, thoải mái và chắc chắn, đảm bảo độ bám tốt khi sử dụng. Dao còn có lưỡi răng cưa, giúp cắt bánh mịn màng, tạo ra những lát bánh đẹp mắt. Dễ dàng vệ sinh và bảo quản, đây là dụng cụ không thể thiếu cho những ai yêu thích làm bánh và trang trí bánh chuyên nghiệp.', 60000.00, 'uploads/dao cắt bánh.jpg', '2024-10-27 14:38:05', 'Dao'),
(14, 'Đầu Bắt Kem', 'Đầu bắt kem là dụng cụ trang trí bánh cao cấp, được làm từ inox bền bỉ và an toàn cho sức khỏe. Với thiết kế nhiều kiểu dáng khác nhau như hình sao, hình tròn, và hình hoa, đầu bắt kem giúp bạn dễ dàng tạo ra các họa tiết đẹp mắt trên bánh kem, mousse, hoặc các món tráng miệng khác. Kích thước đầu bắt kem thường từ 3-5 cm, dễ dàng lắp vào túi bắt kem và phù hợp cho cả người mới bắt đầu lẫn thợ làm bánh chuyên nghiệp. Sản phẩm dễ dàng vệ sinh, có thể sử dụng nhiều lần và không bị gỉ sét. Đầu bắt kem inox là công cụ không thể thiếu để tạo ra những chiếc bánh hoàn hảo và hấp dẫn.', 15000.00, 'uploads/đầu bắt kem.jpg', '2024-10-27 14:39:45', 'Đầu Bắt'),
(15, 'Dụng Cụ Lấy Lòng Đỏ Trứng', 'Dụng cụ lấy lòng đỏ trứng  là công cụ tiện lợi bằng nhựa giúp tách lòng đỏ khỏi lòng trắng một cách dễ dàng và nhanh chóng. Sản phẩm được thiết kế hình dáng nhỏ gọn, với phần đầu hình chóp giúp dễ dàng thả lòng đỏ vào bát mà không bị vỡ. Kích thước khoảng 10 cm, dụng cụ này nhẹ, dễ cầm nắm và sử dụng. Chất liệu nhựa an toàn cho sức khỏe, dễ dàng vệ sinh và bảo quản. Dụng cụ lấy lòng đỏ trứng là lựa chọn lý tưởng cho những ai yêu thích làm bánh và cần tách trứng một cách chính xác và hiệu quả.', 15000.00, 'uploads/dụng cụ lấy lòng đỏ.jpg', '2024-10-27 14:40:49', 'Dụng cụ'),
(16, 'Găng Lò Nướng', 'Găng lò nướng bằng vải được làm từ chất liệu vải dày, bên trong thường có lớp lót chịu nhiệt. Kích thước khoảng 30 cm, găng có khả năng chịu nhiệt lên đến 200°C. Thiết kế ôm sát tay giúp bạn cầm nắm chắc chắn khay, khuôn nướng mà không lo bị bỏng. Sản phẩm dễ dàng vệ sinh, có thể giặt tay hoặc giặt máy. Găng lò nướng vải là dụng cụ bảo vệ tay an toàn, mang lại sự thoải mái và tiện lợi khi nấu ăn và nướng bánh.', 55000.00, 'uploads/găng làm bánh.jpg', '2024-10-27 14:42:17', 'Găng'),
(17, 'Khuôn Bánh Tròn', 'Khuôn bánh tròn inox được làm từ inox 304 chống gỉ, có kích thước 28 cm, chiều cao 7 cm. Thiết kế đáy rời giúp dễ dàng lấy bánh ra mà không làm hỏng hình dáng. Khuôn chịu nhiệt lên đến 250°C, phân phối nhiệt đều, giúp bánh chín đều và thơm ngon. Dễ dàng vệ sinh và an toàn cho máy rửa chén, khuôn bánh tròn inox là dụng cụ lý tưởng cho những người yêu thích làm bánh, giúp tạo ra những chiếc bánh hoàn hảo và hấp dẫn.', 30000.00, 'uploads/gatotron.jpg', '2024-10-27 14:43:59', 'Khuôn'),
(18, 'Khuôn Bánh Hình Hoa', 'Khuôn bánh hình hoa được làm từ chất liệu inox cao cấp, bền bỉ và chống gỉ. Kích thước khoảng 24 cm, với chiều cao 7 cm, thiết kế đáy rời giúp dễ dàng lấy bánh ra mà không làm hỏng hình dạng. Khuôn có nhiều chi tiết hoa tinh tế, tạo ra những chiếc bánh đẹp mắt và hấp dẫn. Khả năng chịu nhiệt lên đến 250°C, phân phối nhiệt đều, giúp bánh chín đều và thơm ngon. Dễ dàng vệ sinh và an toàn cho máy rửa chén, khuôn bánh hình hoa là dụng cụ hoàn hảo cho những người yêu thích làm bánh và trang trí bánh sáng tạo.', 35000.00, 'uploads/hoa.png', '2024-10-27 14:44:53', 'Khuôn'),
(19, 'Kẹp Gắp', 'Kẹp gắp inox được làm từ chất liệu inox cao cấp, bền bỉ và chống gỉ. Kích thước khoảng 25 cm, với phần đầu kẹp rộng 5 cm, giúp dễ dàng gắp và di chuyển thực phẩm như bánh, salad, hoặc rau củ mà không bị rơi. Thiết kế cơ chế mở tự động giúp bạn sử dụng một cách dễ dàng và thuận tiện. Chất liệu inox an toàn cho sức khỏe, dễ dàng vệ sinh và có thể sử dụng trong máy rửa chén. Kẹp gắp inox là dụng cụ tiện lợi không thể thiếu trong bếp, giúp bạn chế biến và phục vụ món ăn một cách chuyên nghiệp.', 20000.00, 'uploads/kẹp gắp.jpg', '2024-10-27 14:46:11', 'Kẹp'),
(20, 'Khay Nướng ', 'Khay nướng hình chữ nhật (HCN) được làm từ chất liệu inox cao cấp, bền bỉ và chống gỉ. Kích thước phổ biến khoảng 30 cm x 40 cm, chiều cao 3 cm, lý tưởng cho việc nướng bánh, thịt, rau củ hoặc các món ăn khác. Chất liệu inox giúp phân phối nhiệt đều, đảm bảo thực phẩm chín đều và thơm ngon. Khay có thiết kế đáy phẳng, dễ dàng vệ sinh và không hấp thụ mùi. An toàn cho máy rửa chén, khay nướng inox là dụng cụ không thể thiếu trong bếp, mang lại sự tiện lợi và hiệu quả khi nấu ăn.', 100000.00, 'uploads/khay nướng.jpg', '2024-10-27 14:47:04', 'Khay'),
(21, 'Khuôn Bánh Trái Tim', 'Khuôn bánh hình trái tim được làm từ chất liệu inox cao cấp, bền bỉ và chống gỉ. Kích thước khoảng 20 cm, chiều cao 7 cm, với thiết kế đáy rời giúp dễ dàng lấy bánh ra mà không làm hỏng hình dáng. Khuôn tạo ra những chiếc bánh xinh xắn và dễ thương, rất phù hợp cho các dịp đặc biệt như Valentine hay sinh nhật. Khả năng chịu nhiệt lên đến 250°C, phân phối nhiệt đều, giúp bánh chín đều và thơm ngon. Dễ dàng vệ sinh và an toàn cho máy rửa chén, khuôn bánh trái tim là lựa chọn hoàn hảo cho những ai yêu thích làm bánh và muốn tạo ra những món quà ngọt ngào.', 35000.00, 'uploads/tim.jpg', '2024-10-27 14:48:28', 'Khuôn'),
(22, 'Máy Đánh Trứng', 'Máy đánh trứng 500W là dụng cụ tiện ích trong bếp, giúp bạn dễ dàng đánh trứng, kem, hoặc các loại hỗn hợp bánh. Máy có thiết kế nhỏ gọn, với nhiều tốc độ đánh khác nhau (thường từ 3 đến 5 tốc độ), giúp bạn điều chỉnh phù hợp với nhu cầu sử dụng. Que đánh được làm từ inox chất lượng cao, đảm bảo độ bền và an toàn khi sử dụng. Máy dễ dàng vệ sinh và lưu trữ. Công suất 500W cho phép đánh nhanh chóng và hiệu quả, tiết kiệm thời gian và công sức cho những ai yêu thích nấu ăn và làm bánh. Đây là trợ thủ đắc lực để tạo ra những món ăn ngon miệng và hấp dẫn.', 1000000.00, 'uploads/maydanhtrung.jpg', '2024-10-27 14:50:05', 'Máy đánh trứng'),
(23, 'Máy Đánh Trứng Để Bàn', 'Máy đánh trứng để bàn là dụng cụ tiện lợi, thường có công suất 500W, giúp bạn dễ dàng đánh trứng, kem và các loại hỗn hợp bánh. Với thiết kế kiểu dáng hiện đại và nhỏ gọn, máy thường trang bị nhiều tốc độ đánh (3-5 tốc độ) để bạn tùy chỉnh phù hợp với nhu cầu. Que đánh được làm từ inox hoặc nhựa an toàn, đảm bảo độ bền và dễ dàng vệ sinh. Máy đánh trứng để bàn giúp tiết kiệm thời gian và công sức, lý tưởng cho những người yêu thích làm bánh và nấu ăn tại nhà, mang lại những món ăn ngon miệng và hấp dẫn.', 1500000.00, 'uploads/maydanhtrungdeban.jpg', '2024-10-27 14:50:46', 'Máy đánh trứng'),
(24, 'Nhiệt Kế Lò Nướng', 'Nhiệt kế lò nướng là dụng cụ hữu ích giúp bạn theo dõi nhiệt độ bên trong lò nướng một cách chính xác. Sản phẩm thường có thiết kế bằng inox hoặc thủy tinh chịu nhiệt, với nhiệt độ đo được từ 0°C đến 300°C. Kích thước nhỏ gọn, dễ dàng gắn vào lò nướng hoặc đặt bên trong khay nướng. Nhiệt kế thường có mặt đồng hồ rõ ràng, giúp bạn dễ dàng đọc kết quả. Sử dụng nhiệt kế lò nướng giúp đảm bảo thực phẩm được nấu chín đều, mang lại kết quả món ăn ngon miệng và đạt yêu cầu. Đây là dụng cụ không thể thiếu cho những ai yêu thích nấu ăn và làm bánh.', 150000.00, 'uploads/nhiệt kế lò.jpg', '2024-10-27 14:51:26', 'Nhiệt Kế'),
(25, 'Phới Dẹt', 'Phới dẹt silicone là dụng cụ nhà bếp lý tưởng với lưỡi phẳng, giúp khuấy, trộn và phết các hỗn hợp một cách dễ dàng. Kích thước phổ biến khoảng 30 cm, với đầu phới rộng khoảng 5 cm, phù hợp cho việc trộn bột, kem, hoặc các loại sốt. Chất liệu silicone chịu nhiệt lên đến 230°C, an toàn cho sức khỏe và không gây trầy xước bề mặt nồi, chảo. Thiết kế tay cầm tiện dụng, dễ dàng cầm nắm và thao tác, đồng thời dễ dàng vệ sinh, có thể sử dụng trong máy rửa chén. Phới dẹt silicone là dụng cụ không thể thiếu trong bếp, mang lại sự tiện lợi và hiệu quả khi chế biến món ăn.', 30000.00, 'uploads/phới dẹt.jpg', '2024-10-27 14:52:19', 'Phới'),
(26, 'Phới Lông', 'Phới lồng  là dụng cụ nhà bếp bằng inox chuyên dụng để đánh trứng, khuấy bột và trộn các hỗn hợp. Với thiết kế lồng dạng dây, phới giúp không khí dễ dàng đi vào hỗn hợp, tạo độ bông xốp cho trứng và kem. Kích thước phổ biến khoảng 30 cm, với tay cầm chắc chắn, dễ cầm nắm và thao tác. Chất liệu inox bền bỉ, chống gỉ sét và an toàn cho sức khỏe, dễ dàng vệ sinh và bảo quản. Phới lồng inox là dụng cụ không thể thiếu cho những ai yêu thích làm bánh và nấu ăn, giúp tiết kiệm thời gian và nâng cao chất lượng món ăn.', 40000.00, 'uploads/phới lồng.jpg', '2024-10-27 14:53:30', 'Phới'),
(27, 'Rây Bột', 'Rây bột  là dụng cụ nhà bếp thiết yếu bằng inox giúp lọc và rây bột, đường hoặc các nguyên liệu khô khác một cách hiệu quả. Sản phẩm được làm từ chất liệu inox cao cấp, bền bỉ và chống gỉ, đảm bảo an toàn cho sức khỏe. Kích thước phổ biến khoảng 20 cm, với tay cầm chắc chắn và dễ cầm nắm. Lưới rây được thiết kế với các lỗ nhỏ, giúp loại bỏ tạp chất và tạo độ mịn cho bột. Rây bột inox dễ dàng vệ sinh và có thể rửa trong máy rửa chén. Dụng cụ này là lựa chọn hoàn hảo cho những người yêu thích làm bánh và nấu ăn, giúp nâng cao chất lượng các món ăn.', 45000.00, 'uploads/rây bột.jpg', '2024-10-27 14:54:33', 'Rây'),
(28, 'Túi Bắt Kem', 'Túi bắt kem là dụng cụ không thể thiếu trong làm bánh, giúp bạn dễ dàng trang trí bánh và món ăn bằng kem hoặc các loại hỗn hợp mềm. Túi thường được làm từ chất liệu nhựa dẻo hoặc silicone, có độ bền cao và an toàn cho thực phẩm. Kích thước phổ biến khoảng 30 cm, với miệng túi có thể cắt để điều chỉnh kích thước theo ý muốn. Túi bắt kem đi kèm với nhiều đầu bắt khác nhau, cho phép bạn tạo ra các hình dạng và hoa văn đa dạng. Dễ dàng sử dụng và vệ sinh, túi bắt kem là công cụ lý tưởng cho những ai yêu thích trang trí bánh và món ăn đẹp mắt.', 15000.00, 'uploads/túi bắt kem.jpg', '2024-10-27 14:55:06', 'Khuôn'),
(42, 'Nguyên Liêu Bột Mì', 'Bột mì là nguyên liệu không thể thiếu trong bếp của mọi thợ làm bánh. Với chất lượng vượt trội, bột mì giúp bạn tạo nên những chiếc bánh thơm ngon, mềm mịn và giòn tan. Được làm từ lúa mì chọn lọc, bột mì có độ mịn và độ tơi xốp hoàn hảo, mang lại sự dễ dàng trong việc kết hợp với các nguyên liệu khác như bơ, trứng, và đường.  Dù bạn đang làm bánh mì, bánh ngọt, bánh quy hay bánh bông lan, bột mì luôn là lựa chọn lý tưởng để đảm bảo kết quả tuyệt vời mỗi lần nướng. Với bột mì này, bạn có thể tự tin sáng tạo ra những món bánh thơm ngon cho gia đình hoặc khách hàng của mình.', 20000.00, 'uploads/Botmi.jpg', '2024-11-14 13:51:06', 'Nguyên Liệu');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `user_name`, `content`, `created_at`) VALUES
(9, 2, 'son', 'oke day', '2024-11-11 05:58:20'),
(11, 2, 'son', 'Được quá bạn', '2024-11-14 16:15:06');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`, `full_name`, `email`, `phone`, `address`) VALUES
(4, 'nam', '$2y$10$CU6Ge/heSHj/g4RsCh9mFOgR.pEqDWNLkkPECFLKK6lVdBmk3360.', '2024-10-19 03:37:02', 'Nguyễn VăN Nam', 'nvnam.dhti15a15hn@sv.uneti.edu.vn', '0865693162', 'số nhà 04 , ngõ cổng gạch , khê ngoại 3 , văn khê , mê linh , hà nội'),
(6, 'son', '$2y$10$H5ivK6qaOIJE4TqkfzX/7u5zMiTgx6I1rykgsGTVTAlMNQxX0Wy0m', '2024-11-15 15:45:23', 'Nguyễn Văn Sơn', 'son@gmail.com', '0987654321', 'Giáp bát ');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
