<?php

namespace App\Services;

use Hashids\Hashids;

class HashIdService
{
    public $hashIds;

    public function __construct()
    {
        $this->hashIds = new Hashids('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890', 10);
    }

    public function encode($id)
    {
        return $this->hashIds->encode($id);
    }

    public function decode($hashId)
    {
        if (is_int($hashId)) {
            return $hashId;
        }

        $decoded = $this->hashIds->decode($hashId);

        return $decoded[0] ?? null;
    }
}
