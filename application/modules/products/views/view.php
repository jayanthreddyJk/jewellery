<?php $this->load->view('common/header'); ?>

<?php
$product_name = isset($viewData->product_name) && !empty($viewData->product_name) ? $viewData->product_name : '';
$price = isset($viewData->price) && !empty($viewData->price) ? $viewData->price : '';
$category_id = isset($viewData->category_id) && !empty($viewData->category_id) ? $viewData->category_id : '';
$category_name = isset($viewData->category_name) && !empty($viewData->category_name) ? $viewData->category_name : '';
$description = isset($viewData->description) && !empty($viewData->description) ? $viewData->description : '';
$image = isset($viewData->image) && !empty($viewData->image) ? $viewData->image : '';
?>

<div class="container-fluid wrapper">
    <div class="content-wrapper">

        <div class="row p-4">
            <div class="col-12">


                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0"><?= $page_title ?? 'Product View'; ?></h3>
                        <a href="<?= base_url('products/products') ?>" class="btn btn-secondary">Back</a>

                    </div>

                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <strong>Product Name:</strong>
                                <p><?= htmlspecialchars($product_name) ?></p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <strong>Price:</strong>
                                <p>₹ <?= htmlspecialchars($price) ?></p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <strong>Category:</strong>
                                <p><?= htmlspecialchars($category_name) ?></p>
                            </div>
                            <div class="col-md-12 mb-3">
                                <strong>Description:</strong>
                                <p><?= nl2br(htmlspecialchars($description)) ?></p>
                            </div>
                            <div class="col-md-12 mb-3">
                                <strong>Product Image:</strong><br>
                                <?php if (!empty($image) && file_exists($image)) : ?>
                                    <img src="<?= base_url($image) ?>" alt="Product Image" class="img-thumbnail mt-2" style="max-width: 300px;">
                                <?php else: ?>
                                    <p class="text-muted">No image available.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>
</div>
<?php $this->load->view('common/footer'); ?>

</body>

</html>