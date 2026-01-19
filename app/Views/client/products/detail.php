<?php require_once '../app/Views/client/layouts/header.php'; ?>

<div class="bg-white pb-5">
    <div class="bg-light py-3 mb-4">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0 small bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="/MY_WEB/public/" class="text-success">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="/MY_WEB/public/product" class="text-success">Sản phẩm</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= $product['name'] ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="border rounded-lg overflow-hidden mb-3 position-relative shadow-sm d-flex align-items-center justify-content-center bg-white" style="height: 450px;">
                    <img src="/MY_WEB/public/<?= $product['image_url'] ?>" alt="<?= $product['name'] ?>" class="mw-100 mh-100" style="object-fit: contain;">
                </div>
            </div>

            <div class="col-lg-6">
                <div class="pl-lg-4">
                    <span class="badge badge-success mb-2 bg-opacity-75">Sản phẩm Eco</span>
                    <h2 class="font-weight-bold mb-3 text-dark"><?= $product['name'] ?></h2>
                    
                    <div class="mb-4">
                        <span class="display-4 font-weight-bold mr-3 text-success">
                            <?= number_format($product['price_cents']) ?> đ
                        </span>
                    </div>

                    <div class="mb-4">
                        <p class="text-muted" style="line-height: 1.8;">
                            <?= nl2br($product['description']) ?>
                        </p>
                    </div>

                    <form action="/MY_WEB/public/cart/add" method="POST">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <div class="d-flex flex-wrap gap-3 mb-4 align-items-center">
                            <div class="input-group border rounded-pill overflow-hidden" style="width: 140px;">
                                <input type="number" name="quantity" class="form-control border-0 text-center bg-white font-weight-bold" value="1" min="1">
                            </div>
                            <button type="submit" class="btn btn-success btn-lg rounded-pill px-5 font-weight-bold shadow-sm flex-grow-1">
                                <i class="fas fa-shopping-cart mr-2"></i> Thêm vào giỏ
                            </button>
                        </div>
                    </form>

                    <div class="d-flex gap-4 pt-4 border-top">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-truck text-success fa-2x"></i>
                            <span class="small font-weight-bold ml-2">FreeShip <br> từ 300k</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 ml-4">
                            <i class="fas fa-shield-alt text-success fa-2x"></i>
                            <span class="small font-weight-bold ml-2">Hàng chính hãng <br> 100%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/Views/client/layouts/footer.php'; ?>