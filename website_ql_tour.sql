-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2025 at 10:22 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `website_ql_tour`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint(20) NOT NULL,
  `tour_id` bigint(20) DEFAULT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `customer_email` varchar(100) DEFAULT NULL,
  `booking_type` enum('Khách lẻ','Đoàn') DEFAULT NULL,
  `num_people` int(11) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `assigned_guide_id` bigint(20) DEFAULT NULL,
  `status` bigint(20) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `special_requirements` text DEFAULT NULL,
  `schedule_detail` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`schedule_detail`)),
  `service_detail` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`service_detail`)),
  `diary` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`diary`)),
  `lists_file` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`lists_file`)),
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `tour_id`, `created_by`, `assigned_guide_id`, `status`, `start_date`, `end_date`, `schedule_detail`, `service_detail`, `diary`, `lists_file`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2, 1, '2024-07-01', '2024-07-03', '{\"guide\":\"2\"}', '{\"bus\":\"Xe Hùng Mạnh\"}', '{\"entries\":[\"Ngày 1: OK\"]}', '{\"guest_list\":\"guest1.xlsx\"}', 'Booking đoàn A', '2025-11-21 10:34:56', '2025-11-21 10:34:56'),
(2, 2, 1, 2, 2, '2024-08-10', '2024-08-13', '{\"guide\":\"2\"}', '{\"bus\":\"Bus Express\"}', '{\"entries\":[\"Ngày 1: Checkin\"]}', '{\"guest_list\":\"guest2.xlsx\"}', 'Booking đoàn B', '2025-11-21 10:34:56', '2025-11-21 10:34:56');

-- --------------------------------------------------------

--
-- Table structure for table `booking_status_logs`
--

CREATE TABLE `booking_status_logs` (
  `id` bigint(20) NOT NULL,
  `booking_id` bigint(20) DEFAULT NULL,
  `old_status` bigint(20) DEFAULT NULL,
  `new_status` bigint(20) DEFAULT NULL,
  `changed_by` bigint(20) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_status_logs`
--

INSERT INTO `booking_status_logs` (`id`, `booking_id`, `old_status`, `new_status`, `changed_by`, `note`, `changed_at`) VALUES
(1, 1, 1, 2, 1, 'Chuyển sang đã xác nhận', '2025-11-21 10:35:15'),
(2, 2, 1, 2, 2, 'HDV xác nhận tham gia', '2025-11-21 10:35:15');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Tour trong nước', 'Tour du lịch nội địa', 1, '2025-11-21 10:33:01', '2025-11-21 10:33:01'),
(2, 'Tour quốc tế', 'Tour du lịch quốc tế', 1, '2025-11-21 10:33:01', '2025-11-21 10:33:01');

-- --------------------------------------------------------

--
-- Table structure for table `guide_profiles`
--

CREATE TABLE `guide_profiles` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `certificate` text DEFAULT NULL,
  `languages` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`languages`)),
  `experience` text DEFAULT NULL,
  `history` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`history`)),
  `rating` decimal(3,2) DEFAULT NULL,
  `health_status` text DEFAULT NULL,
  `group_type` varchar(50) DEFAULT NULL,
  `speciality` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guide_profiles`
--

INSERT INTO `guide_profiles` (`id`, `user_id`, `birthdate`, `avatar`, `phone`, `certificate`, `languages`, `experience`, `history`, `rating`, `health_status`, `group_type`, `speciality`, `created_at`, `updated_at`) VALUES
(1, 2, '1990-01-01', 'guide1.jpg', '0912345678', '[\"HDV quốc tế\"]', '[\"Tiếng Anh\",\"Tiếng Việt\"]', '5 năm kinh nghiệm', '{\"tours\":[1]}', 4.80, 'Tốt', 'quốc tế', 'chuyên tuyến miền Bắc', '2025-11-21 10:35:29', '2025-11-21 10:35:29'),
(2, 1, '1985-02-02', 'guide2.jpg', '0987654321', '[\"HDV nội địa\"]', '[\"Tiếng Việt\"]', '10 năm kinh nghiệm', '{\"tours\":[2]}', 4.90, 'Khá', 'nội địa', 'chuyên khách đoàn', '2025-11-21 10:35:29', '2025-11-21 10:35:29');

-- --------------------------------------------------------

--
-- Table structure for table `tours`
--

CREATE TABLE `tours` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category_id` bigint(20) DEFAULT NULL,
  `schedule` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`schedule`)),
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `prices` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`prices`)),
  `policies` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`policies`)),
  `suppliers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`suppliers`)),
  `price` decimal(15,2) DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tours`
--

INSERT INTO `tours` (`id`, `name`, `description`, `category_id`, `schedule`, `images`, `prices`, `policies`, `suppliers`, `price`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Tour Hạ Long', 'Khám phá vịnh Hạ Long', 1, '{\"days\":[{\"date\":\"2024-07-01\",\"activities\":[\"Thăm vịnh Hạ Long\",\"Ăn trưa trên tàu\"]}]}', '[\"halong1.jpg\",\"halong2.jpg\"]', '{\"adult\":1500000,\"child\":800000}', '{\"booking\":\"Không hoàn tiền khi hủy trong vòng 48h\"}', '[\"Vinpearl Hotel\",\"Xe Hùng Mạnh\"]', 1500000.00, 1, '2025-11-21 10:33:16', '2025-11-21 10:33:16'),
(2, 'Tour Thái Lan', 'Du lịch Bangkok - Pattaya', 2, '{\"days\":[{\"date\":\"2024-08-10\",\"activities\":[\"Tham quan chùa vàng\",\"Ăn buffet\"]}]}', '[\"thailand1.jpg\",\"thailand2.jpg\"]', '{\"adult\":8000000,\"child\":6000000}', '{\"booking\":\"Hoàn 50% trước 7 ngày\"}', '[\"Bangkok Hotel\",\"Bus Express\"]', 8000000.00, 1, '2025-11-21 10:33:16', '2025-11-21 10:33:16');

-- --------------------------------------------------------

--
-- Table structure for table `tour_statuses`
--

CREATE TABLE `tour_statuses` (
  `id` bigint(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tour_statuses`
