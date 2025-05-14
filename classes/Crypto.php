<?php
class Crypto {
    public static function encryptAES($data, $key) {
        $iv=openssl_random_pseudo_bytes(16);
        $encrypted=openssl_encrypt($data, 'AES-256-CBC', hash('sha256', $key, true), 0, $iv);
        return base64_encode($iv . $encrypted);
    }
}
?>
