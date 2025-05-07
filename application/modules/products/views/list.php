<?php $this->load->view('common/header'); ?>


<div class="container-fluid wrapper">
    <div class="content-wrapper">

        <div class="row p-4">
            <div class="col-12">

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0"><?= $page_title ?? 'Product List'; ?></h3>
                        <a href="<?= BASE_URL('products/products/add') ?>" class="btn btn-sm btn-primary resptarget">
                            Add Product
                        </a>
                    </div>

                    <div class="card-body p-4">
                        <table id="employeeid" class="table table-bordered table-striped dt-responsive nowrap" width="100%">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<?php $this->load->view('common/footer'); ?>

<script>
    $(function() {
        let csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
        let csrfToken = '<?= $this->security->get_csrf_hash(); ?>';

        let table = $('#employeeid').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            searching: true,
            ordering: true,
            stateSave: true,
            ajax: {
                url: "<?= isset($ajaxurl) ? site_url($ajaxurl) : '' ?>",
                type: "POST",
                data: function(d) {
                    d[csrfName] = csrfToken;
                }
            },
            dom: 'Bfrtip',
            buttons: [
                'pageLength',
                'colvis'
            ],
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            columnDefs: [{
                targets: -1,
                orderable: false
            }]
        });

        // Delete Product
        $(document).on('click', '.deleteProduct', function(e) {
            e.preventDefault();
            let url = '<?= BASE_URL("products/products/delete"); ?>';
            let deletId = $(this).attr('delt-id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(url, {
                        id: deletId,
                        [csrfName]: csrfToken
                    }, function(res) {
                        let response = JSON.parse(res);
                        if (response.status === 'success') {
                            Swal.fire(
                                'Deleted!',
                                response.message,
                                'success'
                            );
                            table.ajax.reload(null, false); 
                        } else {
                            Swal.fire(
                                'Error!',
                                response.message,
                                'error'
                            );
                        }
                    });
                } else {
                    Swal.fire(
                        'Cancelled',
                        'Your product is safe',
                        'info'
                    );
                }
            });
        });


    });
</script>

</body>

</html>