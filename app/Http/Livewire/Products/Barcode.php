<?php

declare(strict_types=1);

namespace App\Http\Livewire\Products;

use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Milon\Barcode\Facades\DNS1DFacade;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Barcode extends Component
{
    use LivewireAlert;

    public $product;

    public $products;

    public $quantity;

    public $barcodes;

    protected $listeners = ['productSelected', 'getPdf'];

    public function mount(): void
    {
        $this->products = [];
        $this->product = '';
        $this->quantity = 0;
        $this->barcodes = [];
    }

       public function render(): View|Factory
       {
           return view('livewire.products.barcode');
       }

    public function productSelected($product): void
    {
        $this->products = Product::find($product);
        $this->quantity = 1;
        $this->barcodes = [];
        $this->generateBarcodes($product, $this->quantity);
    }

    public function generateBarcodes($product, $quantity): void
    {
        if ($quantity > 100) {
            $this->alert('error', __('Max quantity is 100 per barcode generation!'));
        }

        $this->barcodes = [];
        // dd($product->);
        for ($i = 0; $i < $this->quantity; $i++) {
            $barcode = DNS1DFacade::getBarCodeSVG($product['code'], $product['barcode_symbology'], 2, 60, 'black', false);
            array_push($this->barcodes, $barcode);
        }
    }

    public function getPdf(): StreamedResponse
    {
        // dd($this->barcodes);

        $pdf = PDF::loadView('admin.barcode.print', [
            'barcodes' => $this->barcodes,
            'products' => $this->products,
            // 'price' => $this->product->price,
            // 'name' => $this->product->name,
        ])->output();

        return response()->streamDownload(
            fn () => print($pdf),
            'barcodes-'.date('Y-m-d').'.pdf'
        );
        // return $pdf->streamDownload('barcodes-'. $this->product->code .'.pdf');
    }

    public function updatedQuantity(): void
    {
        $this->barcodes = [];
    }
}
