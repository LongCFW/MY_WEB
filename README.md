# EcoStore

**EcoStore** là một nền tảng **thương mại điện tử (E-commerce)** chuyên cung cấp các sản phẩm **nông sản và thực phẩm hữu cơ**.

Dự án được xây dựng **từ đầu bằng PHP thuần theo mô hình MVC (Object-Oriented Programming)**, **không sử dụng PHP Framework**, nhằm mục đích hiểu sâu về:

* Kiến trúc hệ thống
* Luồng xử lý request
* Cách tổ chức code theo mô hình MVC

---

## Các Tính Năng Nổi Bật

### Dành cho Khách hàng (Client)

#### Xác thực

* Đăng ký / Đăng nhập tài khoản
* **Đăng nhập bằng Google (OAuth 2.0)**
* Quên mật khẩu → gửi **OTP qua Email** (sử dụng PHPMailer)

#### Mua sắm

* Lọc sản phẩm nâng cao:
  * Theo **Danh mục**
  * Theo **Khoảng giá**
  * Theo **Số sao đánh giá**
  * Theo **Thương hiệu**

#### Giỏ hàng & Thanh toán

* Quản lý giỏ hàng bằng **Database**
* Thanh toán hỗ trợ:
  * **COD (Thanh toán khi nhận hàng)**
  * **Chuyển khoản ngân hàng (VietQR)**

#### Tài khoản cá nhân

Người dùng có thể:

* Quản lý thông tin cá nhân
* Quản lý **sổ địa chỉ**
* Thiết lập **địa chỉ mặc định**
* Xem **danh sách yêu thích (Wishlist)**
* Quản lý **Voucher**
* Nhận **thông báo hệ thống**

#### Tương tác

* Đánh giá sản phẩm
* Bình luận sản phẩm
*(Chỉ cho phép sau khi mua hàng thành công)*

---

## Chức Năng Quản Trị (Admin)

### Dashboard

* Thống kê **doanh thu**
* Thống kê **đơn hàng**
* Thống kê **sản phẩm**

### Quản lý đơn hàng

* Cập nhật trạng thái giao hàng
* Xác nhận **đã nhận tiền chuyển khoản**

### Quản lý sản phẩm

* Thêm / sửa / xóa sản phẩm
* Upload **nhiều hình ảnh**
* Hỗ trợ **Product Variants**

Ví dụ:

* 500g
* 1kg
* 2kg
*(mỗi biến thể có giá khác nhau)*

### Marketing

* Tạo **Coupon / Voucher**
* Giới hạn **số lượt sử dụng**

### Quản lý người dùng

* Khóa / mở khóa tài khoản
* Kiểm duyệt **đánh giá sản phẩm**

---

## Tech Stack

### Backend

* **PHP 8.3**
* OOP
* MVC Architecture

### Database

* **MySQL**

### Frontend

* HTML5
* CSS3
* **Bootstrap 5**
* Vanilla JavaScript
* AJAX (Fetch API)

### Thư viện sử dụng (Composer)

* `vlucas/phpdotenv` → Quản lý biến môi trường
* `phpmailer/phpmailer` → Gửi email (OTP, thông báo đơn hàng)
* `google/apiclient` → Đăng nhập bằng Google

---

## Cấu Trúc Thư Mục Chính

MY_WEB/
│
├── app/
│   ├── Controllers/     # Logic điều khiển (Admin, Client)
│   ├── Models/          # Tương tác Database
│   ├── Views/           # Giao diện (HTML + PHP)
│   ├── Core/            # Router, Database, Base Controller
│   └── Utils/           # Helper, Pagination, Upload...
│
├── public/              # Document Root
│   ├── assets/          # CSS, JS, Images, Uploads
│   ├── .htaccess        # URL Rewrite
│   └── index.php        # Entry Point của ứng dụng
│
├── config/              # File cấu hình hệ thống
├── vendor/              # Thư viện Composer (ignore trên git)
├── .env                 # Biến môi trường (Local)
└── ecostore.sql         # File Database mẫu

---

## Hướng Dẫn Cài Đặt

* Vui lòng xem chi tiết tại: [SETUP.md](./SETUP.md)
  * File này sẽ hướng dẫn:

    * Cài đặt môi trường (Laragon/XAMPP)

    * Import database

    * Cấu hình .env

    * Chạy project trên localhost

---

## Tác giả

* Dự án được phát triển bởi Mr. Long
