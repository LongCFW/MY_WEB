<div class="modal fade" id="quickViewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 overflow-hidden shadow-lg">
            <button type="button" class="btn-close position-absolute top-0 end-0 m-3 z-3 bg-white p-2 rounded-circle shadow-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="row g-0">
                <div class="col-md-6 bg-light d-flex align-items-center justify-content-center p-4 position-relative overflow-hidden">
                    <img id="qv-image" src="" class="img-fluid position-relative z-2" style="max-height: 350px; object-fit: contain;">
                </div>
                <div class="col-md-6 p-4 p-lg-5 d-flex flex-column justify-content-center bg-white">
                    <span id="qv-category" class="text-uppercase fw-bold text-success small mb-2"></span>
                    <h3 id="qv-name" class="fw-bold text-dark mb-3"></h3>
                    <h2 id="qv-price" class="text-success fw-bold m-0 mb-4"></h2>
                    <p id="qv-desc" class="text-muted mb-4 small"></p>
                    
                    <div class="mt-auto">
                        <input type="hidden" id="qv-id"> 
                        
                        <div class="d-flex gap-3">
                            <div class="input-group border rounded-pill overflow-hidden" style="width: 120px;">
                                <button type="button" class="btn btn-light border-0" onclick="document.getElementById('qv-qty').stepDown()"><i class="fas fa-minus small"></i></button>
                                <input type="number" id="qv-qty" value="1" min="1" class="form-control border-0 text-center fw-bold bg-white">
                                <button type="button" class="btn btn-light border-0" onclick="document.getElementById('qv-qty').stepUp()"><i class="fas fa-plus small"></i></button>
                            </div>

                            <button type="button" 
                                    class="btn btn-success rounded-pill fw-bold px-4 flex-grow-1 shadow-sm"
                                    onclick="addToCartGlobal(document.getElementById('qv-id').value, document.getElementById('qv-qty').value)">
                                Mua ngay
                            </button>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-3 border-top text-center">
                        <a id="qv-link" href="#" class="text-secondary text-decoration-none small fw-bold">Xem chi tiết sản phẩm <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const qvModalEl = document.getElementById('quickViewModal');
        if (typeof bootstrap !== 'undefined' && qvModalEl) {
            const qvModal = new bootstrap.Modal(qvModalEl);
            
            qvModalEl.addEventListener('show.bs.modal', function () {
                document.getElementById('qv-qty').value = 1;
            });

            document.body.addEventListener('click', function(e) {
                const btn = e.target.closest('.btn-quick-view');
                if (btn) {
                    e.preventDefault();
                    document.getElementById('qv-id').value = btn.dataset.id;
                    document.getElementById('qv-name').innerText = btn.dataset.name;
                    document.getElementById('qv-price').innerText = parseInt(btn.dataset.price).toLocaleString('vi-VN') + ' đ';
                    document.getElementById('qv-image').src = btn.dataset.image;
                    document.getElementById('qv-category').innerText = btn.dataset.cat;
                    document.getElementById('qv-desc').innerText = btn.dataset.desc || 'Đang cập nhật...';
                    document.getElementById('qv-link').href = '/MY_WEB/public/product/detail/' + btn.dataset.id;
                    qvModal.show();
                }
            });
        }
    });
</script>