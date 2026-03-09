<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Có đơn hàng mới</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1); border-top: 5px solid #FF5722;">
        
        <div style="padding: 20px 30px; text-align: center; border-bottom: 1px solid #eee;">
            <h2 style="margin: 0; font-size: 22px; color: #FF5722;">🔔 THÔNG BÁO CÓ ĐƠN HÀNG MỚI!</h2>
            <p style="margin: 10px 0 0; color: #555;">Mã đơn hàng: <strong style="font-size: 18px;">#<?= $order['order_number'] ?></strong></p>
            <p style="margin: 5px 0 0; color: #888; font-size: 14px;">Thời gian đặt: <?= date('d/m/Y H:i:s') ?></p>
        </div>

        <div style="padding: 30px;">
            <div style="background-color: #FFF3E0; padding: 15px; border-radius: 6px; margin-bottom: 20px; border-left: 4px solid #FF9800;">
                <h4 style="margin: 0 0 10px; color: #E65100;">Thông tin Khách hàng</h4>
                <p style="margin: 3px 0; color: #555;"><strong>Họ tên:</strong> <?= $order['ship_name'] ?? $order['name'] ?? 'Không rõ' ?></p>
                <p style="margin: 3px 0; color: #555;"><strong>SĐT:</strong> <a href="tel:<?= $order['ship_phone'] ?? '' ?>"><?= $order['ship_phone'] ?? $order['phone'] ?? 'Không cung cấp' ?></a></p>
                <p style="margin: 3px 0; color: #555;"><strong>Email khách:</strong> <a href="mailto:<?= $toEmail ?>"><?= $toEmail ?></a></p>
                
                <?php
                    $addressArr = array_filter([$order['address_line'] ?? null, $order['city'] ?? null, $order['province'] ?? null]);
                    $fullAddress = !empty($addressArr) ? implode(', ', $addressArr) : 'Chưa cập nhật chi tiết';
                ?>
                <p style="margin: 3px 0; color: #555;"><strong>Địa chỉ giao:</strong> <?= $fullAddress ?></p>
                <p style="margin: 10px 0 0; color: #D32F2F;"><strong>Giá trị đơn: <?= number_format($order['total_cents']) ?> đ</strong></p>
            </div>

            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                <thead>
                    <tr style="background-color: #f9f9f9;">
                        <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">Sản phẩm</th>
                        <th style="padding: 10px; text-align: right; border-bottom: 2px solid #ddd;">Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #eee; color: #555;">
                            <?php 
                                $snapshot = json_decode($item['product_snapshot'] ?? '{}', true); 
                                $itemName = $snapshot['name'] ?? $item['display_name'] ?? $item['name'] ?? 'Sản phẩm';
                            ?>
                            <strong><?= $itemName ?></strong><br>
                            <small>Số lượng: <?= $item['quantity'] ?></small>
                        </td>
                        <td style="padding: 10px; border-bottom: 1px solid #eee; text-align: right; color: #333; font-weight: bold;">
                            <?= number_format($item['total_price_cents'] ?? ($item['price_cents'] * $item['quantity'])) ?> đ
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div style="text-align: center; margin-top: 30px;">
                <a href="<?= $_ENV['BASE_URL'] ?? 'http://localhost/MY_WEB/public/' ?>admin/orders" style="display: inline-block; background-color: #FF5722; color: white; text-decoration: none; padding: 12px 25px; border-radius: 4px; font-weight: bold;">
                    Vào trang Quản lý Admin ngay
                </a>
            </div>
        </div>
    </div>
</body>
</html>