<?php

function isLogin()
{
    $ci = &get_instance();

    if (!$ci->session->userdata('user_id')) {
        redirect('auth');
    }

    return true;
}


function base_hide($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base_unhide($data) {
    return base64_decode(strtr($data, '-_', '+/'));
}

function hideval($id) {
    $key = 'my_secret_key_12345'; 
    $cipher = "AES-128-CTR";
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
    $encrypted = openssl_encrypt($id, $cipher, $key, 0, $iv);
    return base_hide($iv . '::' . $encrypted);
}

function unhideval($val) {
    $key = 'my_secret_key_12345';
    $cipher = "AES-128-CTR";
    $data = base_unhide($val);
    list($iv, $encrypted) = explode('::', $data);
    return openssl_decrypt($encrypted, $cipher, $key, 0, $iv);
}




