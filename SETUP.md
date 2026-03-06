# Hướng Dẫn Cài Đặt (Setup Guide)

## Yêu cầu trước khi cài đặt

Đảm bảo máy của bạn đã cài đặt các công cụ sau:

- PHP 8.x
- Composer
- MySQL
- Web Server (Laragon hoặc XAMPP)
- Git (để clone repository)

## Bước 1: Tải mã nguồn (Clone Project)

Mở Terminal (hoặc Git Bash) tại thư mục web của bạn (ví dụ: `C:\laragon\www`) và chạy lệnh:

```bash
git clone https://github.com/LongCFW/MY_WEB.git
cd MY_WEB
```

---

## Bước 2: Cài Đặt Thư Viện (Composer)

Đảm bảo bạn đang ở trong thư mục dự án `MY_WEB`. Chạy lệnh sau để tải các thư viện cần thiết (PHPMailer, Google API, DotEnv...):

```bash
composer update
```

*(Lệnh này sẽ tạo ra thư mục `vendor/` dựa trên cấu hình từ `composer.json`).*

---

## Bước 3: Cấu Hình Biến Môi Trường (.env)

1. Trong thư mục gốc của dự án, copy file `.env.example` và đổi tên bản copy thành `.env`.
2. Mở file `.env` và cập nhật thông tin kết nối Database của bạn.

### Lưu ý trước khi cấu hình file `.env`

- **Lưu ý 1:** `"DB_PASS="` sẽ tùy theo mật khẩu phần mềm quản lý db của bạn. Ví dụ mật khẩu là `abc123` thì `DB_PASS="abc123"`.
- **Lưu ý 2:** `"MAIL_USERNAME"` cũng là mail mà bạn sẽ nhận được thông báo từ client khi client thực hiện chức năng liên quan đến gửi mail.
- **Lưu ý 3:** Nếu Database của bạn đã có tên tương tự, thì chỉ cần đổi `DB_NAME` trong file `.env` và nhập đúng tên `DB_NAME` mà bạn set trong `.env` vào phần mềm quản lý Database của bạn là ok.

```env
DB_HOST=localhost
DB_NAME=ecostore
DB_USER=root
DB_PASS=
```

### Hướng dẫn lấy thông tin cấu hình

1. Cách tạo mật khẩu ứng dụng Email:  
   [GET_APP_PASSWORD.md](./GET_APP_PASSWORD.md)

2. Cách lấy Google Client ID & Secret:  
   [GET_ID_AND_SECRET_OF_GOOGLE.md](./GET_ID_AND_SECRET_OF_GOOGLE.md)

```env
Cấu hình gửi Mail & Đăng nhập Google (Điền thông tin của bạn để test tính năng):

MAIL_USERNAME=your_email@gmail.com (email này sẽ được dùng để tạo App Password)
MAIL_PASSWORD=your_app_password (sau khi tạo app password theo hướng dẫn thành công thì copy chuỗi đó và nhập vào đây)
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
```

---

## Bước 4: Cài Đặt Cơ Sở Dữ Liệu (Database)

Dự án đã đính kèm sẵn file `ecostore.sql` bao gồm cấu trúc bảng và dữ liệu mẫu (Sản phẩm, Đơn hàng, Tài khoản...).

1. Mở phần mềm quản lý Database (phpMyAdmin, HeidiSQL...).
2. Tạo một Database mới có tên giống với `DB_NAME` đã đặt.  
   Ví dụ `DB_NAME=ecostore` thì tên Database sẽ là `ecostore`.  
   *(Collation khuyến nghị: `utf8mb4_unicode_ci`).*
3. Chọn tính năng **Import (Nhập)** và tải lên file `ecostore.sql` từ thư mục dự án.
4. Bấm **Go (Thực hiện)** để hoàn tất.

---

## Bước 5: Truy Cập Website

Khởi động Apache và MySQL trên Laragon (hoặc XAMPP). Mở trình duyệt và truy cập vào đường dẫn:

- **Nếu dùng Laragon (Virtual Host):**  
  `http://my_web.test`

- **Nếu dùng XAMPP / Localhost thường:**  
  `http://localhost/MY_WEB/public`

---

## Tài Khoản Mặc Định (Dành cho Test)

Hệ thống đã có sẵn các tài khoản sau trong Database để bạn kiểm thử các chức năng:

### 1. Tài khoản Quản trị viên

- **Email:** `admin@ecostore.com`
- **Mật khẩu:** `123456`

*Hướng dẫn:* Sau khi đăng nhập ở trang chủ, click vào Avatar góc phải trên Header, chọn **"Vào Admin Dashboard"** để truy cập trang quản trị.

---

### 2. Tài khoản Quản lý

- **Email:** `manager@ecostore.com`
- **Mật khẩu:** `123456`

---

### 3. Tài khoản Nhân viên

- **Email:** `staff@ecostore.com`
- **Mật khẩu:** `123456`

---

### 4. Tài khoản Khách hàng

- **Email:** `khachhang@gmail.com`
- **Mật khẩu:** `123456`
