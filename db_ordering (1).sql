-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 23, 2017 at 11:31 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 7.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_ordering`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_addedstock`
--

CREATE TABLE `tbl_addedstock` (
  `stock_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `date_added` datetime NOT NULL,
  `stockquantity` int(11) NOT NULL,
  `addedby` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_addedstock`
--

INSERT INTO `tbl_addedstock` (`stock_id`, `product_id`, `supplier_id`, `date_added`, `stockquantity`, `addedby`) VALUES
(1, 1, 3, '2017-03-09 07:57:49', 10, 2),
(2, 1, 3, '2017-02-09 07:58:27', 11, 2),
(3, 3, 2, '2017-03-09 08:17:24', 13, 2),
(4, 1, 3, '2017-03-21 14:29:10', 500, 5);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_brand`
--

CREATE TABLE `tbl_brand` (
  `brand_id` int(11) NOT NULL,
  `brandname` varchar(255) NOT NULL,
  `brand_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_brand`
--

INSERT INTO `tbl_brand` (`brand_id`, `brandname`, `brand_status`) VALUES
(1, 'Dunlop 2', 1),
(2, 'Firestones', 1),
(3, 'asdfasdfa 2', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cart`
--

CREATE TABLE `tbl_cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `deliveryaddress` varchar(255) NOT NULL,
  `dateadded` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `delivery_status` varchar(50) NOT NULL,
  `deleteStatus` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_cart`
--

INSERT INTO `tbl_cart` (`cart_id`, `user_id`, `deliveryaddress`, `dateadded`, `delivery_status`, `deleteStatus`) VALUES
(2, 3, 'Labangon, Cebu City', '2017-03-06 05:49:27', 'For delivery', 0),
(3, 3, 'Labangon, Cebu City', '2017-03-13 05:57:32', 'Delivered', 0),
(4, 3, 'Labangon, Cebu City', '2017-03-21 13:49:49', 'Delivered', 0),
(5, 3, 'Labangon, Cebu City', '2017-03-23 17:00:07', 'Delivered', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cartproduct`
--

CREATE TABLE `tbl_cartproduct` (
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_cartproduct`
--

INSERT INTO `tbl_cartproduct` (`cart_id`, `product_id`, `quantity`, `price`) VALUES
(2, 2, 6, '200.00'),
(3, 2, 5, '200.00'),
(4, 1, 1, '200.00'),
(4, 2, 2, '200.00'),
(5, 2, 4, '200.00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_payment`
--

CREATE TABLE `tbl_payment` (
  `payment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `payment_image` varchar(50) NOT NULL,
  `comment` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_payment`
--

INSERT INTO `tbl_payment` (`payment_id`, `user_id`, `cart_id`, `payment_image`, `comment`) VALUES
(1, 3, 2, '1488777456.jpeg', '50% payment'),
(2, 3, 2, '1488777634.jpeg', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product`
--

CREATE TABLE `tbl_product` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `size` varchar(50) NOT NULL,
  `product_type` varchar(50) NOT NULL,
  `made` varchar(50) DEFAULT NULL,
  `price` decimal(20,2) NOT NULL,
  `reorder_level` int(11) DEFAULT NULL,
  `description` text NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '0',
  `product_status` int(11) NOT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `brand_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_product`
--

INSERT INTO `tbl_product` (`product_id`, `product_name`, `supplier_id`, `size`, `product_type`, `made`, `price`, `reorder_level`, `description`, `quantity`, `product_status`, `product_image`, `brand_id`) VALUES
(1, 'Dunlop Tire', 3, '24x24', 'Tubeless', 'China', '200.00', 20, 'dasdfasdf', 520, 1, '1488147705.jpeg', 1),
(2, 'Dunlop Tire B', 2, '24x24', 'Tubeless', 'China', '200.00', 10, 'dasdfasdf', 68, 1, '1488147732.jpeg', 2),
(3, 'test', 2, '24x24', 'Tubeless', 'Japan', '1222.00', 12, '121212', 10, 1, '', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_purchase_order`
--

CREATE TABLE `tbl_purchase_order` (
  `po_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `datecreated` datetime NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_purchase_order_details`
--

CREATE TABLE `tbl_purchase_order_details` (
  `purchase_order_details_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `po_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_setting`
--

CREATE TABLE `tbl_setting` (
  `id` int(11) NOT NULL,
  `setting_name` varchar(50) NOT NULL,
  `value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_setting`
--

INSERT INTO `tbl_setting` (`id`, `setting_name`, `value`) VALUES
(1, 'delivery fee', '120');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_size`
--

CREATE TABLE `tbl_size` (
  `size_id` int(11) NOT NULL,
  `sizename` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_size`
--

INSERT INTO `tbl_size` (`size_id`, `sizename`, `description`) VALUES
(2, '24x24', 'Size from Japan Dunlop tires.');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_supplier`
--

CREATE TABLE `tbl_supplier` (
  `supplier_id` int(11) NOT NULL,
  `suppliername` varchar(50) NOT NULL,
  `address` varchar(255) NOT NULL,
  `contactnumber` varchar(50) NOT NULL,
  `contactname` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_supplier`
--

INSERT INTO `tbl_supplier` (`supplier_id`, `suppliername`, `address`, `contactnumber`, `contactname`) VALUES
(1, 'Mitsubishi', 'Japan', '09323', 'Misu Bishi'),
(2, 'AutoWheel', 'Test, Test', '091212-test', 'Test Contact'),
(3, 'Supplier 1', 'Test Address', 'Contact Number', 'Contact Person');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `user_id` int(10) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `contact_no` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `birthdate` date NOT NULL,
  `gender` varchar(50) NOT NULL,
  `user_type` varchar(50) NOT NULL,
  `status` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`user_id`, `firstname`, `lastname`, `address`, `email`, `contact_no`, `username`, `password`, `birthdate`, `gender`, `user_type`, `status`) VALUES
(2, 'Test', 'Test 2', 'Bulacao, Cebu City', 'test2@gmail.com', '0921-12012121', 'admin', '21232f297a57a5a743894a0e4a801fc3', '2012-01-04', 'Male', 'Admin', 1),
(3, 'Test', 'Test', 'Labangon, Cebu City', 'asdf@gmail.com', '092121232', 'test', '21232f297a57a5a743894a0e4a801fc3', '2017-12-21', 'Male', 'Customer', 1),
(4, 'Test', 'Test', 'Labangon, Cebu City', 'asdf@gmail.com', '092121232', 'test', '6a204bd89f3c8348afd5c77c717a097a', '2017-12-21', 'Male', 'Customer', 0),
(5, 'Staff', 'Account 1', 'Staff Address', 'staff@gmail.com', '0932323', 'staff1', '21232f297a57a5a743894a0e4a801fc3', '1998-12-21', 'Male', 'Staff', 1),
(6, 'Staff', 'Account 2', 'Account 2 Address', 'account2@gmail.com', '09123123', 'staff2', 'a8f5f167f44f4964e6c998dee827110c', '1998-12-21', 'Female', 'Staff', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_addedstock`
--
ALTER TABLE `tbl_addedstock`
  ADD PRIMARY KEY (`stock_id`);

--
-- Indexes for table `tbl_brand`
--
ALTER TABLE `tbl_brand`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indexes for table `tbl_cart`
--
ALTER TABLE `tbl_cart`
  ADD PRIMARY KEY (`cart_id`);

--
-- Indexes for table `tbl_payment`
--
ALTER TABLE `tbl_payment`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `tbl_purchase_order`
--
ALTER TABLE `tbl_purchase_order`
  ADD PRIMARY KEY (`po_id`);

--
-- Indexes for table `tbl_purchase_order_details`
--
ALTER TABLE `tbl_purchase_order_details`
  ADD PRIMARY KEY (`purchase_order_details_id`);

--
-- Indexes for table `tbl_setting`
--
ALTER TABLE `tbl_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_size`
--
ALTER TABLE `tbl_size`
  ADD PRIMARY KEY (`size_id`);

--
-- Indexes for table `tbl_supplier`
--
ALTER TABLE `tbl_supplier`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_addedstock`
--
ALTER TABLE `tbl_addedstock`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tbl_brand`
--
ALTER TABLE `tbl_brand`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tbl_cart`
--
ALTER TABLE `tbl_cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `tbl_payment`
--
ALTER TABLE `tbl_payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tbl_product`
--
ALTER TABLE `tbl_product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tbl_purchase_order_details`
--
ALTER TABLE `tbl_purchase_order_details`
  MODIFY `purchase_order_details_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_setting`
--
ALTER TABLE `tbl_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tbl_size`
--
ALTER TABLE `tbl_size`
  MODIFY `size_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tbl_supplier`
--
ALTER TABLE `tbl_supplier`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
