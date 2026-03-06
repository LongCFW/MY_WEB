# Hướng dẫn lấy Google Client ID & Client Secret

Dùng cho tính năng đăng nhập bằng Google (OAuth 2.0).

## Các bước thực hiện

### 1. Truy cập Google Cloud Console

- Truy cập: [https://console.cloud.google.com/](https://console.cloud.google.com/)
- Nhấn **Select a project** (Chọn dự án).
- Nhấn **New Project** để tạo dự án mới.
- Đặt tên dự án tùy ý rồi nhấn **Create**.
- Chọn project mới vừa tạo.

---

### 2. Cấu hình OAuth Consent Screen

1. Trong menu bên trái (Dấu ba gạch) chọn:

   **APIs & Services → OAuth consent screen**

   - Lúc này màn hình sẽ hiện lên khung giao diện mới
   - Chọn **Overview**
   - Chọn tiếp **"Get started"**

2. Điền các thông tin bắt buộc:

   - **App name:** tên ứng dụng (ví dụ: EcoStore)
   - **User support email:** email của bạn

   Sau khi nhập xong chọn tiếp **Next**.

3. **Phần Audience**

   - Chọn **External**
   - Tiếp tục chọn **Next**

4. **Phần Contact Information**

   - Chọn email mà bạn muốn nhận thông báo từ Google
   - Tiếp tục chọn **Next**

5. **Phần Finish**

   - Check mục **"I agree..."**
   - Nhấn **Continue**
   - Nhấn **Create** để hoàn tất

---

### 3. Tạo OAuth Client ID

1. Ngay màn hình khi vừa tạo **OAuth Consent Screen** xong, nhìn bên trái chọn **Clients**:

   - Tìm mục **Create client** và chọn

2. Phần **Create OAuth client ID** hiện ra:

   - Chọn **Application type** là **Web application**
   - Đặt **name** bất kỳ

3. Kéo xuống phần **Authorized redirect URIs**

   - Dán URI sau vào:

```text
http://localhost/MY_WEB/public/auth/googleCallback
```

- Sau đó nhấn **Create**.

---

### 4. Lấy Client ID và Client Secret

Sau khi tạo xong, Google sẽ hiển thị:

- **Client ID**
- **Client Secret**

Copy hai giá trị này và dán vào file `.env` của dự án:

```env
GOOGLE_CLIENT_ID=xxxxxxxxxxxxxxxx
GOOGLE_CLIENT_SECRET=xxxxxxxxxxxxxxxx
```

---

### Lưu ý

- Email đăng nhập Google phải nằm trong **Test Users**, nếu không sẽ báo lỗi **Access blocked**.
- Redirect URI phải **giống 100%** với URI đã cấu hình, nếu khác sẽ xảy ra lỗi **redirect_uri_mismatch**.
- Nếu deploy lên server thật, cần thêm redirect URI của domain thật vào danh sách **Authorized redirect URIs**
