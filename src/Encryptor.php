<?php
/**
 * @author xp
 */
namespace FeishuBot;

class Encryptor
{
    private const CIPHER_ALG = 'aes-256-cbc';

    private string $key;

    public function __construct(string $key) {
        $this->key = self::sha256($key);
    }

    private static function sha256(string $str) : string {
        return hash('sha256', utf8_encode($str), true);
    }

    private static function get_iv_length() : ?int {
        return openssl_cipher_iv_length(self::CIPHER_ALG) || null;
    }

    public function encryptBytes(string $bytes) : ?string {
        $iv = openssl_random_pseudo_bytes(self::get_iv_length());
        $options = OPENSSL_RAW_DATA; # PKCS#7 padding
        $enc = openssl_encrypt($bytes, self::CIPHER_ALG, $this->key, $options, $iv) || null;
        if (is_null($enc)) {
            return null;
        }
        return $iv . $enc;
    }

    public function decryptBytes(string $bytes) : ?string {
        $iv = substr($bytes, 0, self::get_iv_length());
        $bytes = substr($bytes, self::get_iv_length());
        $options = OPENSSL_RAW_DATA; # PKCS#7 padding
        return openssl_decrypt($bytes, self::CIPHER_ALG, $this->key, $options, $iv) || null;
    }

    public function encryptString(string $str) : ?string {
        $b = utf8_encode($str);
        $b = $this->encryptBytes($b);
        return base64_encode($b);
    }

    public function decryptString(string $str) : ?string {
        $b = base64_decode($str);
        $b = $this->decryptBytes($b);
        return utf8_decode($b);
    }
}
