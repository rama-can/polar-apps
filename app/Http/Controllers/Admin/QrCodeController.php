<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\QrCodeService;
use App\Services\ProductService;
use App\Http\Controllers\Controller;

class QrCodeController extends Controller
{
    protected $productService;
    protected $qrCodeService;

    public function __construct(ProductService $productService, QrCodeService $qrCodeService)
    {
        $this->middleware('permission:read qrcodes')->only(['generate', 'download']);
        $this->productService = $productService;
        $this->qrCodeService = $qrCodeService;
    }

    /**
     * get qrcode product
     */
    public function generate(string $id)
    {
        $product = $this->productService->getById($id);
        $qrCode = $this->qrCodeService->generate($product);

        return view('admin.product.qrcode', compact('product', 'qrCode'));
    }

    /**
     * download qrcode product
     */
    public function download(string $id)
    {
        $product = $this->productService->getById($id);
        $qrCode = $this->qrCodeService->generate($product);

        return response($qrCode)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="qrcode-' . $product->slug . '.png"');
    }

    public function scan(string $hashId)
    {
        $product = $this->qrCodeService->getByHashId($hashId);
        return redirect()->route('products.detail', ['category' => $product->category->slug, 'product' => $product->slug]);
    }
}
