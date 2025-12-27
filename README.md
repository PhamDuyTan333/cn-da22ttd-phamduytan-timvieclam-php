# Xây dựng Website Tìm Việc Làm

## Mục lục
- Xây dựng Website Tìm Việc Làm
  - [Mục lục](#mục-lục)
  - [Giới thiệu](#giới-thiệu)
  - [Tính năng](#tính-năng)
  - [Giới Thiệu Về Repositories của tôi](#giới-thiệu-về-repositories-của-tôi)
  - [Cài đặt](#cài-đặt)
  - [Sử dụng](#sử-dụng)
  - [Thông tin liên lạc](#thông-tin-liên-lạc)
---
## Giới thiệu
Website Tìm Việc Làm là ứng dụng web được xây dựng nhằm hỗ trợ kết nối hiệu quả giữa ứng viên và nhà tuyển dụng trên thị trường lao động Việt Nam.  
Hệ thống được phát triển theo mô hình MVC (Model-View-Controller), sử dụng ngôn ngữ PHP cho backend, cơ sở dữ liệu MySQL, kết hợp giao diện hiện đại với HTML, CSS, JavaScript.  
Ứng dụng cung cấp giải pháp toàn diện giúp ứng viên dễ dàng tìm kiếm và ứng tuyển công việc, nhà tuyển dụng quản lý tin tuyển dụng chuyên nghiệp, đồng thời hỗ trợ quản trị viên giám sát và điều hành hệ thống một cách minh bạch, chính xác.
---
## Tính năng
- **Dành cho Ứng viên**: Đăng ký/đăng nhập (mặc định vai trò ứng viên), cập nhật hồ sơ cá nhân (thông tin, CV, kỹ năng), tìm kiếm và lọc việc làm nâng cao (từ khóa, ngành nghề, tỉnh/thành phố, mức lương, loại công việc), xem chi tiết tin tuyển dụng, nộp đơn ứng tuyển trực tuyến (upload CV, viết thư xin việc), theo dõi trạng thái đơn đã nộp, sử dụng chatbot hỗ trợ tìm việc nhanh chóng.
- **Dành cho Nhà tuyển dụng** (sau khi được duyệt): Đăng tin tuyển dụng mới (chờ duyệt), chỉnh sửa/xóa/gia hạn tin, xem danh sách ứng viên nộp vào tin của mình, tải CV và thư xin việc, cập nhật trạng thái ứng tuyển (mới, đã xem, mời phỏng vấn, từ chối, nhận việc), quản lý thông tin công ty (logo, mô tả, website...).
- **Dành cho Quản trị viên (Admin)**: Quản lý toàn bộ người dùng (khóa/mở khóa tài khoản), duyệt/từ chối yêu cầu trở thành nhà tuyển dụng, duyệt/ẩn/xóa tin tuyển dụng vi phạm, quản lý đơn ứng tuyển, quản lý danh mục (ngành nghề, mức lương, loại công việc, tỉnh/thành phố), cấu hình hệ thống (giới hạn file, thời hạn tin...), xem thống kê và biểu đồ trực quan theo ngày/tuần/tháng.
- **Thống kê và Báo cáo**: Dashboard tổng quan với biểu đồ realtime về số lượng người dùng, tin tuyển dụng, đơn ứng tuyển, top ngành nghề hot và các chỉ số hoạt động khác.
- **Tính năng chung**: Chatbot hỗ trợ tìm việc thông minh bằng tiếng Việt, bảo mật cơ bản (mã hóa mật khẩu, phân quyền nghiêm ngặt, chống SQL Injection/XSS/CSRF), giao diện responsive tương thích desktop và mobile.
---
## Giới Thiệu Về Repositories của tôi
Đây là nơi tôi lưu trữ toàn bộ source code của website và các tài liệu liên quan đến đồ án chuyên ngành. Toàn bộ nội dung nằm ở branch master trong repository này. Dưới đây là mô tả chi tiết:

-**src**: Đây là nơi lưu source code liên quan đến ứng dụng web (theo mô hình MVC) và cơ sở dữ liệu.

-**thesis**: Đây là nơi chứa các tập tin tài liệu văn bản, file thiết kế và báo cáo của Đồ án.

---

## Cài đặt
1. **Tải về ứng dụng web**:
   - Tải toàn bộ thư mục src từ repository này.
2. **Cài đặt XAMPP**:
   - Đảm bảo XAMPP (PHP 7.4+) đã được cài đặt trên máy tính.
   - Khởi động Apache và MySQL từ XAMPP Control Panel.
   - Thay đổi cổng kết nối trên XAMPP thành 81.
   - Truy cập http://localhost:81/phpmyadmin, tạo cơ sở dữ liệu mới (ví dụ: timvieclam_db).
   - Import file database/timvieclam_db.sql (nếu có trong thư mục thesis hoặc src) vào cơ sở dữ liệu vừa tạo.
3. **Thiết lập thư mục dự án**:
   - Vào thư mục htdocs của XAMPP (thường là C:\xampp\htdocs\).
   - Tạo thư mục mới (ví dụ: timvieclam) và giải nén toàn bộ source code vào đây.
   - Kiểm tra và chỉnh sửa file cấu hình kết nối database (thường là config/database.php hoặc tương tự) để đảm bảo thông tin host, username, password và tên database chính xác.
4. **Chạy ứng dụng**:
   - Mở trình duyệt và truy cập địa chỉ: http://localhost:81/timvieclam (hoặc tên thư mục bạn đã tạo).
   - Tài khoản admin mặc định (nếu có trong file SQL): cskh.timviec@gmail.com / 123456 (có thể thay đổi sau khi import dữ liệu mẫu).
---
## Sử dụng
- Đăng ký tài khoản mới → mặc định là ứng viên → có thể gửi yêu cầu nâng cấp lên nhà tuyển dụng.
- Tìm việc: Sử dụng thanh tìm kiếm và bộ lọc nâng cao trên trang chủ.
- Ứng tuyển: Chọn tin tuyển dụng → nhấn “Ứng tuyển ngay” → upload CV và viết thư.
- Nhà tuyển dụng: Sau khi được duyệt → truy cập dashboard để đăng tin và quản lý ứng viên.
- Quản trị viên: Đăng nhập tài khoản admin → truy cập phần quản trị để duyệt tin, duyệt nhà tuyển dụng, xem thống kê và quản lý danh mục.
- Chatbot: Nhấn biểu tượng chat ở góc dưới màn hình để được hỗ trợ tìm việc nhanh.
---
## Thông tin liên lạc
- Họ tên: Phạm Duy Tân
- Lớp: DA22TTD
- MSSV: 110122243
- Đơn vị: Trường Kỹ Thuật và Công Nghệ - Khoa Công Nghệ Thông Tin, Vĩnh Long
- Email: vietmobi4@gmail.com
- Số điện thoại: 0354975691
