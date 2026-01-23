<?php
namespace App\Controllers\Client;

use App\Core\Controller;

class ShippingAddressController extends Controller {

    public function store() {
        if (!isset($_SESSION['user_logged_in'])) {
            echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id'];
            
            // --- LOGIC MỚI: Lấy Tên + SĐT từ Profile User ---
            $userModel = $this->model('User');
            $currentUser = $userModel->find($userId);
            
            if (!$currentUser) {
                echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy thông tin tài khoản']);
                exit;
            }

            // Lấy dữ liệu địa chỉ từ Form
            $addressLine = $_POST['address_line'] ?? '';
            $city = $_POST['city'] ?? '';
            $province = $_POST['province'] ?? '';
            $postalCode = $_POST['postal_code'] ?? '';
            
            // Validate cơ bản
            if (empty($addressLine) || empty($city)) {
                echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhập địa chỉ và thành phố']);
                exit;
            }

            // Logic mặc định (Nếu chưa có địa chỉ nào -> Auto default)
            $model = $this->model('ShippingAddress');
            $isDefault = isset($_POST['is_default']) ? 1 : 0;
            
            if (!$model->hasAddress($userId)) {
                $isDefault = 1;
            }

            if ($isDefault == 1) {
                $model->resetDefault($userId);
            }

            $data = [
                'user_id' => $userId,
                // --- AUTO FILL TỪ USER PROFILE ---
                'full_name' => $currentUser['name'], 
                'phone' => $currentUser['phone'],
                // ---------------------------------
                'address_line' => $addressLine,
                'city' => $city,
                'province' => $province,
                'postal_code' => $postalCode,
                'country' => 'Vietnam',
                'is_default' => $isDefault,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $model->create($data);

            echo json_encode(['status' => 'success', 'message' => 'Thêm địa chỉ thành công!']);
            exit;
        }
    }

    public function setDefault($id) {
        // Kiểm tra AJAX request hay Request thường
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

        if (!isset($_SESSION['user_logged_in'])) {
            if ($isAjax) { echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập']); exit; }
            header('Location: /MY_WEB/public/auth/login'); exit;
        }
        
        $userId = $_SESSION['user_id'];
        $model = $this->model('ShippingAddress');
        
        // Check quyền sở hữu
        $addr = $model->find($id);
        if ($addr && $addr['user_id'] == $userId) {
            $model->setAsDefault($id, $userId);
            
            if ($isAjax) {
                echo json_encode(['status' => 'success', 'message' => 'Đã thay đổi địa chỉ mặc định thành công!']);
                exit;
            }
        } else {
            if ($isAjax) {
                echo json_encode(['status' => 'error', 'message' => 'Địa chỉ không tồn tại']);
                exit;
            }
        }
        
        // Fallback cho request thường
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    public function delete($id) {
        if (!isset($_SESSION['user_logged_in'])) exit;
        $userId = $_SESSION['user_id'];
        $model = $this->model('ShippingAddress');
        
        $address = $model->find($id);
        if ($address && $address['user_id'] == $userId) {
            if ($address['is_default'] == 1) {
                echo "<script>alert('Không thể xóa địa chỉ mặc định!'); window.history.back();</script>";
                return;
            }
            $model->delete($id);
        }
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}