<?php
namespace App\Controllers\Client;

use App\Core\Controller;

class OffersController extends Controller {
    public function index() {
        // Data giả lập voucher (giống React)
        $vouchers = [
            ['id' => 1, 'type' => 'new', 'code' => "ECOSTART", 'desc' => "Giảm 10% đơn đầu tiên", 'min' => 100000, 'expiry' => "30/12/2025"],
            ['id' => 2, 'type' => 'shipping', 'code' => "FREESHIP", 'desc' => "Miễn phí vận chuyển", 'min' => 300000, 'expiry' => "31/01/2025"],
            ['id' => 3, 'type' => 'vip', 'code' => "GREENLIFE", 'desc' => "Giảm 50k đơn từ 500k", 'min' => 500000, 'expiry' => "15/02/2025"],
            ['id' => 4, 'type' => 'shipping', 'code' => "SHIP50", 'desc' => "Giảm 50% phí ship", 'min' => 150000, 'expiry' => "30/06/2025"],
        ];

        $this->view('client/offers/index', ['vouchers' => $vouchers]);
    }
}