<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?php echo base_url('assets/images/logo/logo.png'); ?>" type="image/png">
    <title>Jewellery - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <style>
        body {
            background-image: url('<?php echo base_url('assets/images/logo/background.jpg'); ?>');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-card {
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #fff0f5;
            border-bottom: none;
        }

        .btn-gold {
            background-color: #d4af37;
            color: white;
        }

        .btn-gold:hover {
            background-color: #bfa137;
        }

        .logo {
            max-width: 80px;
        }

        .toast-error {
            color: black !important;
            background-color: white !important;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px !important;
            background: #f1f1f1;
            padding: 10px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card login-card">
                    <div class="card-header text-center">
                        <img src="<?php echo base_url('assets/images/logo/logo.png'); ?>" alt="Jewellery Logo" class="logo mb-2">
                        <h4 class="fw-bold text-gold">Jewellery Login</h4>
                    </div>
                    <div class="card-body p-4">
                        <form id="loginForm" method="POST" action="<?php echo base_url('auth/auth/validate'); ?>">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Enter email" required autocomplete="off">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Enter password" required autocomplete="new-password">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="togglePassword">
                                    <label class="form-check-label" for="togglePassword">Show Password</label>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-gold">Login</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.js"></script>

    <script>
        <?php if ($this->session->flashdata('error')): ?>
            var errorMessage = "<?php echo $this->session->flashdata('error'); ?>";
            Toastify({
                text: "<strong>Login Failed</strong><br>" + errorMessage,
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: "linear-gradient(to right,rgb(248, 246, 245),rgb(247, 247, 247))",
                stopOnFocus: true,
                close: true,
                style: {
                    borderRadius: "10px"
                },
                className: "toast-error",
                escapeMarkup: false,
                avatar: "https://img.icons8.com/ios/50/000000/error.png",
            }).showToast();
        <?php endif; ?>

        $(document).ready(function() {
            $("#loginForm").validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                    }
                },
                messages: {
                    email: {
                        required: "Please enter your email address",
                        email: "Please enter a valid email"
                    },
                    password: {
                        required: "Please enter your password",
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


            $('#togglePassword').on('change', function() {
                const passwordField = $('#password');
                const type = $(this).is(':checked') ? 'text' : 'password';
                passwordField.attr('type', type);
            });

        });
    </script>
</body>


</html>