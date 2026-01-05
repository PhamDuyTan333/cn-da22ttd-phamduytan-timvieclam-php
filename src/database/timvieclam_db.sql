-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th1 05, 2026 lúc 03:12 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `timvieclam_db`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cauhinh`
--

CREATE TABLE `cauhinh` (
  `id` int(11) NOT NULL,
  `ten_config` varchar(100) NOT NULL COMMENT 'Tên cấu hình',
  `gia_tri` text NOT NULL COMMENT 'Giá trị',
  `kieu_dulieu` varchar(20) DEFAULT 'string' COMMENT 'Kiểu dữ liệu: string, int, bool, json',
  `mota` text DEFAULT NULL COMMENT 'Mô tả',
  `nhom` varchar(50) DEFAULT 'general' COMMENT 'Nhóm: general, upload, tin, email'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `cauhinh`
--

INSERT INTO `cauhinh` (`id`, `ten_config`, `gia_tri`, `kieu_dulieu`, `mota`, `nhom`) VALUES
(1, 'max_cv_size', '5242880', 'int', 'Giới hạn dung lượng CV (bytes) - Mặc định: 5MB', 'upload'),
(2, 'allowed_cv_types', 'pdf,doc,docx', 'string', 'Định dạng CV cho phép', 'upload'),
(3, 'tin_expire_days', '30', 'int', 'Thời hạn tin (ngày) - Mặc định: 30 ngày', 'tin'),
(4, 'max_tin_per_employer', '50', 'int', 'Số tin tối đa/nhà tuyển dụng', 'tin');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chatbot_conversations`
--

