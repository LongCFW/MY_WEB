# Hướng dẫn lấy Mật khẩu ứng dụng (App Password) cho Gmail

Để gửi được email qua SMTP trong dự án này, bạn không thể dùng mật khẩu Gmail thông thường. Bạn cần tạo App Password (16 ký tự).

## Các bước thực hiện

1. **Bật xác minh 2 bước (2-Step Verification):**
   - Truy cập: [https://myaccount.google.com/security](https://myaccount.google.com/security)
   - Tìm mục "Bảo mật và đăng nhập" và bật **Xác minh 2 bước**.

2. **Tạo Mật khẩu ứng dụng:**
   - Sau khi bật xác minh 2 bước, gõ từ khóa **"Mật khẩu ứng dụng"** hoặc **"App Passwords"** vào thanh tìm kiếm trên cùng của trang tài khoản Google.
   - Tại ô "Tên ứng dụng", nhập tên gợi nhớ (ví dụ: `EcoStore PHP`).
   - Nhấn **Tạo (Create)**.

3. **Cấu hình vào dự án:**
   - Một ô cửa sổ hiện ra chứa mã 16 ký tự (nền vàng). Hãy copy mã này.
   - Mở file `.env` của dự án, dán vào dòng:
     `MAIL_PASSWORD=chuỗi_16_ký_tự_vừa_copy`

*Lưu ý: Không chia sẻ mã này cho bất kỳ ai.*
