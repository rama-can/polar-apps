<?php

namespace App\Services;

use App\Models\Product;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    public function generate(Product $product)
    {
        try {
            $data = app(HashIdService::class)->encode($product->id);
            $url = route('qrcode.scan', $data);
            $logo = public_path('assets/images/qrcode.webp');
            $data = QrCode::size(512)
                ->format('png')
                ->margin(1)
                ->merge($logo, 0.3, true)
                ->errorCorrection('Q')
                ->generate($url);

            return $data;

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getByHashId($hashId)
    {
        $id = app(HashIdService::class)->decode($hashId);
        return Product::findOrFail($id);
    }
}
