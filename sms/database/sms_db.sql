-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 03, 2021 at 07:31 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `back_order_list`
--

CREATE TABLE `back_order_list` (
  `id` int(30) NOT NULL,
  `receiving_id` int(30) NOT NULL,
  `po_id` int(30) NOT NULL,
  `bo_code` varchar(50) NOT NULL,
  `supplier_id` int(30) NOT NULL,
  `amount` float NOT NULL,
  `discount_perc` float NOT NULL DEFAULT 0,
  `discount` float NOT NULL DEFAULT 0,
  `tax_perc` float NOT NULL DEFAULT 0,
  `tax` float NOT NULL DEFAULT 0,
  `remarks` text DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 = pending, 1 = partially received, 2 =received',
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `back_order_list`
--

INSERT INTO `back_order_list` (`id`, `receiving_id`, `po_id`, `bo_code`, `supplier_id`, `amount`, `discount_perc`, `discount`, `tax_perc`, `tax`, `remarks`, `status`, `date_created`, `date_updated`) VALUES
(1, 1, 1, 'BO-0001', 1, 40740, 3, 1125, 12, 4365, NULL, 1, '2021-11-03 11:20:38', '2021-11-03 11:20:51'),
(2, 2, 1, 'BO-0002', 1, 20370, 3, 562.5, 12, 2182.5, NULL, 2, '2021-11-03 11:20:51', '2021-11-03 11:21:00'),
(3, 4, 2, 'BO-0003', 2, 42826, 5, 2012.5, 12, 4588.5, NULL, 1, '2021-11-03 11:51:41', '2021-11-03 11:52:02'),
(4, 5, 2, 'BO-0004', 2, 10640, 5, 500, 12, 1140, NULL, 2, '2021-11-03 11:52:02', '2021-11-03 11:52:15');

-- --------------------------------------------------------

--
-- Table structure for table `bo_items`
--

CREATE TABLE `bo_items` (
  `bo_id` int(30) NOT NULL,
  `item_id` int(30) NOT NULL,
  `quantity` int(30) NOT NULL,
  `price` float NOT NULL DEFAULT 0,
  `unit` varchar(50) NOT NULL,
  `total` float NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bo_items`
--

INSERT INTO `bo_items` (`bo_id`, `item_id`, `quantity`, `price`, `unit`, `total`) VALUES
(1, 1, 250, 150, 'pcs', 37500),
(2, 1, 125, 150, 'pcs', 18750),
(3, 2, 150, 200, 'Boxes', 30000),
(3, 4, 50, 205, 'pcs', 10250),
(4, 2, 50, 200, 'Boxes', 10000);

-- --------------------------------------------------------

--
-- Table structure for table `item_list`
--

CREATE TABLE `item_list` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `supplier_id` int(30) NOT NULL,
  `cost` float NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `item_list`
--

INSERT INTO `item_list` (`id`, `name`, `description`, `supplier_id`, `cost`, `status`, `date_created`, `date_updated`) VALUES
(1, 'Item 101', 'Sample Only', 1, 150, 1, '2021-11-02 10:01:55', '2021-11-02 10:01:55'),
(2, 'Item 102', 'Sample only', 2, 200, 1, '2021-11-02 10:02:12', '2021-11-02 10:02:12'),
(3, 'Item 103', 'Sample', 1, 185, 1, '2021-11-02 10:02:27', '2021-11-02 10:02:27'),
(4, 'Item 104', 'Sample only', 2, 205, 1, '2021-11-02 10:02:47', '2021-11-02 10:02:47');

-- --------------------------------------------------------

--
-- Table structure for table `po_items`
--

CREATE TABLE `po_items` (
  `po_id` int(30) NOT NULL,
  `item_id` int(30) NOT NULL,
  `quantity` int(30) NOT NULL,
  `price` float NOT NULL DEFAULT 0,
  `unit` varchar(50) NOT NULL,
  `total` float NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `po_items`
--

INSERT INTO `po_items` (`po_id`, `item_id`, `quantity`, `price`, `unit`, `total`) VALUES
(1, 1, 500, 150, 'pcs', 75000),
(2, 2, 300, 200, 'Boxes', 60000),
(2, 4, 200, 205, 'pcs', 41000);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_list`
--

CREATE TABLE `purchase_order_list` (
  `id` int(30) NOT NULL,
  `po_code` varchar(50) NOT NULL,
  `supplier_id` int(30) NOT NULL,
  `amount` float NOT NULL,
  `discount_perc` float NOT NULL DEFAULT 0,
  `discount` float NOT NULL DEFAULT 0,
  `tax_perc` float NOT NULL DEFAULT 0,
  `tax` float NOT NULL DEFAULT 0,
  `remarks` text NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 = pending, 1 = partially received, 2 =received',
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `purchase_order_list`
--

INSERT INTO `purchase_order_list` (`id`, `po_code`, `supplier_id`, `amount`, `discount_perc`, `discount`, `tax_perc`, `tax`, `remarks`, `status`, `date_created`, `date_updated`) VALUES
(1, 'PO-0001', 1, 81480, 3, 2250, 12, 8730, 'Sample', 2, '2021-11-03 11:20:22', '2021-11-03 11:21:00'),
(2, 'PO-0002', 2, 107464, 5, 5050, 12, 11514, 'Sample PO Only', 2, '2021-11-03 11:50:50', '2021-11-03 11:52:15');

-- --------------------------------------------------------

--
-- Table structure for table `receiving_list`
--

CREATE TABLE `receiving_list` (
  `id` int(30) NOT NULL,
  `form_id` int(30) NOT NULL,
  `from_order` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=PO ,2 = BO',
  `amount` float NOT NULL DEFAULT 0,
  `discount_perc` float NOT NULL DEFAULT 0,
  `discount` float NOT NULL DEFAULT 0,
  `tax_perc` float NOT NULL DEFAULT 0,
  `tax` float NOT NULL DEFAULT 0,
  `stock_ids` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `receiving_list`
--

INSERT INTO `receiving_list` (`id`, `form_id`, `from_order`, `amount`, `discount_perc`, `discount`, `tax_perc`, `tax`, `stock_ids`, `remarks`, `date_created`, `date_updated`) VALUES
(1, 1, 1, 40740, 3, 1125, 12, 4365, '1', 'Sample', '2021-11-03 11:20:38', '2021-11-03 11:20:38'),
(2, 1, 2, 20370, 3, 562.5, 12, 2182.5, '2', '', '2021-11-03 11:20:51', '2021-11-03 11:20:51'),
(3, 2, 2, 20370, 3, 562.5, 12, 2182.5, '3', 'Success', '2021-11-03 11:21:00', '2021-11-03 11:21:00'),
(4, 2, 1, 64638, 5, 3037.5, 12, 6925.5, '4,5', 'Sample Receiving (Partial)', '2021-11-03 11:51:41', '2021-11-03 11:51:41'),
(5, 3, 2, 32186, 5, 1512.5, 12, 3448.5, '6,7', 'BO Receive (Partial)', '2021-11-03 11:52:02', '2021-11-03 11:52:02'),
(6, 4, 2, 10640, 5, 500, 12, 1140, '8', 'Sample Success', '2021-11-03 11:52:15', '2021-11-03 11:52:15');

-- --------------------------------------------------------

--
-- Table structure for table `return_list`
--

CREATE TABLE `return_list` (
  `id` int(30) NOT NULL,
  `return_code` varchar(50) NOT NULL,
  `supplier_id` int(30) NOT NULL,
  `amount` float NOT NULL DEFAULT 0,
  `remarks` text DEFAULT NULL,
  `stock_ids` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `return_list`
--

INSERT INTO `return_list` (`id`, `return_code`, `supplier_id`, `amount`, `remarks`, `stock_ids`, `date_created`, `date_updated`) VALUES
(1, 'R-0001', 2, 3025, 'Sample Issue', '16,17', '2021-11-03 13:45:53', '2021-11-03 13:45:53');

-- --------------------------------------------------------

--
-- Table structure for table `sales_list`
--

CREATE TABLE `sales_list` (
  `id` int(30) NOT NULL,
  `sales_code` varchar(50) NOT NULL,
  `client` text DEFAULT NULL,
  `amount` float NOT NULL DEFAULT 0,
  `remarks` text DEFAULT NULL,
  `stock_ids` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sales_list`
--

INSERT INTO `sales_list` (`id`, `sales_code`, `client`, `amount`, `remarks`, `stock_ids`, `date_created`, `date_updated`) VALUES
(1, 'SALE-0001', 'John Smith', 7625, 'Sample Remarks', '24,25,26', '2021-11-03 14:03:30', '2021-11-03 14:08:27');

-- --------------------------------------------------------

--
-- Table structure for table `stock_list`
--

CREATE TABLE `stock_list` (
  `id` int(30) NOT NULL,
  `item_id` int(30) NOT NULL,
  `quantity` int(30) NOT NULL,
  `unit` varchar(250) DEFAULT NULL,
  `price` float NOT NULL DEFAULT 0,
  `total` float NOT NULL DEFAULT current_timestamp(),
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=IN , 2=OUT',
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stock_list`
--

INSERT INTO `stock_list` (`id`, `item_id`, `quantity`, `unit`, `price`, `total`, `type`, `date_created`) VALUES
(1, 1, 250, 'pcs', 150, 37500, 1, '2021-11-03 11:20:38'),
(2, 1, 125, 'pcs', 150, 18750, 1, '2021-11-03 11:20:51'),
(3, 1, 125, 'pcs', 150, 18750, 1, '2021-11-03 11:21:00'),
(4, 2, 150, 'Boxes', 200, 30000, 1, '2021-11-03 11:51:41'),
(5, 4, 150, 'pcs', 205, 30750, 1, '2021-11-03 11:51:41'),
(6, 2, 100, 'Boxes', 200, 20000, 1, '2021-11-03 11:52:02'),
(7, 4, 50, 'pcs', 205, 10250, 1, '2021-11-03 11:52:02'),
(8, 2, 50, 'Boxes', 200, 10000, 1, '2021-11-03 11:52:15'),
(16, 2, 10, 'pcs', 200, 2000, 2, '2021-11-03 13:45:53'),
(17, 4, 5, 'boxes', 205, 1025, 2, '2021-11-03 13:45:53'),
(24, 1, 10, 'pcs', 150, 1500, 2, '2021-11-03 14:08:27'),
(25, 2, 5, 'pcs', 200, 1000, 2, '2021-11-03 14:08:27'),
(26, 4, 25, 'boxes', 205, 5125, 2, '2021-11-03 14:08:27');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_list`
--

CREATE TABLE `supplier_list` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `address` text NOT NULL,
  `cperson` text NOT NULL,
  `contact` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `supplier_list`
--

INSERT INTO `supplier_list` (`id`, `name`, `address`, `cperson`, `contact`, `status`, `date_created`, `date_updated`) VALUES
(1, 'Supplier 101', 'Sample Supplier Address 101', 'Supplier Staff 101', '09123456789', 1, '2021-11-02 09:36:19', '2021-11-02 09:36:19'),
(2, 'Supplier 102', 'Sample Address 102', 'Supplier Staff 102', '0987654332', 1, '2021-11-02 09:36:54', '2021-11-02 09:36:54');

-- --------------------------------------------------------

--
-- Table structure for table `system_info`
--

CREATE TABLE `system_info` (
  `id` int(30) NOT NULL,
  `meta_field` text NOT NULL,
  `meta_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `system_info`
--

INSERT INTO `system_info` (`id`, `meta_field`, `meta_value`) VALUES
(1, 'name', 'Stock Management System - PHP'),
(6, 'short_name', 'SMS- PHP'),
(11, 'logo', 'uploads/logo-1635816671.png'),
(13, 'user_avatar', 'uploads/user_avatar.jpg'),
(14, 'cover', 'uploads/cover-1635816671.png'),
(15, 'content', 'Array');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(50) NOT NULL,
  `firstname` varchar(250) NOT NULL,
  `middlename` text DEFAULT NULL,
  `lastname` varchar(250) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `avatar` text DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `middlename`, `lastname`, `username`, `password`, `avatar`, `last_login`, `type`, `date_added`, `date_updated`) VALUES
(1, 'Adminstrator', NULL, 'Admin', 'admin', '0192023a7bbd73250516f069df18b500', 'uploads/avatar-1.png?v=1635556826', NULL, 1, '2021-01-20 14:02:37', '2021-10-30 09:20:26'),
(10, 'John', NULL, 'Smith', 'jsmith', '39ce7e2a8573b41ce73b5ba41617f8f7', 'uploads/avatar-10.png?v=1635920488', NULL, 2, '2021-11-03 14:21:28', '2021-11-03 14:21:28'),
(11, 'Claire', NULL, 'Blake', 'cblake', 'cd74fae0a3adf459f73bbf187607ccea', 'uploads/avatar-11.png?v=1635920566', NULL, 1, '2021-11-03 14:22:46', '2021-11-03 14:22:46');

-- --------------------------------------------------------

--
-- Table structure for table `user_meta`
--

CREATE TABLE `user_meta` (
  `user_id` int(30) NOT NULL,
  `meta_field` text NOT NULL,
  `meta_value` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `back_order_list`
--
ALTER TABLE `back_order_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `po_id` (`po_id`),
  ADD KEY `receiving_id` (`receiving_id`);

--
-- Indexes for table `bo_items`
--
ALTER TABLE `bo_items`
  ADD KEY `item_id` (`item_id`),
  ADD KEY `bo_id` (`bo_id`);

--
-- Indexes for table `item_list`
--
ALTER TABLE `item_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `po_items`
--
ALTER TABLE `po_items`
  ADD KEY `po_id` (`po_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `purchase_order_list`
--
ALTER TABLE `purchase_order_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `receiving_list`
--
ALTER TABLE `receiving_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `return_list`
--
ALTER TABLE `return_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `sales_list`
--
ALTER TABLE `sales_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_list`
--
ALTER TABLE `stock_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `supplier_list`
--
ALTER TABLE `supplier_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_info`
--
ALTER TABLE `system_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_meta`
--
ALTER TABLE `user_meta`
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `back_order_list`
--
ALTER TABLE `back_order_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `item_list`
--
ALTER TABLE `item_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `purchase_order_list`
--
ALTER TABLE `purchase_order_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `receiving_list`
--
ALTER TABLE `receiving_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `return_list`
--
ALTER TABLE `return_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sales_list`
--
ALTER TABLE `sales_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `stock_list`
--
ALTER TABLE `stock_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `supplier_list`
--
ALTER TABLE `supplier_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `system_info`
--
ALTER TABLE `system_info`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `back_order_list`
--
ALTER TABLE `back_order_list`
  ADD CONSTRAINT `back_order_list_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier_list` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `back_order_list_ibfk_2` FOREIGN KEY (`po_id`) REFERENCES `purchase_order_list` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `back_order_list_ibfk_3` FOREIGN KEY (`receiving_id`) REFERENCES `receiving_list` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bo_items`
--
ALTER TABLE `bo_items`
  ADD CONSTRAINT `bo_items_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item_list` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bo_items_ibfk_2` FOREIGN KEY (`bo_id`) REFERENCES `back_order_list` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `item_list`
--
ALTER TABLE `item_list`
  ADD CONSTRAINT `item_list_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier_list` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `po_items`
--
ALTER TABLE `po_items`
  ADD CONSTRAINT `po_items_ibfk_1` FOREIGN KEY (`po_id`) REFERENCES `purchase_order_list` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `po_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `item_list` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_order_list`
--
ALTER TABLE `purchase_order_list`
  ADD CONSTRAINT `purchase_order_list_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier_list` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `return_list`
--
ALTER TABLE `return_list`
  ADD CONSTRAINT `return_list_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier_list` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_list`
--
ALTER TABLE `stock_list`
  ADD CONSTRAINT `stock_list_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item_list` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
