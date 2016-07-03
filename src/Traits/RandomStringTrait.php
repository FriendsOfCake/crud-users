<?php

namespace CrudUsers\Traits;

use RuntimeException;

trait RandomStringTrait
{
    public function randomizedString($length = 42)
    {
        if (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes($length * 2);
            if (false === $bytes) {
                throw new RuntimeException('Unable to generate random string.');
            }
            return substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $length);
        }

        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }
}
