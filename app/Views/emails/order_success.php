<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Xác nhận đơn hàng</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
        
        <div style="background-color: #4CAF50; padding: 30px; text-align: center; color: white;">
            <h2 style="margin: 0; font-size: 24px;">
                <?= isset($isBankConfirmed) && $isBankConfirmed ? "Thanh toán thành công!" : "Đặt hàng thành công!" ?>
            </h2>
            <p style="margin: 10px 0 0; opacity: 0.9;">Cảm ơn bạn đã mua sắm tại EcoStore</p>
            <p style="margin: 5px 0 0; font-weight: bold; font-size: 18px;">Mã đơn: #<?= $order['order_number'] ?></p>
        </div>

        <div style="padding: 30px;">
            <h4 style="color: #333; border-bottom: 2px solid #eee; padding-bottom: 10px;">Chi tiết đơn hàng</h4>
            
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td style="padding: 10px 0; border-bottom: 1px solid #eee; color: #555;">
                            <?php 
                                $snapshot = json_decode($item['product_snapshot'] ?? '{}', true); 
                                $itemName = $snapshot['name'] ?? $item['display_name'] ?? $item['name'] ?? 'Sản phẩm';
                            ?>
                            <strong><?= $itemName ?></strong><br>
                            <small>SL: <?= $item['quantity'] ?></small>
                        </td>
                        <td style="padding: 10px 0; border-bottom: 1px solid #eee; text-align: right; color: #333; font-weight: bold;">
                            <?= number_format($item['total_price_cents'] ?? ($item['price_cents'] * $item['quantity'])) ?> đ
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div style="text-align: right; font-size: 16px;">
                <p style="margin: 5px 0; color: #666;">Tạm tính: <?= number_format($order['subtotal_cents']) ?> đ</p>
                <p style="margin: 5px 0; color: #666;">Phí vận chuyển: <?= number_format($order['shipping_fee_cents'] ?? 30000) ?> đ</p>
                
                <?php 
                    $discount = ($order['subtotal_cents'] + $order['shipping_fee_cents'] + ($order['tax_cents'] ?? 0)) - $order['total_cents'];
                    if ($discount > 0): 
                ?>
                <p style="margin: 5px 0; color: #E53935;">Giảm giá: -<?= number_format($discount) ?> đ</p>
                <?php endif; ?>

                <p style="margin: 10px 0 5px; color: #4CAF50; font-size: 20px; font-weight: bold; border-top: 1px solid #eee; padding-top: 10px;">
                    Tổng cộng: <?= number_format($order['total_cents']) ?> đ
                </p>
            </div>

            <div style="background-color: #f9f9f9; padding: 15px; border-radius: 6px; margin-top: 30px;">
                <h5 style="margin: 0 0 10px; color: #333;">Thông tin giao hàng:</h5>
                <p style="margin: 3px 0; color: #555;"><strong>Người nhận:</strong> <?= $order['ship_name'] ?? $order['name'] ?? 'Khách hàng' ?></p>
                <p style="margin: 3px 0; color: #555;"><strong>SĐT:</strong> <?= $order['ship_phone'] ?? $order['phone'] ?? 'Không cung cấp' ?></p>
                
                <?php
                    // Lắp ghép địa chỉ nếu các trường city, province, address_line tồn tại
                    $addressArr = array_filter([
                        $order['address_line'] ?? null,
                        $order['city'] ?? null,
                        $order['province'] ?? null
                    ]);
                    $fullAddress = !empty($addressArr) ? implode(', ', $addressArr) : 'Địa chỉ mặc định của tài khoản';
                ?>
                <p style="margin: 3px 0; color: #555;"><strong>Địa chỉ:</strong> <?= $fullAddress ?></p>
                
                <?php 
                    $methodName = '';
                    switch ($order['payment_method'] ?? '') {
                        case 'banking': $methodName = 'Chuyển khoản VietQR'; break;
                        case 'cod': $methodName = 'Thanh toán khi nhận hàng (COD)'; break;
                        default: $methodName = strtoupper($order['payment_method'] ?? 'COD');
                    }
                    $statusName = ($order['payment_status'] == 'paid') ? '<span style="color: #4CAF50;">(Đã thanh toán)</span>' : '<span style="color: #FF9800;">(Chưa thanh toán)</span>';
                ?>
                <p style="margin: 10px 0 3px; color: #555; border-top: 1px dashed #ccc; padding-top: 10px;">
                    <strong>Hình thức thanh toán:</strong> <?= $methodName ?> <?= $statusName ?>
                </p>
            </div>
            
            <p style="text-align: center; margin-top: 30px; font-size: 14px; color: #888;">
                Nếu có thắc mắc, vui lòng liên hệ hotline: 1900 xxxx hoặc trả lời lại email này.
            </p>
        </div>
    </div>
</body>
</html>