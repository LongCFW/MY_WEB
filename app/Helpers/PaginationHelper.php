<?php
namespace App\Helpers;

class PaginationHelper {
    
    /**
     * Render thanh phân trang Bootstrap 5
     * @param int $currentPage Trang hiện tại
     * @param int $totalPages Tổng số trang
     * @param string $paramName Tên tham số trên URL (mặc định là 'p' hoặc 'page')
     * @return string HTML
     */
    public static function render($currentPage, $totalPages, $paramName = 'p') {
        if ($totalPages <= 1) return '';

        // Lấy URL hiện tại và bảo toàn các tham số $_GET cũ (như page=wishlist)
        $queryParams = $_GET;
        
        $html = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center mt-4">';

        // Nút Previous
        $prevDisabled = ($currentPage <= 1) ? 'disabled' : '';
        $queryParams[$paramName] = $currentPage - 1;
        $prevUrl = '?' . http_build_query($queryParams);
        $html .= '<li class="page-item ' . $prevDisabled . '">
                    <a class="page-link rounded-pill px-3 me-1" href="' . $prevUrl . '" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                  </li>';

        // Các nút số trang (Hiển thị thông minh: 1, 2, ... 5, 6)
        // Để đơn giản, ta hiển thị tất cả, hoặc giới hạn logic hiển thị ở đây
        for ($i = 1; $i <= $totalPages; $i++) {
            $active = ($i == $currentPage) ? 'active' : '';
            $queryParams[$paramName] = $i;
            $url = '?' . http_build_query($queryParams);
            
            // Style riêng cho nút active màu xanh
            $bgStyle = ($i == $currentPage) ? 'background-color: var(--bs-success); border-color: var(--bs-success);' : '';
            
            $html .= '<li class="page-item ' . $active . '">
                        <a class="page-link rounded-circle d-flex align-items-center justify-content-center mx-1" 
                           style="width: 35px; height: 35px; ' . $bgStyle . '" 
                           href="' . $url . '">' . $i . '</a>
                      </li>';
        }

        // Nút Next
        $nextDisabled = ($currentPage >= $totalPages) ? 'disabled' : '';
        $queryParams[$paramName] = $currentPage + 1;
        $nextUrl = '?' . http_build_query($queryParams);
        $html .= '<li class="page-item ' . $nextDisabled . '">
                    <a class="page-link rounded-pill px-3 ms-1" href="' . $nextUrl . '" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                  </li>';

        $html .= '</ul></nav>';

        return $html;
    }
}