--

INSERT INTO `tour_statuses` (`id`, `name`, `description`) VALUES
(1, 'Chờ xác nhận', 'Chưa xác nhận bởi admin/khách'),
(2, 'Đã cọc', 'Khách đã đặt cọc giữ chỗ'),
(3, 'Hoàn tất', 'Tour đã kết thúc thành công'),
(4, 'Hủy', 'Tour đã bị hủy');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) NOT NULL,
  `booking_id` bigint(20) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `gender` enum('Nam','Nữ','Khác') DEFAULT NULL,
  `birth_year` int(11) DEFAULT NULL,
  `id_number` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `payment_status` enum('Chưa thanh toán','Đã cọc','Hoàn tất') DEFAULT 'Chưa thanh toán',
  `special_requirements` text DEFAULT NULL,
  `check_in_status` enum('Chưa check-in','Đã check-in') DEFAULT 'Chưa check-in',
  `room_number` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `booking_id`, `name`, `phone`, `email`, `gender`, `birth_year`, `id_number`, `address`, `payment_status`, `special_requirements`, `check_in_status`, `room_number`) VALUES
(1, 1, 'Nguyễn Văn A', '0901234567', 'a@gmail.com', 'Nam', 1990, '123456789', 'Hà Nội', 'Đã cọc', 'Ăn chay', 'Chưa check-in', NULL),
(2, 1, 'Trần Thị B', '0902345678', 'b@gmail.com', 'Nữ', 1985, '987654321', 'TP.HCM', 'Hoàn tất', NULL, 'Chưa check-in', '101'),
(3, 2, 'Lê Văn C', '0903456789', 'c@gmail.com', 'Nam', 1992, '456789123', 'Đà Nẵng', 'Đã cọc', 'Dị ứng hải sản', 'Chưa check-in', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `departure_schedules`
--

CREATE TABLE `departure_schedules` (
  `id` bigint(20) NOT NULL,
  `tour_id` bigint(20) NOT NULL,
  `departure_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `guide_id` bigint(20) DEFAULT NULL,
  `driver_name` varchar(100) DEFAULT NULL,
  `vehicle_info` varchar(255) DEFAULT NULL,
  `hotel_info` text DEFAULT NULL,
  `max_guests` int(11) DEFAULT 30,
  `current_guests` int(11) DEFAULT 0,
  `status` tinyint(4) DEFAULT 1 COMMENT '1=Sẵn sàng, 2=Đang diễn ra, 3=Hoàn thành, 4=Hủy',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room_assignments`
--

CREATE TABLE `room_assignments` (
  `id` bigint(20) NOT NULL,
  `booking_id` bigint(20) NOT NULL,
  `customer_id` bigint(20) DEFAULT NULL,
  `hotel_name` varchar(255) DEFAULT NULL,
  `room_number` varchar(50) DEFAULT NULL,
  `room_type` varchar(100) DEFAULT NULL,
  `check_in_date` date DEFAULT NULL,
  `check_out_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tour_diaries`
--

CREATE TABLE `tour_diaries` (
  `id` bigint(20) NOT NULL,
  `booking_id` bigint(20) DEFAULT NULL,
  `guide_id` bigint(20) DEFAULT NULL,
  `diary_date` date NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `incidents` text DEFAULT NULL,
  `customer_feedback` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tour_expenses`
--

CREATE TABLE `tour_expenses` (
  `id` bigint(20) NOT NULL,
  `booking_id` bigint(20) NOT NULL,
  `expense_type` varchar(100) NOT NULL COMMENT 'Loại chi phí: xe, khách sạn, ăn uống, vé tham quan...',
  `amount` decimal(15,2) NOT NULL,
  `description` text DEFAULT NULL,
  `expense_date` date DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Admin Demo', 'admin@gmail.com', 'admin@gmail.com', 'admin', 1, '2025-11-21 10:32:39', '2025-11-22 09:21:16'),
(2, 'Guide Demo', 'guide@gmail.com', 'guide@gmail.com', 'guide', 1, '2025-11-21 10:32:39', '2025-11-22 09:21:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `assigned_guide_id` (`assigned_guide_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `booking_status_logs`
--
ALTER TABLE `booking_status_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `old_status` (`old_status`),
  ADD KEY `new_status` (`new_status`),
  ADD KEY `changed_by` (`changed_by`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `tour_diaries`
--
ALTER TABLE `tour_diaries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `guide_id` (`guide_id`);

--
-- Indexes for table `departure_schedules`
--
ALTER TABLE `departure_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`),
  ADD KEY `guide_id` (`guide_id`);

--
-- Indexes for table `room_assignments`
--
ALTER TABLE `room_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `tour_expenses`
--
ALTER TABLE `tour_expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `guide_profiles`
--
ALTER TABLE `guide_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `tours`
--
ALTER TABLE `tours`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `tour_statuses`
--
ALTER TABLE `tour_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `booking_status_logs`
--
ALTER TABLE `booking_status_logs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tour_diaries`
--
ALTER TABLE `tour_diaries`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departure_schedules`
--
ALTER TABLE `departure_schedules`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `room_assignments`
--
ALTER TABLE `room_assignments`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tour_expenses`
--
ALTER TABLE `tour_expenses`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guide_profiles`
--
ALTER TABLE `guide_profiles`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tours`
--
ALTER TABLE `tours`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tour_statuses`
--
ALTER TABLE `tour_statuses`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`assigned_guide_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookings_ibfk_4` FOREIGN KEY (`status`) REFERENCES `tour_statuses` (`id`);

--
-- Constraints for table `booking_status_logs`
--
ALTER TABLE `booking_status_logs`
  ADD CONSTRAINT `booking_status_logs_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`),
  ADD CONSTRAINT `booking_status_logs_ibfk_2` FOREIGN KEY (`old_status`) REFERENCES `tour_statuses` (`id`),
  ADD CONSTRAINT `booking_status_logs_ibfk_3` FOREIGN KEY (`new_status`) REFERENCES `tour_statuses` (`id`),
  ADD CONSTRAINT `booking_status_logs_ibfk_4` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `guide_profiles`
--
ALTER TABLE `guide_profiles`
  ADD CONSTRAINT `guide_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tour_diaries`
--
ALTER TABLE `tour_diaries`
  ADD CONSTRAINT `tour_diaries_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tour_diaries_ibfk_2` FOREIGN KEY (`guide_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `tours`
--
ALTER TABLE `tours`
  ADD CONSTRAINT `tours_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
