-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2023 at 03:40 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `trainingphp`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(10) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `price` float DEFAULT NULL,
  `discount` float DEFAULT NULL,
  `featured_img` varchar(255) DEFAULT NULL,
  `gallery` varchar(500) DEFAULT NULL,
  `brand` varchar(500) DEFAULT NULL,
  `category` varchar(500) DEFAULT NULL,
  `tag` varchar(500) DEFAULT NULL,
  `rate` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `sku`, `description`, `price`, `discount`, `featured_img`, `gallery`, `brand`, `category`, `tag`, `rate`, `create_date`, `modified_date`) VALUES
(1, 'Product name 1', 'product-name-1', '', 22, 0, '', '', 'Brand 1', 'Category 1', '', 0, '2023-05-15 09:07:59', '2023-05-15 10:57:15'),
(2, 'Laptop Asus TUF Gaming', 'laptop-asus-tuf-gaming', '					', 3290000, 0, 'http://localhost/trainingphp/mvc/public/uploads/Laptop-AsusTUF.jpg', 'http://localhost/trainingphp/mvc/public/uploads/Laptop-AsusTUF-2.jpg,http://localhost/trainingphp/mvc/public/uploads/Laptop-AsusTUF.jpg,http://localhost/trainingphp/mvc/public/uploads/Laptop-AsusTUF-1.jpg', 'Brand 1', 'Category 1', 'Tag 1', 0, '2023-05-15 10:04:58', '2023-05-16 09:00:26'),
(3, 'Product name 2', 'product-name-2', '					', 321, 0, 'http://localhost/trainingphp/mvc/public/uploads/Laptop-AsusTUF.jpg', 'http://localhost/trainingphp/mvc/public/uploads/Laptop-AsusTUF-2.jpg,http://localhost/trainingphp/mvc/public/uploads/Laptop-AsusTUF.jpg,http://localhost/trainingphp/mvc/public/uploads/Laptop-AsusTUF-1.jpg', 'Brand 1', 'Category 1', 'Tag 1', 0, '2023-05-15 10:59:37', '2023-05-16 09:05:05');

-- --------------------------------------------------------

--
-- Table structure for table `product_property`
--

CREATE TABLE `product_property` (
  `id` int(10) NOT NULL,
  `property_id` int(10) DEFAULT NULL,
  `product_id` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_property`
--

INSERT INTO `product_property` (`id`, `property_id`, `product_id`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 1, 2),
(4, 2, 2),
(5, 3, 2),
(6, 1, 3),
(7, 3, 3),
(8, 2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `property_id` int(10) NOT NULL,
  `property_type` varchar(255) DEFAULT NULL,
  `property_name` varchar(255) DEFAULT NULL,
  `property_slug` varchar(255) DEFAULT NULL,
  `property_description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`property_id`, `property_type`, `property_name`, `property_slug`, `property_description`) VALUES
(1, 'brand', 'Brand 1', 'brand_1', ''),
(2, 'category', 'Category 1', 'category_1', ''),
(3, 'tag', 'Tag 1', 'tag_1', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `product_property`
--
ALTER TABLE `product_property`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_id` (`property_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`property_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product_property`
--
ALTER TABLE `product_property`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `property_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `product_property`
--
ALTER TABLE `product_property`
  ADD CONSTRAINT `product_property_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`property_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_property_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
