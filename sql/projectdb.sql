-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2025-07-08 17:36:05
-- 伺服器版本： 10.4.32-MariaDB
-- PHP 版本： 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `projectdb`
--
CREATE DATABASE IF NOT EXISTS `projectdb` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `projectdb`;

-- --------------------------------------------------------

--
-- 資料表結構 `customer`
--

CREATE TABLE `customer` (
  `cid` int(11) NOT NULL,
  `cname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `cpassword` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ctel` int(11) DEFAULT NULL,
  `caddr` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `company` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `customer`
--

INSERT INTO `customer` (`cid`, `cname`, `cpassword`, `ctel`, `caddr`, `company`, `email`) VALUES
(1, 'Alex Wong', 'itp4235m', 21232123, 'G/F, ABC Building, King Yip Street, KwunTong, Kowloon, Hong Kong', 'Fat Cat Company Limited', 'john.doe92@gmail.com'),
(2, 'Tina Chan', 'itp4235m', 31233123, '303, Mei Hing Center, Yuen Long, NT, Hong Kong', 'XDD LOL Company', 'sarah.lee87@yahoo.com'),
(3, 'Bowie', 'itp4235m', 61236123, '401, Sing Kei Building, Kowloon, Hong Kong', 'GPA4 Company', 'michael.smith45@hotmail.com'),
(4, 'ken', '$2y$10$Q7dC/dL5S3tBZxe3UuA/qecF9L.Yq4kS6MuLjqcJQd70inXnBBe/m', 12345678, '', '', 'ken@gmail.com');

-- --------------------------------------------------------

--
-- 資料表結構 `material`
--

CREATE TABLE `material` (
  `mid` int(11) NOT NULL,
  `mname` varchar(255) NOT NULL,
  `mqty` int(11) NOT NULL,
  `mrqty` int(11) NOT NULL,
  `munit` varchar(50) NOT NULL,
  `mreorderqty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `material`
--

INSERT INTO `material` (`mid`, `mname`, `mqty`, `mrqty`, `munit`, `mreorderqty`) VALUES
(1, 'Rubber 3233', 100, 50, 'KG', 10),
(2, 'Cotten CDC24', 200, 100, 'PC', 20),
(3, 'Plastic ABC', 150, 75, 'KG', 15),
(4, 'Silicone SR45', 120, 60, 'KG', 12),
(5, 'Polyester Fabric PF12', 300, 150, 'M', 30),
(6, 'Aluminum Alloy AA23', 80, 40, 'KG', 8),
(7, 'Wooden Beads WB56', 250, 100, 'PC', 25),
(8, 'PVC Sheet PS78', 200, 120, 'M', 20),
(9, 'Steel Wire SW34', 150, 75, 'M', 15),
(10, 'Cotton Thread CT89', 400, 200, 'M', 40),
(11, 'ABS Plastic AB22', 180, 90, 'KG', 18),
(12, 'Foam Padding FP67', 220, 110, 'M', 22),
(13, 'Nylon Rope NR45', 100, 50, 'M', 10),
(14, 'LED Module LM12', 300, 120, 'PC', 30),
(15, 'Battery Pack BP33', 200, 80, 'PC', 20),
(16, 'Paperboard PB56', 500, 250, 'PC', 50),
(17, 'Acrylic Paint AP78', 90, 45, 'KG', 9),
(18, 'Brass Fittings BF23', 120, 60, 'PC', 12),
(19, 'Velvet Fabric VF45', 250, 125, 'M', 25),
(20, 'Rubber Band RB89', 400, 200, 'PC', 40),
(21, 'Plastic Gear PG34', 180, 90, 'PC', 18),
(22, 'Copper Wire CW56', 150, 75, 'M', 15),
(23, 'Sponge Sheet SS12', 200, 100, 'M', 20),
(24, 'Cardboard CB78', 300, 150, 'PC', 30),
(25, 'Magnet Strip MS23', 100, 50, 'PC', 10),
(26, 'Felt Fabric FF45', 220, 110, 'M', 22),
(27, 'Zinc Alloy ZA67', 80, 40, 'KG', 8),
(28, 'Packing Foam PF89', 250, 125, 'M', 25),
(29, 'Screws Set SC12', 500, 200, 'PC', 50),
(30, 'Adhesive Tape AT34', 300, 150, 'M', 30);

-- --------------------------------------------------------

--
-- 資料表結構 `orderline`
--

CREATE TABLE `orderline` (
  `oid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `oqty` int(11) NOT NULL,
  `ocost` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `orderline`
--

INSERT INTO `orderline` (`oid`, `pid`, `oqty`, `ocost`) VALUES
(1, 6, 5, 79.95),
(1, 12, 2, 51.98),
(2, 1, 3, 59.70),
(2, 9, 1, 39.99),
(2, 15, 4, 59.96),
(3, 5, 1, 499.00),
(4, 7, 2, 99.98),
(4, 16, 3, 56.97),
(5, 2, 4, 39.60),
(5, 10, 2, 39.98),
(5, 13, 1, 34.99),
(6, 8, 3, 74.97),
(6, 11, 2, 59.98),
(7, 14, 1, 89.99),
(8, 3, 1, 249.90),
(8, 17, 1, 59.99),
(8, 19, 1, 39.99),
(9, 4, 2, 60.00),
(9, 20, 2, 49.98),
(10, 18, 2, 59.98),
(10, 21, 1, 49.99),
(11, 6, 4, 63.96),
(11, 9, 1, 39.99),
(11, 22, 1, 34.99),
(12, 5, 1, 499.00),
(13, 12, 2, 51.98),
(13, 23, 3, 59.97),
(14, 1, 2, 39.80),
(14, 15, 5, 74.95),
(14, 24, 1, 29.99),
(15, 7, 1, 49.99),
(15, 25, 1, 39.99),
(16, 14, 1, 89.99),
(17, 2, 5, 49.50),
(17, 10, 2, 39.98),
(17, 26, 1, 24.99),
(18, 8, 2, 49.98),
(18, 27, 1, 29.99),
(19, 4, 1, 30.00),
(19, 28, 3, 59.97),
(20, 3, 1, 249.90),
(21, 6, 3, 47.97),
(21, 11, 1, 29.99),
(21, 29, 2, 45.98),
(22, 13, 1, 34.99),
(22, 30, 1, 49.99),
(23, 9, 1, 39.99),
(23, 16, 2, 37.98),
(24, 1, 3, 59.70),
(24, 17, 1, 59.99),
(24, 18, 1, 29.99),
(25, 5, 1, 499.00),
(26, 12, 1, 25.99),
(26, 19, 1, 39.99),
(27, 2, 3, 29.70),
(27, 15, 4, 59.96),
(27, 20, 1, 24.99),
(28, 7, 1, 49.99),
(28, 21, 1, 49.99),
(29, 14, 1, 89.99),
(30, 6, 4, 63.96),
(30, 10, 2, 39.98),
(30, 22, 1, 34.99),
(31, 27, 1, 29.99),
(31, 29, 1, 22.99);

-- --------------------------------------------------------

--
-- 資料表結構 `orders`
--

CREATE TABLE `orders` (
  `oid` int(11) NOT NULL,
  `odate` datetime NOT NULL,
  `cid` int(11) NOT NULL,
  `odeliverdate` datetime DEFAULT NULL,
  `ostatus` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `orders`
--

INSERT INTO `orders` (`oid`, `odate`, `cid`, `odeliverdate`, `ostatus`) VALUES
(1, '2025-04-12 17:50:00', 1, '2025-04-19 03:31:00', 3),
(2, '2025-04-13 09:30:00', 2, '2025-04-20 12:00:00', 0),
(3, '2025-07-05 10:00:00', 1, '2025-07-12 10:00:00', 5),
(4, '2025-07-10 14:00:00', 2, '2025-07-17 14:00:00', 4),
(5, '2025-01-05 09:15:00', 1, '2025-01-12 10:00:00', 1),
(6, '2025-01-10 14:30:00', 2, '2025-01-17 14:00:00', 2),
(7, '2025-01-15 11:00:00', 3, '2025-01-22 10:00:00', 5),
(8, '2025-02-01 16:45:00', 4, '2025-02-08 14:00:00', 3),
(9, '2025-02-10 08:30:00', 1, '2025-02-17 10:00:00', 4),
(10, '2025-02-15 13:20:00', 2, '2025-02-22 14:00:00', 0),
(11, '2025-03-01 10:10:00', 3, '2025-03-08 10:00:00', 5),
(12, '2025-03-05 15:00:00', 4, '2025-03-12 14:00:00', 2),
(13, '2025-03-10 12:25:00', 1, '2025-03-17 10:00:00', 1),
(14, '2025-03-15 17:50:00', 2, '2025-03-22 14:00:00', 3),
(15, '2025-04-01 09:40:00', 3, '2025-04-08 10:00:00', 4),
(16, '2025-04-05 14:15:00', 4, '2025-04-12 14:00:00', 5),
(17, '2025-04-15 11:30:00', 1, '2025-04-22 10:00:00', 0),
(18, '2025-05-01 16:00:00', 2, '2025-05-08 14:00:00', 2),
(19, '2025-05-05 08:45:00', 3, '2025-05-12 10:00:00', 1),
(20, '2025-05-10 13:10:00', 4, '2025-05-17 14:00:00', 3),
(21, '2025-05-15 10:20:00', 1, '2025-05-22 10:00:00', 5),
(22, '2025-06-01 15:30:00', 2, '2025-06-08 14:00:00', 4),
(23, '2025-06-05 12:00:00', 3, '2025-06-12 10:00:00', 2),
(24, '2025-06-10 09:50:00', 4, '2025-06-17 14:00:00', 0),
(25, '2025-06-15 17:15:00', 1, '2025-06-22 10:00:00', 5),
(26, '2025-07-01 11:45:00', 2, '2025-07-08 14:00:00', 3),
(27, '2025-07-05 14:20:00', 3, '2025-07-12 10:00:00', 1),
(28, '2025-07-10 08:30:00', 4, '2025-07-17 14:00:00', 4),
(29, '2025-07-15 16:10:00', 1, '2025-07-22 10:00:00', 2),
(30, '2025-07-20 10:00:00', 2, '2025-07-27 14:00:00', 5),
(31, '2025-07-08 17:09:04', 4, '2025-07-15 17:09:04', 1);

-- --------------------------------------------------------

--
-- 資料表結構 `prodmat`
--

CREATE TABLE `prodmat` (
  `pid` int(11) NOT NULL,
  `mid` int(11) NOT NULL,
  `pmqty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `prodmat`
--

INSERT INTO `prodmat` (`pid`, `mid`, `pmqty`) VALUES
(1, 1, 3),
(1, 3, 5),
(1, 6, 10),
(2, 4, 20),
(2, 14, 2),
(3, 8, 4),
(3, 11, 5),
(3, 12, 2),
(4, 3, 3),
(4, 15, 10),
(5, 8, 6),
(5, 11, 3),
(5, 16, 5),
(6, 9, 2),
(6, 23, 3),
(7, 8, 8),
(8, 2, 10),
(8, 3, 2),
(9, 3, 4),
(9, 6, 8),
(9, 12, 1),
(10, 13, 50),
(11, 8, 1),
(11, 14, 1),
(12, 3, 1),
(12, 13, 30),
(13, 4, 30),
(13, 14, 3),
(14, 3, 5),
(14, 4, 50),
(14, 16, 10),
(15, 3, 2),
(15, 21, 20),
(16, 20, 3),
(16, 23, 4),
(17, 3, 6),
(17, 4, 20),
(18, 8, 10),
(19, 4, 40),
(19, 10, 5),
(20, 3, 2),
(20, 13, 10),
(21, 4, 30),
(21, 14, 2),
(22, 3, 3),
(22, 4, 25),
(23, 3, 1),
(23, 16, 2),
(24, 8, 3),
(24, 15, 5),
(25, 4, 20),
(25, 23, 5),
(26, 3, 2),
(26, 11, 1),
(27, 8, 5),
(28, 2, 5),
(28, 3, 2),
(29, 4, 50),
(30, 3, 4),
(30, 6, 10),
(30, 12, 1);

-- --------------------------------------------------------

--
-- 資料表結構 `product`
--

CREATE TABLE `product` (
  `pid` int(11) NOT NULL,
  `pname` varchar(255) NOT NULL,
  `pdesc` text DEFAULT NULL,
  `pcost` decimal(10,2) NOT NULL,
  `pimage` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `product`
--

INSERT INTO `product` (`pid`, `pname`, `pdesc`, `pcost`, `pimage`) VALUES
(1, 'Cyberpunk Truck C204', 'Explore the world of imaginative play with our vibrant and durable toy truck. Perfect for little hands, this truck will inspire endless storytelling adventures both indoors and outdoors. Made from high-quality materials, it is built to withstand hours of creative playtime.', 19.90, '/4523WebProjectGroup08/uploads/pid1.jpg'),
(2, 'XDD Wooden Plane', 'Take to the skies with our charming wooden plane toy. Crafted from eco-friendly and child-safe materials, this beautifully designed plane sparks the imagination and encourages interactive play. With smooth edges and a sturdy construction, it\'s a delightful addition to any young aviator\'s toy collection.', 9.90, '/4523WebProjectGroup08/uploads/pid2.jpg'),
(3, 'iRobot 3233GG', 'Introduce your child to the wonders of technology and robotics with our smart robot companion. Packed with interactive features and educational benefits, this futuristic toy engages curious minds and promotes STEM learning in a fun and engaging way. Watch as your child explores coding, problem-solving, and innovation with this cutting-edge robot friend.', 249.90, '/4523WebProjectGroup08/uploads/pid3.jpg'),
(4, 'Apex Ball Ball Helicopter M1297', 'Experience the thrill of flight with our ball helicopter toy. Easy to launch and navigate, this exciting toy provides hours of entertainment for children of all ages. With colorful LED lights and a durable design, it\'s a fantastic outdoor toy that brings joy and excitement to playtime.', 30.00, '/4523WebProjectGroup08/uploads/pid4.jpg'),
(5, 'RoboKat AI Cat Robot', 'Meet our AI Cat Robot – the purr-fect blend of technology and cuddly companionship. This interactive robotic feline offers lifelike movements, sounds, and responses, providing a realistic pet experience without the hassle. With customizable features and playful interactions, this charming cat robot is a delightful addition to your child\'s playroom.', 499.00, '/4523WebProjectGroup08/uploads/pid5.jpg'),
(6, 'Teddy Bear', 'Soft and cuddly teddy bear with brown fur and a red bow', 15.99, '/4523WebProjectGroup08/uploads/pid6.jpg'),
(7, 'LEGO City Police Station', 'LEGO set with 500 pieces to build a police station', 49.99, '/4523WebProjectGroup08/uploads/pid7.jpg'),
(8, 'Barbie Dreamhouse Adventures Doll', 'Barbie doll with accessories for dreamhouse adventures', 24.99, '/4523WebProjectGroup08/uploads/pid8.jpg'),
(9, 'RC Racing Car', 'High-speed remote control car with rechargeable battery', 39.99, '/4523WebProjectGroup08/uploads/pid9.jpg'),
(10, '1000-Piece Jigsaw Puzzle', 'Challenging jigsaw puzzle with a landscape image', 19.99, '/4523WebProjectGroup08/uploads/pid10.jpg'),
(11, 'Superhero Action Figure', 'Poseable action figure of a popular superhero', 29.99, '/4523WebProjectGroup08/uploads/pid11.jpg'),
(12, 'Monopoly Classic', 'Classic board game for family fun', 25.99, '/4523WebProjectGroup08/uploads/pid12.jpg'),
(13, 'Wooden Toy Train Set', 'Wooden train set with tracks and accessories', 34.99, '/4523WebProjectGroup08/uploads/pid13.jpg'),
(14, 'Victorian Dollhouse', 'Detailed dollhouse with furniture and miniature dolls', 89.99, '/4523WebProjectGroup08/uploads/pid14.jpg'),
(15, 'Play-Doh Creativity Kit', 'Set with multiple colors of Play-Doh and molding tools', 14.99, '/4523WebProjectGroup08/uploads/pid15.jpg'),
(16, 'Plush Elephant', 'Soft plush elephant with big ears and a trunk', 18.99, '/4523WebProjectGroup08/uploads/pid16.jpg'),
(17, 'Little Chef Kitchen Playset', 'Miniature kitchen set with stove, sink, and utensils', 59.99, '/4523WebProjectGroup08/uploads/pid17.jpg'),
(18, 'Mega Building Blocks', 'Large building blocks for toddlers', 29.99, '/4523WebProjectGroup08/uploads/pid18.jpg'),
(19, 'Kid\'s Acoustic Guitar', 'Small acoustic guitar for children', 39.99, '/4523WebProjectGroup08/uploads/pid19.jpg'),
(20, 'Junior Scientist Kit', 'Educational science kit with experiments', 24.99, '/4523WebProjectGroup08/uploads/pid20.jpg'),
(21, 'Double-Sided Art Easel', 'Easel with chalkboard and whiteboard sides', 49.99, '/4523WebProjectGroup08/uploads/pid21.jpg'),
(22, 'Mini Drum Kit', 'Small drum set for kids with drumsticks', 34.99, '/4523WebProjectGroup08/uploads/pid22.jpg'),
(23, 'Young Magician\'s Kit', 'Magic kit with tricks and props', 19.99, '/4523WebProjectGroup08/uploads/pid23.jpg'),
(24, 'Explorer Telescope', 'Toy telescope for stargazing', 29.99, '/4523WebProjectGroup08/uploads/pid24.jpg'),
(25, 'Hand Puppet Theater', 'Theater stage with hand puppets', 39.99, '/4523WebProjectGroup08/uploads/pid25.jpg'),
(26, 'Play Cash Register', 'Toy cash register with play money', 24.99, '/4523WebProjectGroup08/uploads/pid26.jpg'),
(27, 'Marble Run Deluxe', 'Marble run set with tracks and marbles', 29.99, '/4523WebProjectGroup08/uploads/pid27.jpg'),
(28, 'Doctor Play Set', 'Medical kit with stethoscope and tools', 19.99, '/4523WebProjectGroup08/uploads/pid28.jpg'),
(29, 'Classic Wooden Blocks', 'Set of wooden blocks in various shapes', 22.99, '/4523WebProjectGroup08/uploads/pid29.jpg'),
(30, 'Remote Control Airplane', 'RC airplane with remote control', 49.99, '/4523WebProjectGroup08/uploads/pid30.jpg');

-- --------------------------------------------------------

--
-- 資料表結構 `staff`
--

CREATE TABLE `staff` (
  `sid` int(11) NOT NULL,
  `spassword` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `sname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `srole` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `stel` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `staff`
--

INSERT INTO `staff` (`sid`, `spassword`, `sname`, `srole`, `stel`) VALUES
(1, 'itp4523m', 'Hachi Leung', 'admin', 25669197);

-- --------------------------------------------------------

--
-- 資料表結構 `topselling`
--

CREATE TABLE `topselling` (
  `pid` int(11) NOT NULL,
  `sales_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `topselling`
--

INSERT INTO `topselling` (`pid`, `sales_count`) VALUES
(6, 30),
(14, 10),
(15, 20),
(20, 50);

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`cid`);

--
-- 資料表索引 `material`
--
ALTER TABLE `material`
  ADD PRIMARY KEY (`mid`);

--
-- 資料表索引 `orderline`
--
ALTER TABLE `orderline`
  ADD PRIMARY KEY (`oid`,`pid`),
  ADD KEY `pid` (`pid`);

--
-- 資料表索引 `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`oid`),
  ADD KEY `cid` (`cid`);

--
-- 資料表索引 `prodmat`
--
ALTER TABLE `prodmat`
  ADD PRIMARY KEY (`pid`,`mid`),
  ADD KEY `mid` (`mid`);

--
-- 資料表索引 `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`pid`);

--
-- 資料表索引 `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`sid`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `customer`
--
ALTER TABLE `customer`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `material`
--
ALTER TABLE `material`
  MODIFY `mid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `orders`
--
ALTER TABLE `orders`
  MODIFY `oid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `product`
--
ALTER TABLE `product`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `staff`
--
ALTER TABLE `staff`
  MODIFY `sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `orderline`
--
ALTER TABLE `orderline`
  ADD CONSTRAINT `orderline_ibfk_1` FOREIGN KEY (`oid`) REFERENCES `orders` (`oid`) ON DELETE CASCADE,
  ADD CONSTRAINT `orderline_ibfk_2` FOREIGN KEY (`pid`) REFERENCES `product` (`pid`) ON DELETE CASCADE;

--
-- 資料表的限制式 `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`cid`) REFERENCES `customer` (`cid`) ON DELETE CASCADE;

--
-- 資料表的限制式 `prodmat`
--
ALTER TABLE `prodmat`
  ADD CONSTRAINT `prodmat_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `product` (`pid`) ON DELETE CASCADE,
  ADD CONSTRAINT `prodmat_ibfk_2` FOREIGN KEY (`mid`) REFERENCES `material` (`mid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
