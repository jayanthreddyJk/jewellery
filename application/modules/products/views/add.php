<?php $this->load->view('common/header'); ?>

<?php
$id = isset($editData->id) && !empty($editData->id) ? hideval($editData->id) : '';
$product_name = isset($editData->product_name) && !empty($editData->product_name) ? $editData->product_name : '';
$price = isset($editData->price) && !empty($editData->price) ? $editData->price : '';
$category_id = isset($editData->category_id) && !empty($editData->category_id) ? $editData->category_id : '';
$description = isset($editData->description) && !empty($editData->description) ? $editData->description : '';
$image = isset($editData->image) && !empty($editData->image) ? $editData->image : '';
?>

<div class="container-fluid wrapper">
    <div class="content-wrapper">

        <div class="row p-4">
            <div class="col-12">


                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0"><?= $page_title ?? 'Product Add'; ?></h3>
                        <a href="<?= base_url('products/products') ?>" class="btn btn-secondary">Back</a>
                    </div>

                    <div class="card-body p-4">
                        <form id="addProductForm" method="post" action="<?= base_url('products/products/save/' . urlencode($id)) ?>" enctype="multipart/form-data">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="product_name">Product Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="product_name" name="product_name" required placeholder="Enter Product Name" value="<?php echo $product_name; ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="price">Price <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="price" name="price" min="0" step="0.01" required placeholder="Enter Price (In Rupees)" value="<?php echo $price; ?>">
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Category <span class="text-danger">*</span></label>
                                    <select class="form-control select2" id="category_id" name="category_id" required>
                                        <option value="">-- Select Category --</option>
                                        <?php foreach ($categories as $cat):
                                            $selected = ($category_id == $cat->id) ? 'selected' : ''; ?>
                                            <option value="<?= $cat->id ?>" <?= $selected ?>>
                                                <?= htmlspecialchars($cat->category) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="description">Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="description" name="description" rows="3" required placeholder="Enter Description"><?php echo $description; ?></textarea>
                                </div>



                                <div class="form-group col-md-12">
                                    <label for="image">Product Image <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control-file" id="product_image" name="product_image" accept="image/*">
                                    <input type="hidden" name="existing_product_image" accept="image/*" value="<?php echo $image; ?>">
                                    <?php if (!empty($image)): ?>
                                        <img id="preview" src="<?= base_url($image) ?>" alt="Preview" class="img-thumbnail mt-2" style="max-width:200px;">
                                    <?php else: ?>
                                        <img id="preview" src="#" alt="Preview" class="img-thumbnail mt-2" style="display:none; max-width:200px;">
                                    <?php endif; ?>
                                </div>

                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="<?= base_url('products/products') ?>" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<?php $this->load->view('common/footer'); ?>

<script>
    $(document).ready(function() {

        $('.select2').select2();

        $('#product_image').on('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const fileType = file.type.split('/')[0];
            if (fileType !== 'image') {
                alert('Please upload a valid image file.');
                $('#product_image').val('');
                return;
            }


            const reader = new FileReader();
            reader.onload = function(event) {
                const img = new Image();
                img.onload = function() {
                    const MAX_WIDTH = 500;
                    const MAX_HEIGHT = 500;
                    let width = img.width;
                    let height = img.height;

                    if (width > height) {
                        if (width > MAX_WIDTH) {
                            height *= MAX_WIDTH / width;
                            width = MAX_WIDTH;

                        }
                    } else {
                        if (height > MAX_HEIGHT) {
                            width *= MAX_HEIGHT / height;
                            height = MAX_HEIGHT;


                        }
                    }

                    const canvas = document.createElement('canvas');
                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    $('#preview').attr('src', canvas.toDataURL('image/jpeg')).show();

                    canvas.toBlob(function(blob) {
                        const resizedFile = new File([blob], file.name, {
                            type: file.type
                        });
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(resizedFile);
                        $('#product_image')[0].files = dataTransfer.files;
                    }, file.type, 1);
                };
                img.src = event.target.result;
            };
            reader.readAsDataURL(file);
        });

        $("#addProductForm").validate({
            rules: {
                product_name: {
                    required: true,
                },
                price: {
                    required: true,
                },
                category_id: {
                    required: true,
                },
                description: {
                    required: true,
                },
                product_image: {
                    required: function() {
                        return $('input[name="existing_product_image"]').val() === '';
                    }
                }
            },
            messages: {
                product_name: {
                    required: "Please Enter Product Name",
                },
                price: {
                    required: "Please Enter Price",
                },
                category_id: {
                    required: "Please Select Category",
                },
                description: {
                    required: "Please Enter Description",
                },
                product_image: {
                    required: "Please Upload Product Image",
                }
            },
            errorClass: "text-danger",
            errorElement: "div",
            highlight: function(element) {
                $(element).addClass("is-invalid");
            },
            unhighlight: function(element) {
                $(element).removeClass("is-invalid");
            }
        });

    });
</script>

</body>

</html>