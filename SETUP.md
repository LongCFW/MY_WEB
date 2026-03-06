# Hướng Dẫn Cài Đặt (Setup Guide)

## Bước 1: Tải mã nguồn (Clone Project)

Mở Terminal (hoặc Git Bash) tại thư mục web của bạn (ví dụ: `C:\laragon\www`) và chạy lệnh:

1. git clone [https://github.com/LongCFW/MY_WEB.git](https://github.com/LongCFW/MY_WEB.git)
2. cd MY_WEB

## Bước 2: Cài Đặt Thư Viện (Composer)

Đảm bảo bạn đang ở trong thư mục dự án `MY_WEB`. Chạy lệnh sau để tải các thư viện cần thiết (PHPMailer, Google API, DotEnv...):

composer update

*(Lệnh này sẽ tạo ra thư mục `vendor/` dựa trên cấu hình từ `composer.json`)*.

## Bước 3: Cấu Hình Biến Môi Trường (.env)

1. Trong thư mục gốc của dự án, copy file `.env.example` và đổi tên bản copy thành `.env`.
2. Mở file `.env` và cập nhật thông tin kết nối Database của bạn:

```env:
DB_HOST=localhost
DB_NAME=ecostore
DB_USER=root
DB_PASS=

Cấu hình gửi Mail & Đăng nhập Google (Điền thông tin của bạn để test tính năng):
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
```

## Bước 4: Cài Đặt Cơ Sở Dữ Liệu (Database)

Dự án đã đính kèm sẵn file `ecostore.sql` bao gồm cấu trúc bảng và dữ liệu mẫu (Sản phẩm, Đơn hàng, Tài khoản...).

1. Mở phần mềm quản lý Database (phpMyAdmin, HeidiSQL...).
2. Tạo một Database mới có tên là `ecostore` (Collation khuyến nghị: `utf8mb4_unicode_ci`).
3. Chọn tính năng **Import** (Nhập) và tải lên file `ecostore.sql` từ thư mục dự án.
4. Bấm **Go** (Thực hiện) để hoàn tất.

## Bước 5: Truy Cập Website

Khởi động Apache và MySQL trên Laragon (hoặc XAMPP). Mở trình duyệt và truy cập vào đường dẫn:

* **Nếu dùng Laragon (Virtual Host):** `http://my_web.test`
* **Nếu dùng XAMPP / Localhost thường:** `http://localhost/MY_WEB/public`

---

## Tài Khoản Mặc Định (Dành cho Test)

Hệ thống đã có sẵn các tài khoản sau trong Database để bạn kiểm thử các chức năng:

1. **Tài khoản Quản trị viên**
   * **Email:** `admin@ecostore.com`
   * **Mật khẩu:** `123456`
   * *Hướng dẫn:* Sau khi đăng nhập ở trang chủ, click vào Avatar góc phải trên Header, chọn **"Vào Admin Dashboard"** để truy cập trang quản trị.

2. **Tài khoản Quản lý**
   * **Email:** `manager@ecostore.com`
   * **Mật khẩu:** `123456`

3. **Tài khoản Nhân viên**
   * **Email:** `staff@ecostore.com`
   * **Mật khẩu:** `123456`  

4. **Tài khoản Khách hàng**
   * **Email:** `khachhang@gmail.com`
   * **Mật khẩu:** `123456`
