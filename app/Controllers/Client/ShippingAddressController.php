<?php
namespace App\Controllers\Client;

use App\Core\Controller;

class ShippingAddressController extends Controller {

    // --- 1. XỬ LÝ THÊM MỚI (INSERT) ---
    public function store() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_logged_in'])) {
            header('Location: /MY_WEB/public/auth/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id'];
            
            // Lấy dữ liệu từ form
            $fullName = $_POST['full_name'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $addressLine = $_POST['address_line'] ?? '';
            $city = $_POST['city'] ?? '';
            $province = $_POST['province'] ?? '';
            $postalCode = $_POST['postal_code'] ?? '';
            $country = $_POST['country'] ?? 'Vietnam';
            
            // Xử lý logic "Đặt làm mặc định"
            // Nếu user tick chọn hoặc đây là địa chỉ đầu tiên của họ -> set là 1
            $isDefault = isset($_POST['is_default']) ? 1 : 0;

            $model = $this->model('ShippingAddress');

            // Kiểm tra xem user đã có địa chỉ nào chưa, nếu chưa thì cái này bắt buộc là mặc định
            $existingAddresses = $model->getByUserId($userId);
            if (empty($existingAddresses)) {
                $isDefault = 1;
            }

            // Nếu cái này là mặc định, thì reset các cái cũ về 0
            if ($isDefault == 1) {
                $model->resetDefault($userId);
            }

            // Chuẩn bị dữ liệu Insert
            $data = [
                'user_id' => $userId,
                'full_name' => $fullName,
                'phone' => $phone,
                'address_line' => $addressLine,
                'city' => $city,
                'province' => $province,
                'postal_code' => $postalCode,
                'country' => $country,
                'is_default' => $isDefault,
                'created_at' => date('Y-m-d H:i:s') // Lấy thời gian hiện tại
            ];

            // Gọi hàm create có sẵn trong Core/Model
            $model->create($data);

            // Quay lại trang trước đó (ví dụ trang Checkout hoặc Profile)
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }

    // --- 2. XỬ LÝ CẬP NHẬT (UPDATE) ---
    public function update($id) {
        if (!isset($_SESSION['user_logged_in'])) {
            header('Location: /MY_WEB/public/auth/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id'];
            $model = $this->model('ShippingAddress');

            // Bảo mật: Kiểm tra xem địa chỉ này có đúng là của user đang đăng nhập không
            $address = $model->find($id);
            if (!$address || $address['user_id'] != $userId) {
                die("Lỗi: Bạn không có quyền sửa địa chỉ này.");
            }

            // Lấy dữ liệu từ form
            $fullName = $_POST['full_name'];
            $phone = $_POST['phone'];
            $addressLine = $_POST['address_line'];
            $city = $_POST['city'];
            $province = $_POST['province'];
            $postalCode = $_POST['postal_code'];
            $country = $_POST['country'];
            
            $isDefault = isset($_POST['is_default']) ? 1 : 0;

            // Logic mặc định: Nếu sửa thành mặc định, reset các cái khác
            if ($isDefault == 1) {
                $model->resetDefault($userId);
            } elseif ($address['is_default'] == 1) {
                // Nếu đang là mặc định mà bỏ chọn -> Không cho phép (phải có ít nhất 1 cái mặc định)
                // Hoặc bạn có thể force logic khác tùy yêu cầu
                $isDefault = 1; 
            }

            $data = [
                'full_name' => $fullName,
                'phone' => $phone,
                'address_line' => $addressLine,
                'city' => $city,
                'province' => $province,
                'postal_code' => $postalCode,
                'country' => $country,
                'is_default' => $isDefault
                // Không update created_at, user_id
            ];

            // Gọi hàm update có sẵn trong Core/Model
            $model->update($id, $data);

            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }
    
    // --- 3. XÓA ĐỊA CHỈ (DELETE) ---
    public function delete($id) {
        if (!isset($_SESSION['user_logged_in'])) exit;
        
        $userId = $_SESSION['user_id'];
        $model = $this->model('ShippingAddress');
        
        $address = $model->find($id);
        if ($address && $address['user_id'] == $userId) {
            // Không cho xóa địa chỉ mặc định
            if ($address['is_default'] == 1) {
                echo "<script>alert('Không thể xóa địa chỉ mặc định!'); window.history.back();</script>";
                return;
            }
            $model->delete($id);
        }
        
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}