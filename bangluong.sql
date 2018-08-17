-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 15, 2018 lúc 11:59 AM
-- Phiên bản máy phục vụ: 10.1.30-MariaDB
-- Phiên bản PHP: 7.2.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `thpt`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admin`
--

CREATE TABLE `admin` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin', '$2y$10$ABLaWJ0iRx4pZrrxtQFlluGsF6iz8CVHuU1VH6gpJSwFX9Z0ZYlS.', 'EbMYxlXubideQL7YwTLWNhFACaqr1EOEAMHGppAg8SvMhF4yEsCBULRAbdSI', '2018-03-26 23:59:56', '2018-03-26 23:59:56');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `congnv`
--

CREATE TABLE `congnv` (
  `id` int(255) NOT NULL,
  `ma_nv` varchar(11) NOT NULL,
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL,
  `thoi_gian` date NOT NULL,
  `sum_time` time DEFAULT NULL,
  `tang_ca` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Đang đổ dữ liệu cho bảng `congnv`
--

INSERT INTO `congnv` (`id`, `ma_nv`, `time_in`, `time_out`, `thoi_gian`, `sum_time`, `tang_ca`) VALUES
(1, 'NV0027', '08:30:00', '21:03:00', '2018-04-01', '12:33:00', '03:03:00'),
(2, 'NV0027', '08:30:00', '19:25:00', '2018-04-02', '10:55:00', '01:25:00'),
(3, 'NV0027', '08:31:00', '12:34:00', '2018-04-03', '04:03:00', '-05:27:00'),
(4, 'NV0027', '08:26:00', '18:54:00', '2018-04-04', '10:28:00', NULL),
(5, 'NV0027', '08:30:00', '19:14:00', '2018-04-05', '10:44:00', '01:14:00'),
(6, 'NV0027', '08:33:00', '21:54:00', '2018-04-06', '13:21:00', '03:51:00'),
(7, 'NV0027', '12:01:00', '23:02:00', '2018-04-07', '11:01:00', '01:31:00'),
(8, 'NV0027', '08:32:00', '19:07:00', '2018-04-08', '10:35:00', '01:05:00'),
(9, 'NV0027', '18:07:00', NULL, '2018-04-09', NULL, NULL),
(10, 'NV0027', '12:09:00', '20:16:00', '2018-04-10', '08:07:00', '-01:23:00'),
(11, 'NV0027', '08:30:00', '18:23:00', '2018-04-11', '09:53:00', NULL),
(12, 'NV0027', '08:30:00', '22:30:00', '2018-04-12', '14:00:00', '04:30:00'),
(13, 'NV0027', '08:53:00', '20:15:00', '2018-04-13', '11:22:00', '01:52:00'),
(14, 'NV0027', '08:37:00', '19:06:00', '2018-04-14', '10:29:00', NULL),
(15, 'NV0027', '08:32:00', '21:19:00', '2018-04-15', '12:47:00', '03:17:00'),
(16, 'NV0027', '08:30:00', '18:10:00', '2018-04-16', '09:40:00', NULL),
(17, 'NV0027', '08:31:00', NULL, '2018-04-17', NULL, NULL),
(18, 'NV0019', '11:55:00', NULL, '2018-04-01', NULL, NULL),
(19, 'NV0019', '11:01:00', '21:11:00', '2018-04-02', '10:10:00', NULL),
(20, 'NV0019', '12:12:00', NULL, '2018-04-03', NULL, NULL),
(21, 'NV0019', '11:57:00', '21:25:00', '2018-04-04', '09:28:00', '00:00:00'),
(22, 'NV0019', '11:40:00', '21:14:00', '2018-04-05', '09:34:00', NULL),
(23, 'NV0019', '11:59:00', '21:18:00', '2018-04-06', '09:19:00', '00:00:00'),
(24, 'NV0019', '11:19:00', '21:11:00', '2018-04-07', '09:52:00', NULL),
(25, 'NV0019', '11:44:00', NULL, '2018-04-08', NULL, NULL),
(26, 'NV0019', '21:17:00', NULL, '2018-04-09', NULL, NULL),
(27, 'NV0019', '11:54:00', '21:32:00', '2018-04-10', '09:38:00', NULL),
(28, 'NV0019', '11:55:00', '18:18:00', '2018-04-11', '06:23:00', '-03:07:00'),
(29, 'NV0019', '11:44:00', NULL, '2018-04-12', NULL, NULL),
(30, 'NV0019', '12:28:00', '21:43:00', '2018-04-13', '09:15:00', '-00:15:00'),
(31, 'NV0019', '12:06:00', '21:24:00', '2018-04-14', '09:18:00', '-00:12:00'),
(32, 'NV0019', '12:01:00', '21:06:00', '2018-04-15', '09:05:00', '-00:25:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhanvien`
--

