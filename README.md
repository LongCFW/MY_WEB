# Ecostore – Website Bán Hàng Nông Sản Hữu Cơ

**Ecostore** là hệ thống thương mại điện tử chuyên cung cấp các sản phẩm xanh, sạch. Website được xây dựng từ đầu (From Scratch) theo mô hình **Pure MVC** (Model-View-Controller) của PHP, không sử dụng Framework có sẵn, nhằm tối ưu hiệu năng và dễ dàng kiểm soát luồng dữ liệu.

Điểm đặc biệt của dự án là logic xử lý **Quản lý tồn kho chặt chẽ (Stock Management)** và **Bảo mật phiên làm việc thời gian thực**.

---

## Công nghệ sử dụng

    Backend
    **Ngôn ngữ:** PHP (Pure MVC Architecture).
    **Database:** MySQL.
    **Core Logic:**
    * `PDO`: Kết nối cơ sở dữ liệu an toàn, chống SQL Injection.
    * `Transaction`: Xử lý giao dịch khi thanh toán để đảm bảo toàn vẹn dữ liệu.
    * `Session Management`: Quản lý phiên đăng nhập riêng biệt cho Admin và User.

    Frontend
    **HTML5 / CSS3**: Giao diện hiện đại, Responsive.
    **Bootstrap 5**: Framework UI chính (Grid system, Modals, Forms).
    **JavaScript / jQuery**: Xử lý các tác vụ phía client.
    **AJAX**: Thêm vào giỏ hàng, Yêu thích sản phẩm (Wishlist) không cần tải lại trang.
    **FontAwesome**: Bộ icon.

---

## Tính năng nổi bật

    1. Quản lý Tồn kho & Đơn hàng (Advanced)
    
    **Kiểm soát tồn kho thực tế:**
    * Hiển thị trạng thái "Hết hàng" (Badge, làm mờ sản phẩm) ngay trên giao diện khi `stock = 0`.
    * Chặn thêm vào giỏ hàng nếu số lượng mua lớn hơn số lượng tồn.
    
    **Xử lý chống Bán khống (Overselling):**
    * Sử dụng **Database Transaction** và **Row Locking** (`SELECT ... FOR UPDATE`) tại bước thanh toán.
    * Đảm bảo tính nhất quán dữ liệu khi có nhiều người cùng mua một sản phẩm cuối cùng.
    
    **Doanh thu thực:** Hệ thống chỉ tính doanh thu cho các đơn hàng có trạng thái `Completed`.

    2. Khách hàng (Client Side)
    **Xác thực:** Đăng ký, Đăng nhập, Quên mật khẩu (Check trùng Email/SĐT), Đổi mật khẩu.
    
    **Mua sắm:**
    * Tìm kiếm sản phẩm (Modal Global Search).
    * Lọc sản phẩm theo Danh mục, Khoảng giá, Thương hiệu.
    * Sắp xếp sản phẩm (Giá tăng/giảm, Tên A-Z).

    **Giỏ hàng & Thanh toán:**
    * Giỏ hàng lưu trong Database (Persistent Cart) cho thành viên.
    * Thanh toán COD (Cash On Delivery).
    
    **Tiện ích:**
    * **Wishlist:** Thả tim sản phẩm (AJAX), lưu danh sách yêu thích.
    * **Lịch sử đơn hàng:** Xem lại đơn hàng đã mua và trạng thái xử lý.

    3. Quản trị viên (Admin Dashboard)
    **Dashboard:**
    * Thống kê tổng quan: Doanh thu, Tổng đơn, Tổng khách hàng, Tổng sản phẩm.
    * Biểu đồ hoặc bảng danh sách đơn hàng mới nhất.
    
    **Quản lý Sản phẩm (CRUD):** Thêm, sửa, xóa, cập nhật hình ảnh, giá, tồn kho.
    
    **Quản lý Danh mục:** Tạo và chỉnh sửa danh mục sản phẩm.
    
    **Quản lý Đơn hàng:**
    * Xem chi tiết đơn hàng (Items, địa chỉ, tổng tiền).
    * Cập nhật trạng thái đơn hàng (Pending -> Shipping -> Completed/Cancelled).
    * **Lịch sử đơn hàng:** Ghi log ai là người cập nhật trạng thái và vào thời gian nào.
    
    **Quản lý Khách hàng:**
    * Xem danh sách người dùng.
    * **Real-time Blocking:** Khóa tài khoản người dùng. Người dùng sẽ bị đăng xuất ngay lập tức (Force Logout) nếu đang online.

---

