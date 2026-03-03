<?php
namespace App\Controllers\Client;

use App\Core\Controller;
use Google\Client;
use Google\Service\Oauth2;
class AuthController extends Controller {
    
    // 1. Trang Đăng nhập
    public function login() {
        if (isset($_SESSION['user_logged_in'])) {
            header('Location: /MY_WEB/public/');
            exit();
        }

        // Tạo link đăng nhập Google
        $client = $this->getGoogleClient();
        $googleLoginUrl = $client->createAuthUrl();

        // Xử lý lỗi từ callback trả về (nếu có)
        $error = $_GET['error'] ?? null;
        $errorMsg = null;
        if ($error === 'locked') $errorMsg = "Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.";
        
        $this->view('client/auth/login', ['googleLoginUrl' => $googleLoginUrl, 'error' => $errorMsg]);
    }

    // 2. Xử lý Đăng nhập
    public function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $userModel = $this->model('User');
            $user = $userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password_hash'])) {
                
                // Kiểm tra trạng thái tài khoản
                if ($user['status'] == 0) {
                    $this->view('client/auth/login', ['error' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.']);
                    return;
                }

                // Kiểm tra đã xác thực email chưa
                if (isset($user['email_verified']) && $user['email_verified'] == 0) {
                    $this->view('client/auth/login', ['error' => 'Vui lòng xác thực Email trước khi đăng nhập.']);
                    return;
                }

                // Kiểm tra xem có phải khách hàng không (hoặc admin cũng cho login kiểu user)
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_avatar'] = $user['avatar_url']; // Lưu avatar để hiện ở Header

                header('Location: /MY_WEB/public/');
            } else {
                // Truyền lỗi về view
                $this->view('client/auth/login', ['error' => 'Email hoặc mật khẩu không đúng']);
            }
        }
    }

    // 3. Trang Đăng ký
    public function register() {
        $this->view('client/auth/register');
    }

    // 4. Xử lý Đăng ký
    // 4. Xử lý Đăng ký (Trả về JSON cho AJAX)
    public function handleRegister() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json'); // Set header JSON
            
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $phone = $_POST['phone'];

            $userModel = $this->model('User');
            
            if ($userModel->findByEmail($email)) {
                echo json_encode(['status' => 'error', 'message' => 'Email đã tồn tại']); return;
            }

            if ($userModel->checkPhoneExists($phone)) {
                echo json_encode(['status' => 'error', 'message' => 'Số điện thoại này đã được sử dụng!']); return;
            }

            // Sinh mã OTP 6 số ngẫu nhiên
            $otp = sprintf("%06d", mt_rand(1, 999999));

            $data = [
                'name' => $name,
                'email' => $email,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                'phone' => $phone,
                'role_id' => 4, 
                'status' => 1,                
                'email_verified' => 0, 
                'verification_token' => $otp // Dùng cột này lưu OTP luôn
            ];

            // Lưu vào DB (Giả sử hàm create hoạt động tốt, ta lấy lại user qua email để lấy ID)
            if ($userModel->create($data)) {
                $newUser = $userModel->findByEmail($email);
                $userId = $newUser['id'];

                // Gửi Email chứa mã OTP
                $subject = "Mã xác thực tài khoản EcoStore";
                $body = "
                    <div style='font-family: Arial; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; text-align: center;'>
                        <h2 style='color: #2e7d32;'>Mã Xác Thực Của Bạn</h2>
                        <p>Xin chào <strong>$name</strong>,</p>
                        <p>Vui lòng nhập mã OTP gồm 6 chữ số dưới đây để hoàn tất đăng ký:</p>
                        <div style='font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #2e7d32; margin: 20px 0; padding: 15px; background: #f1f8f5; border-radius: 8px;'>$otp</div>
                        <p style='color: #777; font-size: 13px;'>Mã này chỉ dùng một lần. Tuyệt đối không chia sẻ cho người khác.</p>
                    </div>
                ";

                require_once '../app/Utils/MailHelper.php';
                \App\Utils\MailHelper::sendMail($email, $name, $subject, $body);

                // Trả về thành công kèm ID để Client bước sang màn hình nhập OTP
                echo json_encode(['status' => 'success', 'user_id' => $userId, 'message' => 'Vui lòng kiểm tra email để lấy mã xác nhận.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Có lỗi xảy ra khi tạo tài khoản.']);
            }
        }
    }

    // 5. Xử lý Kiểm tra OTP
    public function verifyOTP() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            $userId = $_POST['user_id'] ?? '';
            $otp = $_POST['otp'] ?? '';

            if(empty($userId) || empty($otp)) {
                echo json_encode(['status' => 'error', 'message' => 'Thiếu thông tin xác thực.']); return;
            }

            $userModel = $this->model('User');
            $user = $userModel->findById($userId);

            if (!$user) {
                echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy tài khoản.']); return;
            }

            // Kiểm tra OTP có khớp không
            if ($user['verification_token'] === $otp) {
                // Khớp -> Cập nhật trạng thái
                $userModel->verifyEmailByToken($otp); // Hàm này bạn đã viết trong Model
                
                // (Tùy chọn) Tự động đăng nhập luôn cho khách
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_avatar'] = $user['avatar_url'] ?? '';

                echo json_encode(['status' => 'success', 'message' => 'Xác thực thành công! Hệ thống đang chuyển hướng...']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Mã xác thực không chính xác!']);
            }
        }
    }

    // 5. Đăng xuất
    public function logout() {
        unset($_SESSION['user_logged_in']);
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_avatar']);
        session_destroy();
        header('Location: /MY_WEB/public/auth/login');
    }

    // ... bên trong class AuthController

    // 6. Trang Quên mật khẩu
    public function forgotPassword() {
        $this->view('client/auth/forgot_password');
    }

    // 7. Xử lý gửi yêu cầu reset (Trả về JSON + Gửi Mail)
    public function handleForgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            $email = $_POST['email'];

            $userModel = $this->model('User');
            $user = $userModel->findByEmail($email);

            if ($user) {
                // Tạo mã OTP 6 số
                $otp = sprintf("%06d", mt_rand(1, 999999));
                // Set thời gian hết hạn là 15 phút tính từ hiện tại
                $expiry = date('Y-m-d H:i:s', time() + 15 * 60);

                if ($userModel->saveResetToken($user['id'], $otp, $expiry)) {
                    // Gửi Email
                    $subject = "OTP Khôi phục mật khẩu - EcoStore";
                    $body = "
                        <div style='font-family: Arial; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; text-align: center;'>
                            <h2 style='color: #2e7d32;'>Khôi Phục Mật Khẩu</h2>
                            <p>Xin chào <strong>{$user['name']}</strong>,</p>
                            <p>Bạn vừa yêu cầu đặt lại mật khẩu. Vui lòng nhập mã OTP dưới đây để tiếp tục:</p>
                            <div style='font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #2e7d32; margin: 20px 0; padding: 15px; background: #f1f8f5; border-radius: 8px;'>$otp</div>
                            <p style='color: #777; font-size: 13px;'>Mã này sẽ hết hạn sau 15 phút. Tuyệt đối không chia sẻ cho người khác.</p>
                        </div>
                    ";

                    require_once '../app/Utils/MailHelper.php';
                    \App\Utils\MailHelper::sendMail($email, $user['name'], $subject, $body);

                    echo json_encode(['status' => 'success', 'user_id' => $user['id'], 'message' => 'Mã OTP đã được gửi đến email của bạn.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống. Vui lòng thử lại.']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Email này không tồn tại trong hệ thống.']);
            }
        }
    }

    // 7.5 [MỚI] API Kiểm tra mã OTP do khách nhập
    public function verifyResetOTP() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            $userId = $_POST['user_id'] ?? '';
            $otp = $_POST['otp'] ?? '';

            $userModel = $this->model('User');
            if ($userModel->checkResetToken($userId, $otp)) {
                // Đúng OTP -> Cấp quyền truy cập trang đổi mật khẩu qua Session
                $_SESSION['reset_user_id'] = $userId;
                echo json_encode(['status' => 'success', 'message' => 'Xác thực thành công!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Mã OTP không đúng hoặc đã hết hạn!']);
            }
        }
    }

    // 8. Trang Đặt lại mật khẩu
    public function resetPassword() {
        if (!isset($_SESSION['reset_user_id'])) {
            header('Location: /MY_WEB/public/auth/login');
            exit;
        }
        $this->view('client/auth/reset_password');
    }

    // 9. Xử lý cập nhật mật khẩu mới
    public function handleResetPassword() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_SESSION['reset_user_id'])) {
                header('Location: /MY_WEB/public/auth/login');
                exit;
            }

            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];

            if ($password !== $confirmPassword) {
                $this->view('client/auth/reset_password', ['error' => 'Mật khẩu xác nhận không khớp']);
                return;
            }

            $userId = $_SESSION['reset_user_id'];
            $userModel = $this->model('User');
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            if ($userModel->updatePassword($userId, $hashedPassword)) {
                // Đổi pass xong thì xóa mã OTP đi cho an toàn
                $userModel->clearResetToken($userId);
                
                unset($_SESSION['reset_user_id']);
                echo "<script>alert('Đổi mật khẩu thành công! Vui lòng đăng nhập lại.'); window.location.href='/MY_WEB/public/auth/login';</script>";
            } else {
                $this->view('client/auth/reset_password', ['error' => 'Có lỗi xảy ra, vui lòng thử lại.']);
            }
        }
    }

    // Xử lý xác thực Email khi click từ Link
    public function verifyEmail() {
        $token = $_GET['token'] ?? '';
        
        if (empty($token)) {
            echo "<script>alert('Đường dẫn không hợp lệ!'); window.location.href='/MY_WEB/public/auth/login';</script>";
            return;
        }
        
        // Sử dụng Model User để thao tác Database (Chuẩn MVC - Đã fix lỗi)
        $userModel = $this->model('User');
        $userModel->verifyEmailByToken($token);
        
        // Vì đây là MVC đơn giản, ta báo thành công luôn
        echo "<script>
                alert('Xác thực Email thành công! Bây giờ bạn có thể đăng nhập.'); 
                window.location.href='/MY_WEB/public/auth/login';
              </script>";
    }

    // --- [MỚI] HÀM CẤU HÌNH GOOGLE CLIENT ---
    private function getGoogleClient() {
        // Đảm bảo thư viện được load (nếu index.php chưa gọi autoload)
        if (file_exists('../vendor/autoload.php')) {
            require_once '../vendor/autoload.php';
        }

        $client = new \Google\Client();
        // Lấy thông tin bảo mật từ file .env thay vì viết trực tiếp
        $client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
        $client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
        $client->setRedirectUri($_ENV['GOOGLE_REDIRECT_URI']);
        
        $client->addScope("email");
        $client->addScope("profile");
        
        return $client;
    }

    // --- [MỚI] HÀM XỬ LÝ SAU KHI GOOGLE TRẢ VỀ ---
    // --- HÀM XỬ LÝ SAU KHI GOOGLE TRẢ VỀ ---
    public function googleCallback() {
        $client = $this->getGoogleClient();

        if (isset($_GET['code'])) {
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);            
            if (!isset($token['error'])) {
                $client->setAccessToken($token['access_token']);
                
                // Khởi tạo service Oauth2
                $google_oauth = new Oauth2($client);
                $google_account_info = $google_oauth->userinfo->get();
                
                $email =  $google_account_info->email;
                $name =  $google_account_info->name;
                $google_id = $google_account_info->id;
                $avatar = $google_account_info->picture;

                $userModel = $this->model('User');
                $user = $userModel->findByEmail($email);

                if ($user) {
                    // Nếu user đã tồn tại, kiểm tra và cập nhật Google ID
                    if (empty($user['google_id'])) {
                        $userModel->updateGoogleId($user['id'], $google_id, $avatar);
                    }
                } else {
                    // Nếu user chưa tồn tại, tạo mới
                    $data = [
                        'name' => $name,
                        'email' => $email,
                        'password_hash' => password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT),
                        'phone' => '', 
                        'role_id' => 4,
                        'status' => 1,
                        'email_verified' => 1,
                        'google_id' => $google_id,
                        'avatar_url' => $avatar
                    ];
                    $userModel->create($data);
                    $user = $userModel->findByEmail($email);
                }

                if ($user['status'] == 0) {
                    header('Location: /MY_WEB/public/auth/login?error=locked');
                    exit;
                }

                // Thiết lập session cho người dùng
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_avatar'] = $user['avatar_url'] ?? $avatar;
                
                $_SESSION['login_method'] = 'google';

                // Chuyển hướng người dùng về trang chủ sau khi đăng nhập thành công
                header('Location: /MY_WEB/public/');
                exit();
            }
        }
        
        // Chuyển hướng về trang đăng nhập nếu có lỗi
        header('Location: /MY_WEB/public/auth/login?error=google_failed');
        exit();
    }
}