CREATE TABLE `chatbot_conversations` (
  `id` int(11) NOT NULL,
  `session_id` varchar(100) NOT NULL,
  `nguoidung_id` int(11) DEFAULT NULL,
  `ngaytao` datetime DEFAULT current_timestamp(),
  `ngaycapnhat` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chatbot_conversations`
--

INSERT INTO `chatbot_conversations` (`id`, `session_id`, `nguoidung_id`, `ngaytao`, `ngaycapnhat`) VALUES
(12, 'chat_1763623160579_9ldz5wyw1', 8, '2025-11-20 14:20:19', '2025-11-20 14:20:19'),
(16, 'chat_6938cd54adf551.97204961', 7, '2025-12-10 08:31:10', '2025-12-10 08:31:10'),
(18, 'chat_1766921598946_6nph5lmom', 18, '2025-12-28 18:37:16', '2025-12-28 18:37:16');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chatbot_messages`
--

CREATE TABLE `chatbot_messages` (
  `id` int(11) NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `session_id` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `response` text NOT NULL,
  `message_type` enum('user','bot') NOT NULL,
  `nguoidung_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chatbot_messages`
--

INSERT INTO `chatbot_messages` (`id`, `conversation_id`, `session_id`, `message`, `response`, `message_type`, `nguoidung_id`, `created_at`) VALUES
(61, 12, 'chat_1763623160579_9ldz5wyw1', 'tìm việc làm ở Vĩnh Long', '', 'user', 8, '2025-11-20 14:20:19'),
(62, 12, 'chat_1763623160579_9ldz5wyw1', '', '{\"type\":\"jobs\",\"message\":\"Tìm thấy 5 việc làm tại Vĩnh Long:\",\"jobs\":[{\"id\":13,\"tieude\":\"Tuyển dụng người thử nghiệm phần mềm\",\"tennganh\":\"Công nghệ thông tin\",\"tentinh\":\"Vĩnh Long\",\"tenmucluong\":\"Dưới 5 triệu\",\"tencongty\":\"Công ty TNHH ABC\",\"tenloai\":\"Làm từ xa\"},{\"id\":12,\"tieude\":\"Part-time Developer cuối tuần\",\"tennganh\":\"Công nghệ thông tin\",\"tentinh\":\"Vĩnh Long\",\"tenmucluong\":\"5 - 10 triệu\",\"tencongty\":\"Công ty TNHH ABC\",\"tenloai\":\"Bán thời gian\"},{\"id\":10,\"tieude\":\"Tuyển kỹ sư xây dựng\",\"tennganh\":\"Xây dựng\",\"tentinh\":\"Vĩnh Long\",\"tenmucluong\":\"15 - 20 triệu\",\"tencongty\":\"Công ty Cổ phần XYZ\",\"tenloai\":\"Toàn thời gian\"},{\"id\":6,\"tieude\":\"Kế toán tổng hợp\",\"tennganh\":\"Kế toán - Kiểm toán\",\"tentinh\":\"Vĩnh Long\",\"tenmucluong\":\"10 - 15 triệu\",\"tencongty\":\"Công ty Cổ phần XYZ\",\"tenloai\":\"Toàn thời gian\"},{\"id\":3,\"tieude\":\"Thực tập sinh lập trình Web\",\"tennganh\":\"Công nghệ thông tin\",\"tentinh\":\"Vĩnh Long\",\"tenmucluong\":\"Dưới 5 triệu\",\"tencongty\":\"Công ty TNHH ABC\",\"tenloai\":\"Thực tập\"}],\"footer\":\"Nhấn vào công việc để xem chi tiết và ứng tuyển ngay!\"}', 'bot', 8, '2025-11-20 14:20:19'),
(71, 16, 'chat_6938cd54adf551.97204961', 'tìm việc làm gần tôi', '', 'user', 7, '2025-12-10 08:31:10'),
(72, 16, 'chat_6938cd54adf551.97204961', 'tìm việc làm gần tôi', '{\"type\":\"jobs\",\"message\":\"Tìm thấy 4 việc làm gần Quận 1, TP.Hồ Chí Minh:\",\"jobs\":[{\"id\":11,\"nguoidung_id\":5,\"tieude\":\"Developer làm việc từ xa (Remote)\",\"nganhnghe_id\":1,\"mucluong_id\":5,\"loaicongviec_id\":4,\"tinhthanh_id\":1,\"diachilamviec\":\"Làm việc từ xa (Remote) - Trụ sở: 321 Trần Hưng Đạo, Phường Nguyễn Thái Bình, Quận 1, TP. Hồ Chí Minh\",\"soluong\":2,\"gioitinh_yc\":\"khongphanbiet\",\"mota\":\"Cho phép làm việc 100% remote, chỉ cần internet và laptop.\",\"yeucau\":\"- Có kinh nghiệm làm việc remote\\n- Tự giác, chủ động\\n- Kỹ năng giao tiếp online tốt\\n- Thành thạo công cụ làm việc nhóm\",\"quyenloi\":\"- Lương: 20-30 triệu\\n- Làm việc linh hoạt\\n- Trang thiết bị hỗ trợ\\n- Team building online\",\"ngayhethan\":\"2025-12-31\",\"trangthai\":\"dangmo\",\"luotxem\":4,\"ngaydang\":\"2025-10-28 09:00:00\",\"ngaycapnhat\":\"2025-12-10 07:56:48\",\"tennganh\":\"Công nghệ thông tin\",\"tentinh\":\"TP.Hồ Chí Minh\",\"tenloai\":\"Làm từ xa\",\"tenmucluong\":\"20 - 25 triệu\",\"hoten\":\"Công ty Tech Solutions\",\"tencongty\":\"Công ty Tech Solutions\",\"logo\":null},{\"id\":8,\"nguoidung_id\":4,\"tieude\":\"Nhân viên kinh doanh B2B\",\"nganhnghe_id\":4,\"mucluong_id\":2,\"loaicongviec_id\":1,\"tinhthanh_id\":1,\"diachilamviec\":\"321 Trần Hưng Đạo, Phường Nguyễn Thái Bình, Quận 1, TP. Hồ Chí Minh\",\"soluong\":5,\"gioitinh_yc\":\"khongphanbiet\",\"mota\":\"Tuyển nhân viên kinh doanh để phát triển khách hàng doanh nghiệp.\",\"yeucau\":\"- Có kinh nghiệm bán hàng B2B\\n- Kỹ năng giao tiếp, đàm phán tốt\\n- Ham học hỏi, chịu được áp lực\\n- Có phương tiện đi lại\",\"quyenloi\":\"- Lương: 8-12 triệu + hoa hồng hấp dẫn\\n- Không trần thu nhập\\n- Thưởng KPI\\n- Được đào tạo kỹ năng\",\"ngayhethan\":\"2025-12-31\",\"trangthai\":\"dangmo\",\"luotxem\":0,\"ngaydang\":\"2025-10-20 08:30:00\",\"ngaycapnhat\":\"2025-12-10 07:42:52\",\"tennganh\":\"Kinh doanh - Bán hàng\",\"tentinh\":\"TP.Hồ Chí Minh\",\"tenloai\":\"Toàn thời gian\",\"tenmucluong\":\"5 - 10 triệu\",\"hoten\":\"Tập đoàn DEF\",\"tencongty\":\"Tập đoàn DEF\",\"logo\":null},{\"id\":7,\"nguoidung_id\":4,\"tieude\":\"Nhân viên tuyển dụng (Recruiter)\",\"nganhnghe_id\":5,\"mucluong_id\":3,\"loaicongviec_id\":1,\"tinhthanh_id\":1,\"diachilamviec\":\"321 Trần Hưng Đạo, Phường Nguyễn Thái Bình, Quận 1, TP. Hồ Chí Minh\",\"soluong\":2,\"gioitinh_yc\":\"khongphanbiet\",\"mota\":\"Tuyển Recruiter để mở rộng đội ngũ tuyển dụng, phụ trách tuyển dụng cho các vị trí IT.\",\"yeucau\":\"- Tốt nghiệp chuyên ngành Quản trị nhân lực\\n- Có kinh nghiệm tuyển dụng IT là lợi thế\\n- Kỹ năng giao tiếp tốt\\n- Thành thạo các kênh tuyển dụng\",\"quyenloi\":\"- Lương: 10-15 triệu + thưởng\\n- Bảo hiểm đầy đủ\\n- Được đào tạo\\n- Môi trường năng động\",\"ngayhethan\":\"2025-12-31\",\"trangthai\":\"dangmo\",\"luotxem\":0,\"ngaydang\":\"2025-10-18 09:00:00\",\"ngaycapnhat\":\"2025-12-10 07:42:52\",\"tennganh\":\"Nhân sự\",\"tentinh\":\"TP.Hồ Chí Minh\",\"tenloai\":\"Toàn thời gian\",\"tenmucluong\":\"10 - 15 triệu\",\"hoten\":\"Tập đoàn DEF\",\"tencongty\":\"Tập đoàn DEF\",\"logo\":null},{\"id\":2,\"nguoidung_id\":5,\"tieude\":\"Cần tuyển Senior Full-stack Developer\",\"nganhnghe_id\":1,\"mucluong_id\":7,\"loaicongviec_id\":1,\"tinhthanh_id\":1,\"diachilamviec\":\"321 Trần Hưng Đạo, Phường Nguyễn Thái Bình, Quận 1, TP. Hồ Chí Minh\",\"soluong\":2,\"gioitinh_yc\":\"khongphanbiet\",\"mota\":\"Tìm kiếm Senior Developer có kinh nghiệm để dẫn dắt team phát triển sản phẩm công nghệ mới.\",\"yeucau\":\"- Trên 3 năm kinh nghiệm Full-stack\\n- Thành thạo React, Node.js\\n- Có kinh nghiệm làm việc với Cloud (AWS/Azure)\\n- Kỹ năng leadership\",\"quyenloi\":\"- Lương: 30 triệu trở lên\\n- Stock option\\n- Bảo hiểm cao cấp\\n- Làm việc hybrid\\n- Cơ hội thăng tiến\",\"ngayhethan\":\"2025-12-31\",\"trangthai\":\"dangmo\",\"luotxem\":2,\"ngaydang\":\"2025-10-05 09:00:00\",\"ngaycapnhat\":\"2025-12-10 07:42:52\",\"tennganh\":\"Công nghệ thông tin\",\"tentinh\":\"TP.Hồ Chí Minh\",\"tenloai\":\"Toàn thời gian\",\"tenmucluong\":\"Trên 30 triệu\",\"hoten\":\"Công ty Tech Solutions\",\"tencongty\":\"Công ty Tech Solutions\",\"logo\":null}]}', 'bot', 7, '2025-12-10 08:31:10'),
(77, 16, 'chat_6938cd54adf551.97204961', 'tìm việc làm gần tôi', '', 'user', 7, '2025-12-28 10:32:05'),
(78, 16, 'chat_6938cd54adf551.97204961', 'tìm việc làm gần tôi', '{\"type\":\"jobs\",\"message\":\"Tìm thấy 4 việc làm gần Quận 1, TP.Hồ Chí Minh:\",\"jobs\":[{\"id\":11,\"nguoidung_id\":5,\"tieude\":\"Developer làm việc từ xa (Remote)\",\"nganhnghe_id\":1,\"mucluong_id\":5,\"loaicongviec_id\":4,\"tinhthanh_id\":1,\"diachilamviec\":\"Làm việc từ xa (Remote) - Trụ sở: 321 Trần Hưng Đạo, Phường Nguyễn Thái Bình, Quận 1, TP. Hồ Chí Minh\",\"soluong\":2,\"gioitinh_yc\":\"khongphanbiet\",\"mota\":\"Cho phép làm việc 100% remote, chỉ cần internet và laptop.\",\"yeucau\":\"- Có kinh nghiệm làm việc remote\\n- Tự giác, chủ động\\n- Kỹ năng giao tiếp online tốt\\n- Thành thạo công cụ làm việc nhóm\",\"quyenloi\":\"- Lương: 20-30 triệu\\n- Làm việc linh hoạt\\n- Trang thiết bị hỗ trợ\\n- Team building online\",\"ngayhethan\":\"2025-12-31\",\"trangthai\":\"dangmo\",\"luotxem\":4,\"ngaydang\":\"2025-10-28 09:00:00\",\"ngaycapnhat\":\"2025-12-10 07:56:48\",\"tennganh\":\"Công nghệ thông tin\",\"tentinh\":\"TP.Hồ Chí Minh\",\"tenloai\":\"Làm từ xa\",\"tenmucluong\":\"20 - 25 triệu\",\"hoten\":\"Công ty Tech Solutions\",\"tencongty\":\"Công ty Tech Solutions\",\"logo\":null},{\"id\":8,\"nguoidung_id\":4,\"tieude\":\"Nhân viên kinh doanh B2B\",\"nganhnghe_id\":4,\"mucluong_id\":2,\"loaicongviec_id\":1,\"tinhthanh_id\":1,\"diachilamviec\":\"321 Trần Hưng Đạo, Phường Nguyễn Thái Bình, Quận 1, TP. Hồ Chí Minh\",\"soluong\":5,\"gioitinh_yc\":\"khongphanbiet\",\"mota\":\"Tuyển nhân viên kinh doanh để phát triển khách hàng doanh nghiệp.\",\"yeucau\":\"- Có kinh nghiệm bán hàng B2B\\n- Kỹ năng giao tiếp, đàm phán tốt\\n- Ham học hỏi, chịu được áp lực\\n- Có phương tiện đi lại\",\"quyenloi\":\"- Lương: 8-12 triệu + hoa hồng hấp dẫn\\n- Không trần thu nhập\\n- Thưởng KPI\\n- Được đào tạo kỹ năng\",\"ngayhethan\":\"2025-12-31\",\"trangthai\":\"dangmo\",\"luotxem\":1,\"ngaydang\":\"2025-10-20 08:30:00\",\"ngaycapnhat\":\"2025-12-25 16:59:24\",\"tennganh\":\"Kinh doanh - Bán hàng\",\"tentinh\":\"TP.Hồ Chí Minh\",\"tenloai\":\"Toàn thời gian\",\"tenmucluong\":\"5 - 10 triệu\",\"hoten\":\"Tập đoàn DEF\",\"tencongty\":\"Tập đoàn DEF\",\"logo\":null},{\"id\":7,\"nguoidung_id\":4,\"tieude\":\"Nhân viên tuyển dụng (Recruiter)\",\"nganhnghe_id\":5,\"mucluong_id\":3,\"loaicongviec_id\":1,\"tinhthanh_id\":1,\"diachilamviec\":\"321 Trần Hưng Đạo, Phường Nguyễn Thái Bình, Quận 1, TP. Hồ Chí Minh\",\"soluong\":2,\"gioitinh_yc\":\"khongphanbiet\",\"mota\":\"Tuyển Recruiter để mở rộng đội ngũ tuyển dụng, phụ trách tuyển dụng cho các vị trí IT.\",\"yeucau\":\"- Tốt nghiệp chuyên ngành Quản trị nhân lực\\n- Có kinh nghiệm tuyển dụng IT là lợi thế\\n- Kỹ năng giao tiếp tốt\\n- Thành thạo các kênh tuyển dụng\",\"quyenloi\":\"- Lương: 10-15 triệu + thưởng\\n- Bảo hiểm đầy đủ\\n- Được đào tạo\\n- Môi trường năng động\",\"ngayhethan\":\"2025-12-31\",\"trangthai\":\"dangmo\",\"luotxem\":0,\"ngaydang\":\"2025-10-18 09:00:00\",\"ngaycapnhat\":\"2025-12-10 07:42:52\",\"tennganh\":\"Nhân sự\",\"tentinh\":\"TP.Hồ Chí Minh\",\"tenloai\":\"Toàn thời gian\",\"tenmucluong\":\"10 - 15 triệu\",\"hoten\":\"Tập đoàn DEF\",\"tencongty\":\"Tập đoàn DEF\",\"logo\":null},{\"id\":2,\"nguoidung_id\":5,\"tieude\":\"Cần tuyển Senior Full-stack Developer\",\"nganhnghe_id\":1,\"mucluong_id\":7,\"loaicongviec_id\":1,\"tinhthanh_id\":1,\"diachilamviec\":\"321 Trần Hưng Đạo, Phường Nguyễn Thái Bình, Quận 1, TP. Hồ Chí Minh\",\"soluong\":2,\"gioitinh_yc\":\"khongphanbiet\",\"mota\":\"Tìm kiếm Senior Developer có kinh nghiệm để dẫn dắt team phát triển sản phẩm công nghệ mới.\",\"yeucau\":\"- Trên 3 năm kinh nghiệm Full-stack\\n- Thành thạo React, Node.js\\n- Có kinh nghiệm làm việc với Cloud (AWS/Azure)\\n- Kỹ năng leadership\",\"quyenloi\":\"- Lương: 30 triệu trở lên\\n- Stock option\\n- Bảo hiểm cao cấp\\n- Làm việc hybrid\\n- Cơ hội thăng tiến\",\"ngayhethan\":\"2025-12-31\",\"trangthai\":\"dangmo\",\"luotxem\":2,\"ngaydang\":\"2025-10-05 09:00:00\",\"ngaycapnhat\":\"2025-12-10 07:42:52\",\"tennganh\":\"Công nghệ thông tin\",\"tentinh\":\"TP.Hồ Chí Minh\",\"tenloai\":\"Toàn thời gian\",\"tenmucluong\":\"Trên 30 triệu\",\"hoten\":\"Công ty Tech Solutions\",\"tencongty\":\"Công ty Tech Solutions\",\"logo\":null}]}', 'bot', 7, '2025-12-28 10:32:05'),
(79, 18, 'chat_1766921598946_6nph5lmom', 'tìm việc làm gần tôi', '', 'user', 18, '2025-12-28 18:37:16'),
(80, 18, 'chat_1766921598946_6nph5lmom', 'tìm việc làm gần tôi', '{\"type\":\"text\",\"message\":\"Hiện tại chưa có việc làm nào gần bạn tại Cà Mau.\\n\\nGợi ý:\\n• Thử mở rộng khu vực tìm kiếm\\n• Tìm việc theo ngành nghề\\n• Xem các tỉnh/thành phố khác\",\"suggestions\":[\"Tìm việc IT\",\"Địa điểm\",\"Ngành nghề\",\"Giúp đỡ\"]}', 'bot', 18, '2025-12-28 18:37:16');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `donungtuyen`
--

CREATE TABLE `donungtuyen` (
  `id` int(11) NOT NULL,
  `tintuyendung_id` int(11) NOT NULL,
  `nguoidung_id` int(11) NOT NULL COMMENT 'ID ứng viên',
  `cv_file` varchar(255) NOT NULL,
  `thuungtuyen` text DEFAULT NULL,
  `trangthai` enum('moi','dangxem','phongvan','nhanviec','tuchoi') DEFAULT 'moi',
  `ghichu` text DEFAULT NULL COMMENT 'Ghi chú của nhà tuyển dụng',
  `ngaynop` datetime DEFAULT current_timestamp(),
  `ngaycapnhat` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `donungtuyen`
--

INSERT INTO `donungtuyen` (`id`, `tintuyendung_id`, `nguoidung_id`, `cv_file`, `thuungtuyen`, `trangthai`, `ghichu`, `ngaynop`, `ngaycapnhat`) VALUES
(1, 1, 7, 'cv_nguyenvana.pdf', 'Kính gửi Quý công ty,\n\nTôi là Nguyễn Văn A, đã có 2 năm kinh nghiệm làm việc với PHP Laravel. Tôi rất quan tâm đến vị trí Lập trình viên PHP tại công ty và tin rằng mình phù hợp với yêu cầu công việc.\n\nRất mong được cơ hội trao đổi thêm.\n\nTrân trọng!', 'moi', NULL, '2025-10-03 10:00:00', '2025-11-06 17:38:40'),
(2, 1, 13, 'cv_dovanG.pdf', 'Kính gửi Ban tuyển dụng,\n\nTôi là Đỗ Văn G với 4 năm kinh nghiệm phát triển web. Tôi thành thạo PHP, Laravel và nhiều công nghệ web khác. Mong muốn được đóng góp vào sự phát triển của công ty.', 'dangxem', '', '2025-10-05 14:30:00', '2025-12-01 09:56:56'),
(3, 2, 13, 'cv_senior.pdf', 'Chào team Tech Solutions,\n\nTôi có hơn 4 năm kinh nghiệm với full-stack development. Đã từng dẫn dắt team nhỏ và làm việc với các công nghệ cloud. Rất hào hứng với vị trí này!', 'phongvan', NULL, '2025-10-07 09:15:00', '2025-11-06 17:38:40'),
(4, 3, 14, 'cv_intern.pdf', 'Kính gửi Anh/Chị,\n\nTôi là sinh viên năm cuối ngành CNTT, đang tìm kiếm cơ hội thực tập. Tôi đã học và thực hành HTML, CSS, JavaScript. Mong được học hỏi thêm từ các anh chị.', 'moi', NULL, '2025-10-12 11:20:00', '2025-11-06 17:38:40'),
(5, 4, 8, 'cv_marketing.pdf', 'Xin chào,\n\nTôi là Trần Thị B, có 1 năm kinh nghiệm marketing online. Tôi đã quản lý fanpage 50k followers và chạy ads cho nhiều chiến dịch thành công. Rất mong được làm việc tại công ty.', 'moi', NULL, '2025-10-10 16:45:00', '2025-11-08 09:13:01'),
(6, 5, 8, 'cv_content.pdf', 'Kính gửi team Marketing Pro,\n\nTôi là người yêu thích sáng tạo nội dung. Portfolio của tôi bao gồm nhiều bài viết và thiết kế đã được publish. Mong được đóng góp ý tưởng mới cho công ty.', 'moi', NULL, '2025-10-14 08:30:00', '2025-11-06 17:38:40'),
(7, 6, 9, 'cv_ketoan.pdf', 'Kính gửi Phòng nhân sự,\n\nTôi tốt nghiệp chuyên ngành Kế toán với 3 năm kinh nghiệm. Thành thạo Excel và các phần mềm kế toán. Đã có chứng chỉ kế toán trưởng. Rất mong được phỏng vấn.', 'moi', '', '2025-10-17 13:00:00', '2025-11-08 09:21:39'),
(8, 7, 10, 'cv_hr.pdf', 'Xin chào,\n\nTôi đã có 6 tháng kinh nghiệm trong lĩnh vực tuyển dụng. Tôi thành thạo các kênh tuyển dụng online và có khả năng đánh giá ứng viên tốt. Mong được cơ hội phát triển cùng công ty.', 'moi', NULL, '2025-10-20 10:15:00', '2025-11-08 09:13:01'),
(9, 8, 11, 'cv_sales.pdf', 'Kính gửi Ban giám đốc,\n\nVới 2 năm kinh nghiệm bán hàng B2B, tôi đã đạt được nhiều hợp đồng lớn. Tôi tin vào khả năng phát triển thị trường và mang lại doanh thu cho công ty.', 'moi', NULL, '2025-10-22 15:20:00', '2025-11-08 09:13:01'),
(10, 9, 12, 'cv_designer.pdf', 'Chào anh/chị,\n\nTôi là designer với 1 năm kinh nghiệm. Portfolio của tôi bao gồm nhiều dự án về branding, social media và marketing materials. Mong được làm việc trong môi trường sáng tạo.', 'tuchoi', NULL, '2025-10-24 09:40:00', '2025-11-06 17:38:40'),
(11, 11, 7, 'cv_remote.pdf', 'Kính gửi Quý công ty,\n\nTôi rất quan tâm đến vị trí remote developer. Tôi đã có kinh nghiệm làm việc từ xa và quản lý thời gian hiệu quả. Có khả năng giao tiếp tốt qua các công cụ online.', 'dangxem', NULL, '2025-10-30 14:00:00', '2025-12-03 09:18:23'),
(12, 12, 15, 'cv_parttime.pdf', 'Xin chào,\n\nTôi là sinh viên năm cuối, đang tìm công việc part-time để tích lũy kinh nghiệm. Tôi có thể làm việc linh hoạt vào cuối tuần và học hỏi nhanh.', 'moi', NULL, '2025-11-03 11:30:00', '2025-11-08 09:13:01'),
(13, 12, 17, '690c7c440d1be_1762425924.pdf', 'hhh', 'moi', '', '2025-11-06 17:45:24', '2025-12-28 10:29:50'),
(14, 13, 18, '6916e423eeb3f_1763107875.pdf', 'abc', 'phongvan', '', '2025-11-14 15:11:15', '2025-11-14 15:16:00'),
(15, 13, 7, '694d0b3446575_1766656820.pdf', 'h', 'phongvan', '', '2025-12-25 17:00:20', '2025-12-25 17:02:36'),
(16, 12, 7, '6950a18c6f6e1_1766891916.pdf', 'hi', 'moi', '', '2025-12-28 10:20:21', '2025-12-28 10:26:51');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `loaicongviec`
--

CREATE TABLE `loaicongviec` (
  `id` int(11) NOT NULL,
  `tenloai` varchar(255) NOT NULL,
  `mota` text DEFAULT NULL,
  `trangthai` enum('hoatdong','an') DEFAULT 'hoatdong',
  `ngaytao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `loaicongviec`
--

INSERT INTO `loaicongviec` (`id`, `tenloai`, `mota`, `trangthai`, `ngaytao`) VALUES
(1, 'Toàn thời gian', 'Làm việc full-time', 'hoatdong', '2025-11-06 17:38:39'),
(2, 'Bán thời gian', 'Làm việc part-time', 'hoatdong', '2025-11-06 17:38:39'),
(3, 'Thực tập', 'Sinh viên thực tập', 'hoatdong', '2025-11-06 17:38:39'),
(4, 'Làm từ xa', 'Remote, làm việc từ xa', 'hoatdong', '2025-11-06 17:38:39'),
(5, 'Theo dự án', 'Làm việc theo dự án', 'hoatdong', '2025-11-06 17:38:39');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `mucluong`
--

CREATE TABLE `mucluong` (
  `id` int(11) NOT NULL,
  `tenmucluong` varchar(255) NOT NULL,
  `giatri_min` decimal(15,2) DEFAULT NULL,
  `giatri_max` decimal(15,2) DEFAULT NULL,
  `trangthai` enum('hoatdong','an') DEFAULT 'hoatdong',
  `ngaytao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `mucluong`
--

INSERT INTO `mucluong` (`id`, `tenmucluong`, `giatri_min`, `giatri_max`, `trangthai`, `ngaytao`) VALUES
(1, 'Dưới 5 triệu', 0.00, 5000000.00, 'hoatdong', '2025-11-06 17:38:39'),
(2, '5 - 10 triệu', 5000000.00, 10000000.00, 'hoatdong', '2025-11-06 17:38:39'),
(3, '10 - 15 triệu', 10000000.00, 15000000.00, 'hoatdong', '2025-11-06 17:38:39'),
(4, '15 - 20 triệu', 15000000.00, 20000000.00, 'hoatdong', '2025-11-06 17:38:39'),
(5, '20 - 25 triệu', 20000000.00, 25000000.00, 'hoatdong', '2025-11-06 17:38:39'),
(6, '25 - 30 triệu', 25000000.00, 30000000.00, 'hoatdong', '2025-11-06 17:38:39'),
(7, 'Trên 30 triệu', 30000000.00, 999999999.00, 'hoatdong', '2025-11-06 17:38:39'),
(8, 'Thỏa thuận', NULL, NULL, 'hoatdong', '2025-11-06 17:38:39'),
(41, 'Trên 50 triệu', 50000000.00, 999999999.00, 'hoatdong', '2025-11-19 13:57:13'),
(42, 'Dưới 2 triệu', 0.00, 2000000.00, 'hoatdong', '2025-12-03 08:11:01'),
(44, 'Dưới 1 triệu', 0.00, 1000000.00, 'hoatdong', '2025-12-03 08:24:51');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nganhnghe`
--

CREATE TABLE `nganhnghe` (
  `id` int(11) NOT NULL,
  `tennganh` varchar(255) NOT NULL,
  `mota` text DEFAULT NULL,
  `trangthai` enum('hoatdong','an') DEFAULT 'hoatdong',
  `ngaytao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nganhnghe`
--

INSERT INTO `nganhnghe` (`id`, `tennganh`, `mota`, `trangthai`, `ngaytao`) VALUES
(1, 'Công nghệ thông tin', 'Lập trình, phát triển phần mềm, IT', 'hoatdong', '2025-11-06 17:38:39'),
(2, 'Kế toán - Kiểm toán', 'Kế toán, kiểm toán, tài chính', 'hoatdong', '2025-11-06 17:38:39'),
(3, 'Marketing - PR', 'Marketing, truyền thông, quảng cáo', 'hoatdong', '2025-11-06 17:38:39'),
(4, 'Kinh doanh - Bán hàng', 'Kinh doanh, bán hàng, phát triển thị trường', 'hoatdong', '2025-11-06 17:38:39'),
(5, 'Nhân sự', 'Tuyển dụng, đào tạo, quản lý nhân sự', 'hoatdong', '2025-11-06 17:38:39'),
(6, 'Hành chính - Văn phòng', 'Thư ký, hành chính, văn phòng', 'hoatdong', '2025-11-06 17:38:39'),
(7, 'Thiết kế - Mỹ thuật', 'Thiết kế đồ họa, UI/UX, mỹ thuật', 'hoatdong', '2025-11-06 17:38:39'),
(8, 'Xây dựng', 'Xây dựng, kiến trúc, nội thất', 'hoatdong', '2025-11-06 17:38:39'),
(9, 'Giáo dục - Đào tạo', 'Giảng dạy, đào tạo, giáo viên', 'hoatdong', '2025-11-06 17:38:39'),
(10, 'Y tế - Dược', 'Y tế, dược phẩm, chăm sóc sức khỏe', 'hoatdong', '2025-11-06 17:38:39'),
(71, 'Logistic', NULL, 'hoatdong', '2025-11-14 15:29:55');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoidung`
--

CREATE TABLE `nguoidung` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `matkhau` varchar(255) NOT NULL,
  `hoten` varchar(255) NOT NULL,
  `sodienthoai` varchar(20) DEFAULT NULL,
  `diachi` text DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `vaitro` enum('admin','ungvien','tuyendung','choduyet') DEFAULT 'ungvien',
  `trangthai` enum('hoatdong','khoa') DEFAULT 'hoatdong',
  `xacminh` tinyint(1) DEFAULT 0,
  `ngaytao` datetime DEFAULT current_timestamp(),
  `ngaycapnhat` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nguoidung`
--

INSERT INTO `nguoidung` (`id`, `email`, `matkhau`, `hoten`, `sodienthoai`, `diachi`, `avatar`, `vaitro`, `trangthai`, `xacminh`, `ngaytao`, `ngaycapnhat`) VALUES
(1, 'cskh.timviec@gmail.com', '$2y$10$kNGUYGuD3558cJDchHng3ODxsd0b/6pZ.xw2FoOgDBov5dntREW9K', 'Quản Trị Viên', '0123456789', '123 Đường 3 Tháng 2, Phường 1, TP. Vĩnh Long, Vĩnh Long', NULL, 'admin', 'hoatdong', 1, '2025-11-06 09:58:58', '2025-12-10 07:42:51'),
(2, 'nhatd1@gmail.com', '$2y$10$kNGUYGuD3558cJDchHng3ODxsd0b/6pZ.xw2FoOgDBov5dntREW9K', 'Công ty TNHH ABC', '0901234567', '234 Đường 3 Tháng 2, Phường 5, TP. Vĩnh Long, Vĩnh Long', 'avatar_2_1762702633.jpg', 'tuyendung', 'hoatdong', 0, '2025-11-06 17:38:39', '2025-12-10 07:42:51'),
(3, 'nhatd2@gmail.com', '$2y$10$kNGUYGuD3558cJDchHng3ODxsd0b/6pZ.xw2FoOgDBov5dntREW9K', 'Công ty Cổ phần XYZ', '0902345678', '456 Lê Lợi, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh', NULL, 'tuyendung', 'hoatdong', 0, '2025-11-06 17:38:39', '2025-12-10 07:42:51'),
(4, 'nhatd3@gmail.com', '$2y$10$kNGUYGuD3558cJDchHng3ODxsd0b/6pZ.xw2FoOgDBov5dntREW9K', 'Tập đoàn DEF', '0903456789', '789 Nguyễn Huệ, Phường 2, TP. Vĩnh Long, Vĩnh Long', NULL, 'tuyendung', 'hoatdong', 0, '2025-11-06 17:38:39', '2025-12-10 07:42:51'),
(5, 'nhatd4@gmail.com', '$2y$10$kNGUYGuD3558cJDchHng3ODxsd0b/6pZ.xw2FoOgDBov5dntREW9K', 'Công ty Tech Solutions', '0904567890', '321 Trần Hưng Đạo, Phường Nguyễn Thái Bình, Quận 1, TP. Hồ Chí Minh', NULL, 'tuyendung', 'hoatdong', 0, '2025-11-06 17:38:39', '2025-12-10 07:42:51'),
(6, 'nhatd5@gmail.com', '$2y$10$kNGUYGuD3558cJDchHng3ODxsd0b/6pZ.xw2FoOgDBov5dntREW9K', 'Công ty Marketing Pro', '0905678901', '654 Bà Triệu, Phường Nguyễn Du, Quận Hai Bà Trưng, TP. Hà Nội', NULL, 'tuyendung', 'hoatdong', 1, '2025-11-06 17:38:39', '2025-12-10 07:42:51'),
(7, 'ungvien1@gmail.com', '$2y$10$kNGUYGuD3558cJDchHng3ODxsd0b/6pZ.xw2FoOgDBov5dntREW9K', 'Nguyễn Văn A', '0911111111', '12 Lý Thường Kiệt, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh', NULL, 'ungvien', 'hoatdong', 0, '2025-11-06 17:38:39', '2025-12-10 07:42:51'),
(8, 'ungvien2@gmail.com', '$2y$10$kNGUYGuD3558cJDchHng3ODxsd0b/6pZ.xw2FoOgDBov5dntREW9K', 'Trần Thị B', '0922222222', '45 Pasteur, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh', NULL, 'ungvien', 'hoatdong', 0, '2025-11-06 17:38:39', '2025-12-10 07:42:51'),
(9, 'ungvien3@gmail.com', '$2y$10$kNGUYGuD3558cJDchHng3ODxsd0b/6pZ.xw2FoOgDBov5dntREW9K', 'Lê Văn C', '0933333333', '78 Trần Quang Khải, Phường Tân Định, Quận 1, TP. Hồ Chí Minh', NULL, 'ungvien', 'hoatdong', 0, '2025-11-06 17:38:39', '2025-12-10 07:42:51'),
(10, 'ungvien4@gmail.com', '$2y$10$kNGUYGuD3558cJDchHng3ODxsd0b/6pZ.xw2FoOgDBov5dntREW9K', 'Phạm Thị D', '0944444444', '23 Nguyễn Văn Cừ, Phường Tân Thuận Tây, Quận 7, TP. Hồ Chí Minh', NULL, 'ungvien', 'hoatdong', 0, '2025-11-06 17:38:39', '2025-12-10 07:42:51'),
(11, 'ungvien5@gmail.com', '$2y$10$kNGUYGuD3558cJDchHng3ODxsd0b/6pZ.xw2FoOgDBov5dntREW9K', 'Hoàng Văn E', '0955555555', '89 Lạc Long Quân, Phường 5, Quận 11, TP. Hồ Chí Minh', NULL, 'ungvien', 'hoatdong', 0, '2025-11-06 17:38:39', '2025-12-10 07:42:51'),
(12, 'ungvien6@gmail.com', '$2y$10$kNGUYGuD3558cJDchHng3ODxsd0b/6pZ.xw2FoOgDBov5dntREW9K', 'Vũ Thị F', '0966666666', '56 Điện Biên Phủ, Phường 15, Quận Bình Thạnh, TP. Hồ Chí Minh', NULL, 'ungvien', 'hoatdong', 0, '2025-11-06 17:38:39', '2025-12-10 07:42:51'),
(13, 'ungvien7@gmail.com', '$2y$10$kNGUYGuD3558cJDchHng3ODxsd0b/6pZ.xw2FoOgDBov5dntREW9K', 'Đỗ Văn G', '0977777777', '34 Hoàng Văn Thụ, Phường 9, Quận Phú Nhuận, TP. Hồ Chí Minh', NULL, 'ungvien', 'hoatdong', 0, '2025-11-06 17:38:39', '2025-12-10 07:42:51'),
(14, 'ungvien8@gmail.com', '$2y$10$kNGUYGuD3558cJDchHng3ODxsd0b/6pZ.xw2FoOgDBov5dntREW9K', 'Bùi Thị H', '0988888888', '67 Quang Trung, Phường 10, Quận Gò Vấp, TP. Hồ Chí Minh', NULL, 'ungvien', 'hoatdong', 0, '2025-11-06 17:38:39', '2025-12-10 07:42:51'),
(15, 'ungvien9@gmail.com', '$2y$10$kNGUYGuD3558cJDchHng3ODxsd0b/6pZ.xw2FoOgDBov5dntREW9K', 'Dương Văn I', '0999999999', '90 Võ Văn Ngân, Phường Linh Chiểu, TP. Thủ Đức, TP. Hồ Chí Minh', NULL, 'ungvien', 'hoatdong', 0, '2025-11-06 17:38:39', '2025-12-10 07:42:51'),
(16, 'ungvien10@gmail.com', '$2y$10$kNGUYGuD3558cJDchHng3ODxsd0b/6pZ.xw2FoOgDBov5dntREW9K', 'Võ Thị K', '0910101010', '43 Phan Xích Long, Phường 2, Quận Phú Nhuận, TP. Hồ Chí Minh', NULL, 'ungvien', 'hoatdong', 0, '2025-11-06 17:38:39', '2025-12-10 07:42:52'),
(17, 'tan@gmail.com', '$2y$10$XxbawWlZ.Zjhws094gN1cusxOkDaGhwur5zjzAdC66jSPmH4bUCM6', 'tan', '0222222222', '156 Đường 30 Tháng 4, Phường 1, TP. Vĩnh Long, Vĩnh Long', NULL, 'ungvien', 'hoatdong', 1, '2025-11-06 17:44:32', '2025-12-10 07:42:52'),
(18, 'khang@gmail.com', '$2y$10$QiIH8oe7m908As/Nb3DYY.7Ayjc1nJ0XG1cJhy8n9Whx0Wu.OOL8y', 'khang', '0967373491', '78 Nguyễn Trãi, Phường 9, TP. Cà Mau, Cà Mau', '6916e3e502d47_1763107813.jpg', 'ungvien', 'hoatdong', 0, '2025-11-14 15:02:29', '2025-12-28 18:57:09'),
(20, 'hao@gmail.com', '$2y$10$z1il85n/7Ahg7StU3pwDTePJAk7cKPfr71awIaXmCul5CyosAIn66', 'Đỗ Gia Hào', '0344567891', NULL, NULL, 'tuyendung', 'hoatdong', 1, '2025-12-28 19:01:28', '2025-12-28 19:02:12');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thongtinnhatuyendung`
--

CREATE TABLE `thongtinnhatuyendung` (
  `id` int(11) NOT NULL,
  `nguoidung_id` int(11) NOT NULL,
  `tencongty` varchar(255) NOT NULL,
  `masothue` varchar(50) DEFAULT NULL,
  `diachi_congty` text DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `mota` text DEFAULT NULL,
  `quymo` varchar(100) DEFAULT NULL,
  `linhvuc` varchar(255) DEFAULT NULL,
  `email_congty` varchar(255) DEFAULT NULL,
  `lydoyeucau` text DEFAULT NULL COMMENT 'Lý do muốn trở thành nhà tuyển dụng',
  `ngaygui` datetime DEFAULT current_timestamp(),
  `ngaycapnhat` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `thongtinnhatuyendung`
--

INSERT INTO `thongtinnhatuyendung` (`id`, `nguoidung_id`, `tencongty`, `masothue`, `diachi_congty`, `website`, `logo`, `mota`, `quymo`, `linhvuc`, `email_congty`, `lydoyeucau`, `ngaygui`, `ngaycapnhat`) VALUES
(1, 2, 'Công ty TNHH ABC', '0123456789', '234 Đường 3 Tháng 2, Phường 5, TP. Vĩnh Long, Vĩnh Long', 'https://abc.com.vn', 'logo_2_1762702671.jpg', 'Công ty chuyên về phát triển phần mềm và giải pháp công nghệ. Với đội ngũ chuyên gia giàu kinh nghiệm, chúng tôi cam kết mang đến các sản phẩm chất lượng cao cho khách hàng.', '50-100 nhân viên', 'Công nghệ thông tin', 'contact@abc.com.vn', NULL, '2025-11-06 17:38:39', '2025-12-10 07:42:52'),
(2, 3, 'Công ty Cổ phần XYZ', '0234567890', '456 Lê Lợi, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh', 'https://xyz.vn', NULL, 'Công ty hàng đầu trong lĩnh vực marketing và truyền thông số. Chúng tôi tự hào là đối tác của nhiều thương hiệu lớn tại Việt Nam và khu vực.', '100-200 nhân viên', 'Marketing - Truyền thông', 'info@xyz.vn', NULL, '2025-11-06 17:38:39', '2025-12-10 07:42:52'),
(3, 4, 'Tập đoàn DEF', '0345678901', '789 Nguyễn Huệ, Phường 2, TP. Vĩnh Long, Vĩnh Long', 'https://def.com', NULL, 'Tập đoàn đa ngành với hơn 20 năm kinh nghiệm. Chuyên về sản xuất, thương mại và dịch vụ. Đang mở rộng quy mô và tìm kiếm nhân tài.', 'Trên 500 nhân viên', 'Đa ngành', 'hr@def.com', NULL, '2025-11-06 17:38:39', '2025-12-10 07:42:52'),
(4, 5, 'Công ty Tech Solutions', '0456789012', '321 Trần Hưng Đạo, Phường Nguyễn Thái Bình, Quận 1, TP. Hồ Chí Minh', 'https://techsolutions.vn', NULL, 'Đơn vị tiên phong trong chuyển đổi số và giải pháp công nghệ cho doanh nghiệp. Chúng tôi cung cấp các giải pháp toàn diện từ tư vấn đến triển khai.', '200-500 nhân viên', 'Chuyển đổi số', 'careers@techsolutions.vn', NULL, '2025-11-06 17:38:39', '2025-12-10 07:42:52'),
(5, 6, 'Công ty Marketing Pro', '0567890123', '654 Bà Triệu, Phường Nguyễn Du, Quận Hai Bà Trưng, TP. Hà Nội', 'https://marketingpro.vn', NULL, 'Agency marketing chuyên nghiệp với đội ngũ sáng tạo trẻ trung, năng động. Chúng tôi mang đến các chiến dịch marketing hiệu quả và sáng tạo.', '30-50 nhân viên', 'Marketing Agency', 'jobs@marketingpro.vn', NULL, '2025-11-06 17:38:39', '2025-12-10 07:42:52'),
(12, 20, '123', '0123456789', '123', 'https://123.com', NULL, '123', '501-1000', '123', '123@gmail.com', '123', '2025-12-28 19:02:00', '2025-12-28 19:02:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thongtinungvien`
--

CREATE TABLE `thongtinungvien` (
  `id` int(11) NOT NULL,
  `nguoidung_id` int(11) NOT NULL,
  `ngaysinh` date DEFAULT NULL,
  `gioitinh` enum('nam','nu','khac') DEFAULT NULL,
  `trinhdo` varchar(255) DEFAULT NULL,
  `kinhnghiem` text DEFAULT NULL,
  `kynang` text DEFAULT NULL,
  `muctieucanhan` text DEFAULT NULL,
  `cv_file` varchar(255) DEFAULT NULL,
  `ngaycapnhat` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `thongtinungvien`
--

INSERT INTO `thongtinungvien` (`id`, `nguoidung_id`, `ngaysinh`, `gioitinh`, `trinhdo`, `kinhnghiem`, `kynang`, `muctieucanhan`, `cv_file`, `ngaycapnhat`) VALUES
(1, 7, '1998-05-15', 'nam', 'Đại học', '2 năm kinh nghiệm lập trình PHP', 'PHP, Laravel, MySQL, JavaScript, Git, HTML/CSS', 'Mong muốn trở thành Senior Developer trong 2 năm tới. Luôn học hỏi và cập nhật kiến thức mới về công nghệ.', '6950a18c6f6e1_1766891916.pdf', '2025-12-28 10:18:36'),
(2, 8, '1999-08-20', 'nu', 'Đại học', '1 năm kinh nghiệm Marketing', 'Facebook Ads, Google Ads, Content Writing, Social Media Management, SEO, Photoshop', 'Phát triển sự nghiệp trong lĩnh vực Digital Marketing. Mục tiêu trở thành Marketing Manager sau 3 năm.', NULL, '2025-11-06 17:38:40'),
(3, 9, '1997-03-10', 'nam', 'Đại học', '3 năm kinh nghiệm kế toán', 'Kế toán tổng hợp, Excel nâng cao, SAP, Misa, Fast Accounting', 'Hoàn thiện kiến thức chuyên môn và lấy chứng chỉ Kế toán trưởng trong năm tới.', NULL, '2025-11-06 17:38:40'),
(4, 10, '2000-11-25', 'nu', 'Cao đẳng', '6 tháng thực tập nhân sự', 'Tuyển dụng, MS Office, Quản lý hồ sơ nhân viên, Giao tiếp tốt', 'Phát triển kỹ năng tuyển dụng và quản trị nhân sự. Mong muốn làm việc lâu dài tại công ty ổn định.', NULL, '2025-11-06 17:38:40'),
(5, 11, '1998-07-18', 'nam', 'Đại học', '2 năm kinh doanh B2B', 'Sales, Marketing, Đàm phán, Quản lý khách hàng, MS Office', 'Trở thành Sales Manager, xây dựng đội ngũ kinh doanh chuyên nghiệp.', NULL, '2025-11-06 17:38:40'),
(6, 12, '1999-02-14', 'nu', 'Đại học', '1 năm thiết kế đồ họa', 'Photoshop, Illustrator, Figma, UI/UX Design, Adobe XD', 'Phát triển kỹ năng UI/UX Design và làm việc tại các công ty công nghệ lớn.', NULL, '2025-11-06 17:38:40'),
(7, 13, '1996-09-05', 'nam', 'Đại học', '4 năm lập trình Java', 'Java, Spring Boot, Microservices, Docker, Kubernetes, AWS', 'Trở thành Technical Lead, dẫn dắt các dự án công nghệ lớn.', NULL, '2025-11-06 17:38:40'),
(8, 14, '2001-04-30', 'nu', 'Cao đẳng', 'Mới tốt nghiệp ngành Điều dưỡng', 'Chăm sóc bệnh nhân, Y tá, Điều dưỡng, Sơ cứu cấp cứu', 'Tích lũy kinh nghiệm làm việc tại bệnh viện và phòng khám uy tín.', NULL, '2025-11-06 17:38:40'),
(9, 15, '1997-12-08', 'nam', 'Đại học', '3 năm giảng dạy Tiếng Anh', 'Tiếng Anh giao tiếp, TOEIC, IELTS, Quản lý lớp học, Soạn giáo án', 'Mở trung tâm Tiếng Anh riêng trong 5 năm tới.', NULL, '2025-11-06 17:38:40'),
(10, 16, '1998-06-22', 'nu', 'Đại học', '2 năm ngành Du lịch', 'Tour guide, Lễ tân khách sạn, Tiếng Anh giao tiếp, Tiếng Trung cơ bản', 'Phát triển sự nghiệp trong ngành Du lịch - Khách sạn cao cấp.', NULL, '2025-11-06 17:38:40'),
(11, 17, '2000-04-06', 'nam', 'Đại học', '5 năm lập trình php', 'lập trình web', 'lập trình viên', NULL, '2025-11-19 13:36:41'),
(12, 18, '2000-06-06', 'nam', 'Đại học', '1 năm làm việc', 'lập trình', 'lập trình Java', '6916e3e4f2a12_1763107812.pdf', '2025-11-19 13:36:58'),
(14, 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-28 19:01:28');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tinhthanh`
--

CREATE TABLE `tinhthanh` (
  `id` int(11) NOT NULL,
  `tentinh` varchar(255) NOT NULL,
  `trangthai` enum('hoatdong','an') DEFAULT 'hoatdong',
  `ngaytao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tinhthanh`
--

INSERT INTO `tinhthanh` (`id`, `tentinh`, `trangthai`, `ngaytao`) VALUES
(1, 'TP.Hồ Chí Minh', 'hoatdong', '2025-11-06 17:38:39'),
(81, 'Vĩnh Long', 'hoatdong', '2025-11-14 15:01:12'),
(82, 'Tuyên Quang', 'hoatdong', '2025-11-16 21:07:57'),
(83, 'Cao Bằng', 'hoatdong', '2025-11-16 21:17:16'),
(84, 'Lai Châu', 'hoatdong', '2025-11-16 21:17:16'),
(85, 'Lào Cai', 'hoatdong', '2025-11-16 21:17:16'),
(86, 'Thái Nguyên', 'hoatdong', '2025-11-16 21:17:16'),
(87, 'Điện Biên', 'hoatdong', '2025-11-16 21:17:16'),
(88, 'Lạng Sơn', 'hoatdong', '2025-11-16 21:17:16'),
(89, 'Sơn La', 'hoatdong', '2025-11-16 21:17:16'),
(90, 'Phú Thọ', 'hoatdong', '2025-11-16 21:17:16'),
(91, 'Bắc Ninh', 'hoatdong', '2025-11-16 21:17:16'),
(92, 'Quảng Ninh', 'hoatdong', '2025-11-16 21:17:16'),
(93, 'TP.Hà Nội', 'hoatdong', '2025-11-16 21:17:16'),
(94, 'TP.Hải Phòng', 'hoatdong', '2025-11-16 21:17:16'),
(95, 'Hưng Yên', 'hoatdong', '2025-11-16 21:17:16'),
(96, 'Ninh Bình', 'hoatdong', '2025-11-16 21:17:16'),
(97, 'Thanh Hóa', 'hoatdong', '2025-11-16 21:17:16'),
(98, 'Nghệ An', 'hoatdong', '2025-11-16 21:17:16'),
(99, 'Hà Tĩnh', 'hoatdong', '2025-11-16 21:17:16'),
(100, 'Quảng Trị', 'hoatdong', '2025-11-16 21:17:16'),
(101, 'TP.Huế', 'hoatdong', '2025-11-16 21:17:16'),
(102, 'TP.Đà Nẵng', 'hoatdong', '2025-11-16 21:17:16'),
(103, 'Quảng Ngãi', 'hoatdong', '2025-11-16 21:17:16'),
(104, 'Gia Lai', 'hoatdong', '2025-11-16 21:17:16'),
(105, 'Đắk Lắk', 'hoatdong', '2025-11-16 21:17:16'),
(106, 'Khánh Hòa', 'hoatdong', '2025-11-16 21:17:16'),
(107, 'Lâm Đồng', 'hoatdong', '2025-11-16 21:17:16'),
(108, 'Đồng Nai', 'hoatdong', '2025-11-16 21:17:16'),
(109, 'Tây Ninh', 'hoatdong', '2025-11-16 21:17:16'),
(110, 'Đồng Tháp', 'hoatdong', '2025-11-16 21:17:16'),
(111, 'An Giang', 'hoatdong', '2025-11-16 21:17:16'),
(112, 'TP.Cần Thơ', 'hoatdong', '2025-11-16 21:17:16'),
(113, 'Cà Mau', 'hoatdong', '2025-11-16 21:17:16');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tintuyendung`
--

CREATE TABLE `tintuyendung` (
  `id` int(11) NOT NULL,
  `nguoidung_id` int(11) NOT NULL COMMENT 'ID nhà tuyển dụng',
  `tieude` varchar(500) NOT NULL,
  `nganhnghe_id` int(11) NOT NULL,
  `mucluong_id` int(11) DEFAULT NULL,
  `loaicongviec_id` int(11) DEFAULT NULL,
  `tinhthanh_id` int(11) DEFAULT NULL,
  `diachilamviec` text DEFAULT NULL,
  `soluong` int(11) DEFAULT 1,
  `gioitinh_yc` enum('nam','nu','khongphanbiet') DEFAULT 'khongphanbiet',
  `mota` text DEFAULT NULL,
  `yeucau` text DEFAULT NULL,
  `quyenloi` text DEFAULT NULL,
  `ngayhethan` date NOT NULL,
  `trangthai` enum('choduyet','dangmo','hethan','dong','an') DEFAULT 'choduyet',
  `luotxem` int(11) DEFAULT 0,
  `ngaydang` datetime DEFAULT current_timestamp(),
  `ngaycapnhat` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tintuyendung`
--

INSERT INTO `tintuyendung` (`id`, `nguoidung_id`, `tieude`, `nganhnghe_id`, `mucluong_id`, `loaicongviec_id`, `tinhthanh_id`, `diachilamviec`, `soluong`, `gioitinh_yc`, `mota`, `yeucau`, `quyenloi`, `ngayhethan`, `trangthai`, `luotxem`, `ngaydang`, `ngaycapnhat`) VALUES
(1, 2, 'Tuyển lập trình viên PHP Laravel', 1, 4, 1, 81, '234 Đường 3 Tháng 2, Phường 5, TP. Vĩnh Long, Vĩnh Long', 3, 'khongphanbiet', 'Chúng tôi đang tìm kiếm lập trình viên PHP Laravel có kinh nghiệm để tham gia vào các dự án web application lớn. Bạn sẽ làm việc trong môi trường chuyên nghiệp với các công nghệ hiện đại.', '- Tốt nghiệp Đại học chuyên ngành CNTT\n- Có ít nhất 1 năm kinh nghiệm với PHP Laravel\n- Thành thạo MySQL, Git\n- Có kiến thức về JavaScript, Vue.js là một lợi thế', '- Lương: 15-20 triệu (thỏa thuận theo năng lực)\n- Thưởng theo dự án\n- Bảo hiểm đầy đủ\n- Du lịch hàng năm\n- Môi trường làm việc trẻ trung, năng động', '2026-03-20', 'dangmo', 2, '2025-10-01 08:00:00', '2026-01-05 21:04:03'),
(2, 5, 'Cần tuyển Senior Full-stack Developer', 1, 7, 1, 1, '321 Trần Hưng Đạo, Phường Nguyễn Thái Bình, Quận 1, TP. Hồ Chí Minh', 2, 'khongphanbiet', 'Tìm kiếm Senior Developer có kinh nghiệm để dẫn dắt team phát triển sản phẩm công nghệ mới.', '- Trên 3 năm kinh nghiệm Full-stack\n- Thành thạo React, Node.js\n- Có kinh nghiệm làm việc với Cloud (AWS/Azure)\n- Kỹ năng leadership', '- Lương: 30 triệu trở lên\n- Stock option\n- Bảo hiểm cao cấp\n- Làm việc hybrid\n- Cơ hội thăng tiến', '2026-03-20', 'dangmo', 2, '2025-10-05 09:00:00', '2026-01-05 21:03:48'),
(3, 2, 'Thực tập sinh lập trình Web', 1, 1, 3, 81, '234 Đường 3 Tháng 2, Phường 5, TP. Vĩnh Long, Vĩnh Long', 5, 'khongphanbiet', 'Cơ hội thực tập cho sinh viên năm cuối hoặc mới tốt nghiệp. Được đào tạo bài bản và có cơ hội trở thành nhân viên chính thức.', '- Sinh viên năm cuối hoặc mới tốt nghiệp\n- Có kiến thức cơ bản về HTML, CSS, JavaScript\n- Ham học hỏi, nhiệt tình\n- Có thể làm việc full-time', '- Trợ cấp: 3-5 triệu/tháng\n- Được đào tạo miễn phí\n- Cơ hội trở thành nhân viên chính thức\n- Môi trường thực tế', '2026-03-20', 'dangmo', 3, '2025-10-10 10:00:00', '2026-01-05 21:08:28'),
(4, 6, 'Nhân viên Marketing Online', 3, 3, 1, 93, '654 Bà Triệu, Phường Nguyễn Du, Quận Hai Bà Trưng, TP. Hà Nội', 2, 'khongphanbiet', 'Tuyển nhân viên marketing online có kinh nghiệm để phát triển các kênh digital marketing cho công ty.', '- Tốt nghiệp chuyên ngành Marketing, TMĐT\n- Có ít nhất 1 năm kinh nghiệm\n- Thành thạo Facebook Ads, Google Ads\n- Kỹ năng content writing tốt', '- Lương: 10-15 triệu + KPI\n- Thưởng doanh số\n- Đào tạo kỹ năng thường xuyên\n- Môi trường sáng tạo', '2026-03-20', 'dangmo', 1, '2025-10-08 08:30:00', '2026-01-05 21:08:28'),
(5, 6, 'Content Creator - Biên tập viên', 3, 2, 1, 93, '654 Bà Triệu, Phường Nguyễn Du, Quận Hai Bà Trưng, TP. Hà Nội', 3, 'khongphanbiet', 'Cần tuyển Content Creator để sản xuất nội dung cho các kênh social media và website.', '- Có khả năng viết content chất lượng\n- Thành thạo Photoshop, Canva\n- Có kiến thức về SEO\n- Kỹ năng quay dựng video là lợi thế', '- Lương: 8-12 triệu\n- Được trang bị thiết bị làm việc\n- Môi trường sáng tạo\n- Học hỏi nhiều kỹ năng mới', '2026-03-20', 'dangmo', 2, '2025-10-12 09:00:00', '2026-01-05 21:08:28'),
(6, 3, 'Kế toán tổng hợp', 2, 3, 1, 81, '789 Nguyễn Huệ, Phường 2, TP. Vĩnh Long, Vĩnh Long', 2, 'khongphanbiet', 'Tuyển kế toán tổng hợp có kinh nghiệm để phụ trách công tác kế toán toàn công ty.', '- Tốt nghiệp Đại học chuyên ngành Kế toán\n- Có ít nhất 2 năm kinh nghiệm\n- Thành thạo Excel, phần mềm kế toán\n- Có chứng chỉ kế toán trưởng là lợi thế', '- Lương: 12-18 triệu\n- Thưởng quý, tết\n- Bảo hiểm đầy đủ\n- Làm việc giờ hành chính', '2026-03-20', 'dangmo', 1, '2025-10-15 08:00:00', '2026-01-05 21:08:28'),
(7, 4, 'Nhân viên tuyển dụng (Recruiter)', 5, 3, 1, 1, '321 Trần Hưng Đạo, Phường Nguyễn Thái Bình, Quận 1, TP. Hồ Chí Minh', 2, 'khongphanbiet', 'Tuyển Recruiter để mở rộng đội ngũ tuyển dụng, phụ trách tuyển dụng cho các vị trí IT.', '- Tốt nghiệp chuyên ngành Quản trị nhân lực\n- Có kinh nghiệm tuyển dụng IT là lợi thế\n- Kỹ năng giao tiếp tốt\n- Thành thạo các kênh tuyển dụng', '- Lương: 10-15 triệu + thưởng\n- Bảo hiểm đầy đủ\n- Được đào tạo\n- Môi trường năng động', '2026-03-20', 'dangmo', 0, '2025-10-18 09:00:00', '2026-01-05 21:08:28'),
(8, 4, 'Nhân viên kinh doanh B2B', 4, 2, 1, 1, '321 Trần Hưng Đạo, Phường Nguyễn Thái Bình, Quận 1, TP. Hồ Chí Minh', 5, 'khongphanbiet', 'Tuyển nhân viên kinh doanh để phát triển khách hàng doanh nghiệp.', '- Có kinh nghiệm bán hàng B2B\n- Kỹ năng giao tiếp, đàm phán tốt\n- Ham học hỏi, chịu được áp lực\n- Có phương tiện đi lại', '- Lương: 8-12 triệu + hoa hồng hấp dẫn\n- Không trần thu nhập\n- Thưởng KPI\n- Được đào tạo kỹ năng', '2026-03-20', 'dangmo', 1, '2025-10-20 08:30:00', '2026-01-05 21:08:28'),
(9, 6, 'Nhân viên thiết kế đồ họa', 7, 3, 1, 93, '654 Bà Triệu, Phường Nguyễn Du, Quận Hai Bà Trưng, TP. Hà Nội', 2, 'khongphanbiet', 'Tuyển designer để thiết kế các ấn phẩm marketing, social media content.', '- Thành thạo Photoshop, Illustrator\n- Có kinh nghiệm thiết kế marketing\n- Có khiếu thẩm mỹ tốt\n- Portfolio ấn tượng', '- Lương: 10-15 triệu\n- Môi trường sáng tạo\n- Được trang bị Macbook\n- Cơ hội học hỏi', '2026-03-20', 'dangmo', 0, '2025-10-22 09:00:00', '2026-01-05 21:08:28'),
(10, 3, 'Tuyển kỹ sư xây dựng', 8, 4, 1, 81, '789 Nguyễn Huệ, Phường 2, TP. Vĩnh Long, Vĩnh Long', 3, 'khongphanbiet', 'Công ty cần tuyển kỹ sư xây dựng để giám sát các công trình.', '- Tốt nghiệp Đại học chuyên ngành Xây dựng\n- Có kinh nghiệm làm việc tại công trường\n- Có thể đi công tác\n- Kỹ năng đọc bản vẽ', '- Lương: 15-25 triệu\n- Hỗ trợ nhà ở\n- Xe đưa đón\n- Bảo hiểm đầy đủ', '2026-03-20', 'dangmo', 0, '2025-10-25 08:00:00', '2026-01-05 21:09:52'),
(11, 5, 'Developer làm việc từ xa (Remote)', 1, 5, 4, 1, 'Làm việc từ xa (Remote) - Trụ sở: 321 Trần Hưng Đạo, Phường Nguyễn Thái Bình, Quận 1, TP. Hồ Chí Minh', 2, 'khongphanbiet', 'Cho phép làm việc 100% remote, chỉ cần internet và laptop.', '- Có kinh nghiệm làm việc remote\n- Tự giác, chủ động\n- Kỹ năng giao tiếp online tốt\n- Thành thạo công cụ làm việc nhóm', '- Lương: 20-30 triệu\n- Làm việc linh hoạt\n- Trang thiết bị hỗ trợ\n- Team building online', '2026-03-20', 'dangmo', 4, '2025-10-28 09:00:00', '2026-01-05 21:11:06'),
(12, 2, 'Part-time Developer cuối tuần', 1, 2, 2, 81, '234 Đường 3 Tháng 2, Phường 5, TP. Vĩnh Long, Vĩnh Long', 3, 'khongphanbiet', 'Tuyển developer làm part-time vào cuối tuần để hỗ trợ dự án.', '- Có kinh nghiệm lập trình\n- Có thể làm việc cuối tuần\n- Linh hoạt thời gian\n- Tự học tốt', '- Lương: 200k-300k/giờ\n- Làm việc linh hoạt\n- Dự án thú vị\n- Cơ hội full-time sau này', '2026-03-20', 'dangmo', 11, '2025-11-01 10:00:00', '2026-01-05 21:10:41'),
(13, 2, 'Tuyển dụng người thử nghiệm phần mềm', 1, 1, 4, 81, '234 Đường 3 Tháng 2, Phường 5, TP. Vĩnh Long, Vĩnh Long', 5, 'khongphanbiet', '123', '123', '123', '2026-03-20', 'dangmo', 17, '2025-11-12 12:31:37', '2026-01-05 21:10:16');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cauhinh`
--
ALTER TABLE `cauhinh`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ten_config` (`ten_config`);

--
-- Chỉ mục cho bảng `chatbot_conversations`
--
ALTER TABLE `chatbot_conversations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `session_id` (`session_id`),
  ADD KEY `nguoidung_id` (`nguoidung_id`);

--
-- Chỉ mục cho bảng `chatbot_messages`
--
ALTER TABLE `chatbot_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conversation_id` (`conversation_id`),
  ADD KEY `session_id` (`session_id`),
  ADD KEY `nguoidung_id` (`nguoidung_id`);

--
-- Chỉ mục cho bảng `donungtuyen`
--
ALTER TABLE `donungtuyen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_don` (`tintuyendung_id`,`nguoidung_id`),
  ADD KEY `idx_tintuyendung` (`tintuyendung_id`),
  ADD KEY `idx_nguoidung` (`nguoidung_id`),
  ADD KEY `idx_trangthai` (`trangthai`);

--
-- Chỉ mục cho bảng `loaicongviec`
--
ALTER TABLE `loaicongviec`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_trangthai` (`trangthai`);

--
-- Chỉ mục cho bảng `mucluong`
--
ALTER TABLE `mucluong`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_trangthai` (`trangthai`);

--
-- Chỉ mục cho bảng `nganhnghe`
--
ALTER TABLE `nganhnghe`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_trangthai` (`trangthai`);

--
-- Chỉ mục cho bảng `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_vaitro` (`vaitro`),
  ADD KEY `idx_trangthai` (`trangthai`);

--
-- Chỉ mục cho bảng `thongtinnhatuyendung`
--
ALTER TABLE `thongtinnhatuyendung`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_nguoidung` (`nguoidung_id`);

--
-- Chỉ mục cho bảng `thongtinungvien`
--
ALTER TABLE `thongtinungvien`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_nguoidung` (`nguoidung_id`);

--
-- Chỉ mục cho bảng `tinhthanh`
--
ALTER TABLE `tinhthanh`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_trangthai` (`trangthai`);

--
-- Chỉ mục cho bảng `tintuyendung`
--
ALTER TABLE `tintuyendung`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_nguoidung` (`nguoidung_id`),
  ADD KEY `idx_nganhnghe` (`nganhnghe_id`),
  ADD KEY `idx_mucluong` (`mucluong_id`),
  ADD KEY `idx_loaicongviec` (`loaicongviec_id`),
  ADD KEY `idx_tinhthanh` (`tinhthanh_id`),
  ADD KEY `idx_trangthai` (`trangthai`),
  ADD KEY `idx_ngayhethan` (`ngayhethan`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `cauhinh`
--
ALTER TABLE `cauhinh`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `chatbot_conversations`
--
ALTER TABLE `chatbot_conversations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT cho bảng `chatbot_messages`
--
ALTER TABLE `chatbot_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT cho bảng `donungtuyen`
--
ALTER TABLE `donungtuyen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `loaicongviec`
--
ALTER TABLE `loaicongviec`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT cho bảng `mucluong`
--
ALTER TABLE `mucluong`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT cho bảng `nganhnghe`
--
ALTER TABLE `nganhnghe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT cho bảng `nguoidung`
--
ALTER TABLE `nguoidung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT cho bảng `thongtinnhatuyendung`
--
ALTER TABLE `thongtinnhatuyendung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `thongtinungvien`
--
ALTER TABLE `thongtinungvien`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `tinhthanh`
--
ALTER TABLE `tinhthanh`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT cho bảng `tintuyendung`
--
ALTER TABLE `tintuyendung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `donungtuyen`
--
ALTER TABLE `donungtuyen`
  ADD CONSTRAINT `fk_donungtuyen_nguoidung` FOREIGN KEY (`nguoidung_id`) REFERENCES `nguoidung` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_donungtuyen_tintuyendung` FOREIGN KEY (`tintuyendung_id`) REFERENCES `tintuyendung` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `thongtinnhatuyendung`
--
ALTER TABLE `thongtinnhatuyendung`
  ADD CONSTRAINT `fk_nhatuyendung_nguoidung` FOREIGN KEY (`nguoidung_id`) REFERENCES `nguoidung` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `thongtinungvien`
--
ALTER TABLE `thongtinungvien`
  ADD CONSTRAINT `fk_ungvien_nguoidung` FOREIGN KEY (`nguoidung_id`) REFERENCES `nguoidung` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tintuyendung`
--
ALTER TABLE `tintuyendung`
  ADD CONSTRAINT `fk_tintuyendung_loaicongviec` FOREIGN KEY (`loaicongviec_id`) REFERENCES `loaicongviec` (`id`),
  ADD CONSTRAINT `fk_tintuyendung_mucluong` FOREIGN KEY (`mucluong_id`) REFERENCES `mucluong` (`id`),
  ADD CONSTRAINT `fk_tintuyendung_nganhnghe` FOREIGN KEY (`nganhnghe_id`) REFERENCES `nganhnghe` (`id`),
  ADD CONSTRAINT `fk_tintuyendung_nguoidung` FOREIGN KEY (`nguoidung_id`) REFERENCES `nguoidung` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_tintuyendung_tinhthanh` FOREIGN KEY (`tinhthanh_id`) REFERENCES `tinhthanh` (`id`);

DELIMITER $$
--
-- Sự kiện
--
CREATE DEFINER=`root`@`localhost` EVENT `capnhat_tin_hethan` ON SCHEDULE EVERY 1 DAY STARTS '2025-11-07 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
    UPDATE `tintuyendung` 
    SET `trangthai` = 'hethan' 
    WHERE `ngayhethan` < CURDATE() 
    AND `trangthai` = 'dangmo';
END$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
