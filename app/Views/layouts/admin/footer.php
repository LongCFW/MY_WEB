</div> </div> </div> <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="/MY_WEB/public/assets/js/ui-helper.js"></script>
<script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
    
    // Tự động bắt sự kiện click vào các thẻ a có class 'btn-delete'
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        const url = $(this).attr('href'); // Lấy link xóa

        Alert.confirm(
            'Xóa dữ liệu?', 
            'Bạn có chắc chắn muốn xóa mục này không? Hành động này không thể hoàn tác.', 
            function() {
                // Nếu user bấm Đồng ý -> Chuyển trang đến link xóa
                window.location.href = url;
            }
        );
    });        
</script>
<div class="custom-toast-container"></div>
</body>
</html>