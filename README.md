
# EcoStore  PHP MVC Web Application
Dự án chuyển đổi (migration) nền tảng thương mại điện tử EcoStore từ ReactJS (SPA) sang PHP Thuần (Server-side Rendering) dựa trên mô hình MVC (Model-View-Controller) tự xây dựng.

Dự án tập trung vào các sản phẩm xanh, hữu cơ và thân thiện với môi trường, với giao diện hiện đại, hiệu ứng mượt mà và tối ưu hóa trải nghiệm người dùng.

Công Nghệ Sử Dụng (Tech Stack)
Backend: PHP 8.x (Hướng đối tượng - OOP, PDO Database).

Architecture: Custom MVC Pattern (Không dùng Framework có sẵn như Laravel/CI).

Frontend:

HTML5/CSS3: Tận dụng lại CSS module từ React, chuyển đổi sang CSS thuần (global.css, product.css, home.css...).

Framework: Bootstrap 5 (Grid system, Modal, Accordion, Offcanvas).

Icons: FontAwesome 6.

JavaScript: Vanilla JS (Xử lý DOM, Modal QuickView, Auto-submit Filter).

Database: MySQL.

Environment: Laragon (Apache Server).

Cấu Trúc Thư Mục (Project Structure)
Dự án tuân thủ nghiêm ngặt mô hình MVC để tách biệt logic và giao diện.

MY_WEB/
├── app/                        # Core logic của ứng dụng
│   ├── Controllers/            # Nơi xử lý yêu cầu từ người dùng
│   │   ├── Admin/              # Controllers cho trang quản trị (Dashboard, Product...)
│   │   └── Client/             # Controllers cho người dùng (Home, Product, Cart, Auth...)
│   ├── Models/                 # Tương tác với Database (Product, User, Category...)
│   ├── Views/                  # Giao diện hiển thị (HTML/PHP)
│   │   ├── admin/              # Views cho Admin
│   │   └── client/             # Views cho Khách hàng
│   │       ├── auth/           # Login, Register
│   │       ├── home/           # Trang chủ
│   │       ├── products/       # Danh sách, Chi tiết sản phẩm
│   │       ├── cart/           # Giỏ hàng
│   │       ├── checkout/       # Thanh toán
│   │       ├── offers/         # Trang ưu đãi
│   │       ├── about/          # Trang giới thiệu
│   │       └── layouts/        # Header, Footer chung
│   └── Core/                   # Lớp nền tảng (App, Controller, Database)
├── public/                     # Thư mục public ra ngoài (Web Root)
│   ├── assets/                 # CSS, JS, Images
│   └── index.php               # Entry point (Điểm vào duy nhất của ứng dụng)
├── config/                     # Cấu hình Database, Hằng số
└── README.md                   # Tài liệu dự án
Các Chức Năng Đã Hoàn Thiện
Phía Người Dùng (Client Side)
Trang Chủ (Home):

[x] Hero Carousel Slider.

[x] Danh mục nổi bật (Categories).

[x] Flash Sale & Sản phẩm mới nhất.

[x] Blog section.

Sản Phẩm (Product List):

[x] Hiển thị danh sách sản phẩm dạng Grid.

[x] Bộ lọc nâng cao (Sidebar): Lọc theo Danh mục, Khoảng giá (Checkbox logic OR), Thương hiệu.

[x] Sắp xếp: Giá tăng/giảm, Tên A-Z.

[x] Phân trang (Pagination): Logic tính toán LIMIT/OFFSET chuẩn xác.

[x] Quick View: Xem nhanh sản phẩm bằng Modal Bootstrap mà không cần load lại trang.

[x] Hiệu ứng Hover card sản phẩm (Overlay nút bấm).

Chi Tiết Sản Phẩm (Product Detail):

[x] Gallery ảnh (Ảnh chính + Thumbnails).

[x] Thông tin giá, SKU, rating.

[x] Chọn số lượng (Tăng/Giảm).

[x] Tab mô tả và thương hiệu.

[x] Hiển thị sản phẩm tương tự (Related Products).

Giỏ Hàng & Thanh Toán:

[x] Giao diện giỏ hàng (Step Wizard).

[x] Tính tổng tiền tạm tính.

[x] Giao diện Checkout (Chọn địa chỉ, Phương thức thanh toán).

Xác Thực (Auth):

[x] Đăng ký / Đăng nhập (Giao diện Split Layout hiện đại).

[x] Logic kiểm tra Session đăng nhập (user_logged_in).

[x] Hiển thị Avatar/Tên người dùng trên Header khi đã login.

[x] Đăng xuất.

Các trang tĩnh:

[x] Giới thiệu (About Us) - Storytelling layout.

[x] Ưu đãi (Offers) - Danh sách Voucher.

2. Phía Quản Trị (Admin Side)
[x] Cấu trúc Router bảo vệ (ProtectedRoute logic in PHP).

[x] Dashboard cơ bản.

[x] Khắc phục lỗi xung đột method getAllProducts giữa Client và Admin.

3. Kỹ Thuật & Fix Lỗi
[x] Fix SQL Strict Mode: Xử lý lỗi ONLY_FULL_GROUP_BY bằng hàm MIN().

[x] Fix 404 View: Chuẩn hóa đường dẫn thư mục client/auth/.

[x] Fix Frontend Logic: Chuyển đổi logic React State sang jQuery/Vanilla JS (Auto-submit form, Modal handling).

📝 Kế Hoạch Sắp Tới (To-Do List)
Hoàn thiện Logic Giỏ hàng (Backend):

Xử lý Session $_SESSION['cart'] thêm/xóa/sửa số lượng thực tế.

Lưu đơn hàng vào Database khi bấm "Đặt hàng".

Trang Cá Nhân (User Profile):

Hoàn thiện hiển thị lịch sử đơn hàng (Lấy từ DB).

Chức năng cập nhật thông tin cá nhân, đổi mật khẩu.

Sổ địa chỉ (CRUD).

Admin Dashboard:

Xây dựng giao diện CRUD cho Sản phẩm (Thêm, Sửa, Xóa ảnh, Biến thể).

Quản lý Đơn hàng (Duyệt đơn, Hủy đơn).

Thống kê doanh thu.

Tối ưu hóa:

Refactor CSS để gọn nhẹ hơn.

Validate dữ liệu kỹ càng hơn ở phía Server.

🛠️ Hướng Dẫn Cài Đặt & Chạy (Localhost)
Cài đặt môi trường:

Tải và cài đặt Laragon (khuyến nghị) hoặc XAMPP.

Khởi động Apache và MySQL.

Cấu hình Code:

Clone source code vào thư mục C:\laragon\www\MY_WEB.

Kiểm tra file app/Core/Database.php để đảm bảo thông tin đăng nhập MySQL đúng (Host, DB Name, User, Pass).

Cấu hình Database:

Tạo database tên ecostore (hoặc tên tương ứng trong config).

Import file SQL cấu trúc bảng (products, categories, users, product_variants, product_images...).

Lưu ý: Đảm bảo bảng product_variants có cột stock hoặc quantity khớp với Model.

Truy cập:

Mở trình duyệt và vào đường dẫn:

Trang chủ: http://localhost/MY_WEB/public/

Admin: http://localhost/MY_WEB/public/admin/