CREATE TABLE `nhanvien` (
  `id` int(11) NOT NULL,
  `ten_nv` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `sdt` varchar(255) NOT NULL,
  `vitri_id` int(11) NOT NULL,
  `phongban_id` int(11) NOT NULL,
  `ma_nv` varchar(255) DEFAULT NULL,
  `dia_chi` varchar(255) NOT NULL,
  `ngay_vao` date NOT NULL,
  `luong_cung` int(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Đang đổ dữ liệu cho bảng `nhanvien`
--

INSERT INTO `nhanvien` (`id`, `ten_nv`, `sdt`, `vitri_id`, `phongban_id`, `ma_nv`, `dia_chi`, `ngay_vao`, `luong_cung`, `updated_at`, `created_at`) VALUES
(1, 'Dam Bao', '01659444980', 2, 1, 'NV0027', '200 Quang Trung', '2018-04-15', 5000000, '2018-04-15 08:54:30', '2018-04-15 08:54:30'),
(2, 'Tô Nguyên', '01659444980', 2, 1, 'NV0019', '200 Quang Trung', '2018-04-11', 6000000, '2018-04-15 08:55:11', '2018-04-15 08:55:11');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phongban`
--

CREATE TABLE `phongban` (
  `id` int(11) NOT NULL,
  `ten_phongban` varchar(255) CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Đang đổ dữ liệu cho bảng `phongban`
--

INSERT INTO `phongban` (`id`, `ten_phongban`) VALUES
(1, 'Bán Hàng');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thuongnv`
--

CREATE TABLE `thuongnv` (
  `id` int(255) NOT NULL,
  `ma_nv` varchar(255) DEFAULT NULL,
  `tien_thuong` varchar(255) DEFAULT NULL,
  `tien_phat` int(255) DEFAULT NULL,
  `thang_thuong` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Đang đổ dữ liệu cho bảng `thuongnv`
--

INSERT INTO `thuongnv` (`id`, `ma_nv`, `tien_thuong`, `tien_phat`, `thang_thuong`) VALUES
(1, 'NV0027', '2000000', 1000000, '2018-04-01'),
(2, 'NV0019', '2000000', 1000000, '2018-04-02');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vitri`
--

CREATE TABLE `vitri` (
  `id_vitri` int(11) NOT NULL,
  `ten_vitri` varchar(255) CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Đang đổ dữ liệu cho bảng `vitri`
--

INSERT INTO `vitri` (`id_vitri`, `ten_vitri`) VALUES
(1, 'Thử Việc'),
(2, 'Nhân viên'),
(3, 'Trưởng Phòng');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Chỉ mục cho bảng `congnv`
--
ALTER TABLE `congnv`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Chỉ mục cho bảng `phongban`
--
ALTER TABLE `phongban`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `thuongnv`
--
ALTER TABLE `thuongnv`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `vitri`
--
ALTER TABLE `vitri`
  ADD PRIMARY KEY (`id_vitri`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `congnv`
--
ALTER TABLE `congnv`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `nhanvien`
--
ALTER TABLE `nhanvien`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `phongban`
--
ALTER TABLE `phongban`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `thuongnv`
--
ALTER TABLE `thuongnv`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `vitri`
--
ALTER TABLE `vitri`
  MODIFY `id_vitri` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