## Cấu trúc dự án

    Cấu trúc thư mục được tổ chức khoa học, phân tách rõ ràng giữa Admin và Client:
    ```text
    MY_WEB/
    ├── app/                            # Chứa toàn bộ logic ứng dụng
    │   ├── Controllers/                # Xử lý yêu cầu từ người dùng (Điều phối)
    │   │   ├── Admin/                  # Các Controller dành cho trang quản trị (Dashboard, Product, Order...)
    │   │   └── Client/                 # Các Controller dành cho khách hàng (Home, Cart, Checkout...)
    │   ├── Core/                       # Lớp lõi của Framework tự build
    │   │   │   ├── App.php             # Router, xử lý URL
    │   │   ├── Controller.php          # Base Controller, tích hợp Middleware bảo mật
    │   │   └── Database.php            # Wrapper PDO, xử lý kết nối và Transaction
    │   ├── Helpers/                    # Các hàm hỗ trợ (Format tiền, Date...)
    │   ├── Models/                     # Tương tác trực tiếp với Database (CRUD)
    │   ├── Utils/                      # Các tiện ích mở rộng
    │   └── Views/                      # Giao diện hiển thị (HTML/PHP)
    │       ├── admin/                  # Giao diện Dashboard (Categories, Orders, Products, Users...)
    │       ├── auth/                   # Giao diện Đăng nhập / Đăng ký chung
    │       ├── client/                 # Giao diện phía người dùng
    │       │   ├── account/            # Trang cá nhân
    │       │   ├── cart/               # Giỏ hàng
    │       │   ├── checkout/           # Thanh toán
    │       │   ├── home/               # Trang chủ
    │       │   └── products/           # Danh sách sản phẩm
    │       └── layouts/                # Các file bố cục chung (Header, Footer, Sidebar)
    ├── config/                         # Chứa file cấu hình Database
    ├── node_modules/                   # Thư viện Frontend (nếu dùng npm)
    ├── public/                         # Thư mục gốc truy cập của Web Server
    │   ├── assets/                     # Tài nguyên tĩnh
    │   │   ├── css/                    # File style (Global, Admin, Home...)
    │   │   ├── images/                 # Hình ảnh giao diện
    │   │   ├── js/                     # File Javascript xử lý sự kiện
    │   │   └── uploads/                # Chứa ảnh sản phẩm/avatar do người dùng upload
    │   ├── .htaccess                   # Cấu hình rewrite URL
    │   └── index.php                   # Điểm khởi chạy ứng dụng (Entry Point)
    ├── vendor/                         # Thư viện Composer
    ├── .env                            # Biến môi trường
    └── ecostore.sql                    # File backup Database
---

## Hướng dẫn cài đặt & chạy dự án

    Bước 1: Chuẩn bị môi trường
    Cài đặt Laragon (hoặc XAMPP) hỗ trợ PHP >= 7.4 và MySQL.

    Start Apache và MySQL.

    Bước 2: Giải nén thư mục 
    Di chuyển thư mục MY_WEB vào thư mục laragon/www để chạy dự án
    Hoặc
    Clone từ trên github [https://github.com/LongCFW/MY_WEB]
    Lệnh để clone "git clone https://github.com/LongCFW/MY_WEB.git"

    Bước 3: Cài đặt Database
    Truy cập [http://localhost/phpmyadmin]

    Tạo database mới tên: ecostore_db (hoặc tên tùy chỉnh trong app/Core/Database.php).

    Import file ecostore.sql (nằm trong thư mục root của dự án) vào database vừa tạo.

    Bước 4: Cấu hình
    Mở file app/Core/Database.php và cập nhật thông tin kết nối nếu cần (DB_NAME, USERNAME, PASSWORD).

    Bước 5: Truy cập
    Trang chủ: [http://localhost/MY_WEB/public/]

    Admin Dashboard: [http://localhost/MY_WEB/public/admin/login]

---

## Tài khoản demo

    **Admin**
    Email: [admin@ecostore.com]
    Password: 123456

    **Manager**
    Email: [manager@ecostore.com]
    Password: 123456

    **Staff**
    Email: [staff@ecostore.com]
    Password: 123456

    **Customer**
    Email: [demo@gmail.com]
    Password: 123456

---

## Hướng phát triển trong tương lai

    Voucher: Áp dụng mã giảm giá khi thanh toán, quản lý kho voucher 
    Thông báo: Gửi thông báo cập nhật tin tức cho người dùng real-time
    Thanh toán Online: Tích hợp API VNPay hoặc MoMo
    Email Marketing: Gửi email xác nhận đơn hàng tự động (PHPMailer)
    Reviews: Cho phép khách hàng đánh giá sao và bình luận sản phẩm
    Analytics: Biểu đồ doanh thu theo tháng/năm

---

## Tác giả

    **Họ tên**: Lê Nguyên Bảo Long (Brian Lê)
    **Email**: [imlongmanhme@gmail.com]
