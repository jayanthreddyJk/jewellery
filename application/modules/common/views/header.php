<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?php echo base_url('assets/images/logo/logo.png'); ?>" type="image/png">
    <title><?= $site_title ?? 'Jewellery - Products'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.0/dist/sweetalert2.min.css">

    <style>
        .content-wrapper {
            padding: 20px;
        }

        .btn+.btn {
            margin-left: 10px;
        }

        .dataTables_wrapper .dt-buttons {
            margin-bottom: 10px;
        }

        @media (max-width: 768px) {
            .float-right {
                float: none !important;
                display: block;
                text-align: right;
                margin-top: 10px;
            }
        }


        .select2-container .select2-selection--single {
            height: 38px;
            padding: 6px 12px;
            border-radius: .25rem;
        }

        .select2-container .select2-selection__arrow {
            top: 5px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="<?= base_url('assets/images/logo/logo.png'); ?>" alt="Logo" width="30" height="30" class="mr-2">
                <span class="font-weight-bold">Jewellery Product Management</span>
            </a>

            <div class="ml-auto d-flex align-items-center">
                <?php $username = $this->session->userdata('user_name'); ?>
                <?php if (!empty($username)): ?>
                    <span class="mr-3 font-weight-bold text-dark">
                        <i class="fa fa-user-circle"></i> <?= htmlspecialchars($username); ?>
                    </span>
                    <a href="<?= base_url('auth/auth/logout'); ?>" class="btn btn-outline-danger btn-sm">
                        <i class="fa fa-sign-out"></i> Logout
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('success'); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('error'); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>