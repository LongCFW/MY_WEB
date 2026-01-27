<?php
namespace App\Controllers\Client;

use App\Core\Controller;

class ShippingAddressController extends Controller {

    // Xử lý thêm mới
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // 1. Kiểm tra xem request này có phải là AJAX không?
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

            // Check Login
            if (!isset($_SESSION['user_logged_in'])) {
                $msg = 'Vui lòng đăng nhập để thực hiện chức năng này.';
                if ($isAjax) { echo json_encode(['status' => 'error', 'message' => $msg]); exit; }
                header('Location: /MY_WEB/public/auth/login'); exit;
            }

            $userId = $_SESSION['user_id'];
            
            // 2. Lấy thông tin User
            $userModel = $this->model('User');
            $currentUser = $userModel->find($userId);

            if (!$currentUser) {
                $msg = 'Không tìm thấy thông tin tài khoản.';
                if ($isAjax) { echo json_encode(['status' => 'error', 'message' => $msg]); exit; }
                header('Location: /MY_WEB/public/account?page=address'); exit;
            }

            // 3. Lấy dữ liệu từ Form (Hỗ trợ cả 'address' và 'address_line')
            $city = $_POST['city'] ?? '';
            $addressDetail = $_POST['address_line'] ?? $_POST['address'] ?? ''; 
            
            // Validate
            if (empty($city) || empty($addressDetail)) {
                $msg = 'Vui lòng nhập Tỉnh/Thành phố và Địa chỉ chi tiết!';
                if ($isAjax) { 
                    echo json_encode(['status' => 'error', 'message' => $msg]); 
                    exit; 
                }
                echo "<script>alert('$msg'); window.history.back();</script>";
                exit;
            }

            // 4. Xử lý Logic Mặc định
            $model = $this->model('ShippingAddress');
            $isDefault = isset($_POST['is_default']) ? 1 : 0;

            // Nếu chưa có địa chỉ nào -> Auto default
            if (!$model->hasAddress($userId)) {
                $isDefault = 1;
            }

            if ($isDefault == 1) {
                $model->resetDefault($userId);
            }

            // 5. Chuẩn bị dữ liệu
            $fullName = $currentUser['fullname'] ?? $currentUser['name'] ?? 'Unknown';
            $phone = $currentUser['phone'] ?? '';

            $data = [
                'user_id' => $userId,
                'full_name' => $fullName,
                'phone' => $phone,
                'address_line' => $addressDetail, // Đảm bảo khớp tên cột trong DB
                'city' => $city,
                'province' => '', 
                'postal_code' => '',
                'country' => 'Vietnam',
                'is_default' => $isDefault,
                'created_at' => date('Y-m-d H:i:s')
            ];

            // 6. Lưu vào DB
            if (method_exists($model, 'create')) {
                $model->create($data);
            } else {
                $sql = "INSERT INTO shipping_addresses (user_id, full_name, phone, address_line, city, is_default, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $model->db->query($sql, [$userId, $fullName, $phone, $addressDetail, $city, $isDefault, date('Y-m-d H:i:s')]);
            }

            // 7. PHẢN HỒI (QUAN TRỌNG ĐỂ FIX LỖI)
            if ($isAjax) {
                // Nếu là Checkout gọi -> Trả về JSON success
                header('Content-Type: application/json');
                echo json_encode(['status' => 'success', 'message' => 'Thêm địa chỉ thành công!']);
                exit;
            }

            // Nếu là trang Account gọi -> Redirect lại trang cũ
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/MY_WEB/public/account?page=address'));
            exit;
        }
    }
    public function setDefault($id) {
        if (!isset($_SESSION['user_logged_in'])) exit;
        
        $userId = $_SESSION['user_id'];
        $model = $this->model('ShippingAddress');
        
        // Kiểm tra quyền sở hữu và update
        $addr = $model->find($id);
        if ($addr && $addr['user_id'] == $userId) {
            $model->setAsDefault($id, $userId);
        }

        // Kiểm tra nếu là Ajax thì trả JSON
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            echo json_encode(['status' => 'success']);
            exit;
        }

        // Nếu request thường -> Redirect về address
        header('Location: /MY_WEB/public/account?page=address');
    }

    public function delete($id) {
        if (!isset($_SESSION['user_logged_in'])) exit;
        
        $userId = $_SESSION['user_id'];
        $model = $this->model('ShippingAddress');
        
        $address = $model->find($id);
        if ($address && $address['user_id'] == $userId) {
            if ($address['is_default'] == 1) {
                 echo "<script>alert('Không thể xóa địa chỉ mặc định!'); window.location.href='/MY_WEB/public/account?page=address';</script>";
                 exit;
            }
            $model->delete($id);
        }
        
        header('Location: /MY_WEB/public/account?page=address');
    }
}