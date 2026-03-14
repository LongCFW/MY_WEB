# Hướng Dẫn Cài Đặt (Setup Guide)

## Yêu cầu trước khi cài đặt

Đảm bảo máy của bạn đã cài đặt các công cụ sau:

- PHP 8.x
- Composer
- MySQL
- Web Server (Laragon hoặc XAMPP)
- Git (để clone repository)

**Lưu ý:** Trong trường hợp **không clone bằng Git** mà chạy dự án bằng **file source code (.zip)** được cung cấp sẵn (ví dụ: file nén đồ án), trong thư mục dự án có thể **không có sẵn hai thư mục `vendor/` và `node_modules/`**

**Để dự án hoạt động chính xác, thư mục dự án bắt buộc phải được đặt trực tiếp trong thư mục gốc của Web Server và không được lồng vào thư mục con khác.**

Ví dụ đúng:
laragon/www/MY_WEB
xampp/htdocs/MY_WEB

Ví dụ sai:
laragon/www/test/MY_WEB
xampp/htdocs/project/MY_WEB

Để khôi phục lại đầy đủ các thư viện cần thiết, hãy thực hiện các lệnh sau khi đã mở Terminal tại thư mục dự án:

```bash
cd MY_WEB
composer install 
npm install
```

*Sau khi hai thư mục này được cài đặt xong, có thể tiếp tục thực hiện từ **Bước 3: Cấu Hình Biến Môi Trường (.env)**.*

## Bước 1: Tải Mã Nguồn (Clone Project)

Để dự án hoạt động chính xác, mã nguồn **bắt buộc phải được đặt trực tiếp trong thư mục gốc của Web Server** (không được lồng vào thư mục con nào khác).

### Cách thực hiện an toàn nhất (Dành cho mọi ổ đĩa/vị trí cài đặt)

1. Mở **File Explorer (My Computer)** và tìm đến thư mục cài đặt gốc của Web Server:
   - Nếu dùng **Laragon**: Tìm thư mục `www`  
     Ví dụ: `C:\laragon\www` hoặc `D:\laragon\www`
   - Nếu dùng **XAMPP**: Tìm thư mục `htdocs`  
     Ví dụ: `C:\xampp\htdocs` hoặc `D:\xampp\htdocs`

2. Mở Terminal tại thư mục này bằng 1 trong 2 cách sau:
   - **Cách 1 (Khuyên dùng - Sử dụng VS Code):** Click chuột phải vào khoảng trống trong thư mục, chọn **"Open with Code"**. Sau khi VS Code mở lên, nhấn tổ hợp phím `` Ctrl + ` `` (hoặc trên thanh menu chọn *Terminal > New Terminal*) để mở cửa sổ dòng lệnh.
   - **Cách 2 (Mở Terminal ngoài):** Click chuột phải vào khoảng trống và chọn **"Open in Terminal"** hoặc **"Open Git Bash here"**.

3. Copy và chạy lần lượt 2 dòng lệnh sau để tải code về và di chuyển hẳn vào bên trong thư mục dự án:

```bash
git clone https://github.com/LongCFW/MY_WEB.git MY_WEB
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

- **Lưu ý 1:** `"DB_PASSWORD="` sẽ tùy theo mật khẩu phần mềm quản lý db của bạn. Ví dụ mật khẩu là `abc123` thì `DB_PASSWORD="abc123"`.
- **Lưu ý 2:** `"MAIL_USERNAME"` cũng là mail mà bạn sẽ nhận được thông báo từ client khi client thực hiện chức năng liên quan đến gửi mail.

```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=ecostore
DB_USERNAME=root
DB_PASSWORD=
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
GOOGLE_CLIENT_ID="your_google_client_id"
GOOGLE_CLIENT_SECRET="your_google_client_secret"
```

---

## Bước 4: Cài Đặt Cơ Sở Dữ Liệu (Database)

Dự án đã đính kèm sẵn file `ecostore.sql` bao gồm cấu trúc bảng và dữ liệu mẫu (Sản phẩm, Đơn hàng, Tài khoản...).

- **Lưu ý:** Nếu Database của bạn đã có tên tương tự, thì chỉ cần đổi `DB_DATABASE` trong file `.env` và nhập đúng tên `DB_DATABASE` mà bạn set trong `.env` vào phần mềm quản lý Database của bạn là ok.

1. Mở phần mềm quản lý Database (phpMyAdmin, HeidiSQL...).
2. Tạo một Database mới có tên giống với `DB_DATABASE` đã đặt.  
   Ví dụ `DB_DATABASE=ecostore` thì tên Database sẽ là `ecostore`.  
   *(Collation khuyến nghị: `utf8mb4_unicode_ci`).*
3. Chọn tính năng **Import (Nhập)** và tải lên file `ecostore.sql` từ thư mục dự án.
4. Bấm **Go (Thực hiện)** để hoàn tất.

---

## Bước 5: Truy Cập Website

1. Khởi động **Apache** và **MySQL** trên Web Server của bạn (Laragon hoặc XAMPP).

2. Mở trình duyệt và truy cập vào đường dẫn sau để hệ thống nhận diện đúng cấu hình MVC:

**[http://localhost/MY_WEB/public/](http://localhost/MY_WEB/public/)**

**LƯU Ý ĐẶC BIỆT QUAN TRỌNG:**
**Vị trí đặt thư mục:** Tuyệt đối KHÔNG lồng thư mục `MY_WEB` vào bên trong một thư mục khác (ví dụ: `www/test/MY_WEB/`). Nếu sai cấp thư mục, giao diện sẽ bị vỡ và báo lỗi 404.
**Không dùng Virtual Host:** Tuyệt đối KHÔNG sử dụng tính năng Auto Virtual Host (như `http://my_web.test` của Laragon). Dự án đã được cấu hình đường dẫn tĩnh bảo mật theo thư mục `public`, việc dùng tên miền ảo sẽ gây xung đột URL Rewrite.

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

- **Mẹo trải nghiệm:** Các tài khoản mẫu ở trên giúp bạn xem nhanh giao diện. Tuy nhiên, để test trọn vẹn các tính năng thực tế (như nhận email xác nhận đơn hàng, đăng nhập bằng Google...), bạn hãy tự **Đăng ký** một tài khoản mới bằng email thật của chính mình nhé!
