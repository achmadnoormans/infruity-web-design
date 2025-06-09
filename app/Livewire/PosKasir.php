<?php

namespace App\Livewire;

use Livewire\Component;
use Modules\Master\Entities\Product;

class PosKasir extends Component
{
    public $products = [];
    public $cart = [];

    public function mount()
    {
        $this->products = Product::all(); // bisa pakai pagination juga
    }

    public function addToCart($productId)
    {
        $product = Product::find($productId);

        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['qty'] += 1;
        } else {
            $this->cart[$productId] = [
                'name' => $product->name,
                'price' => $product->price,
                'qty' => 1,
                'discount' => 0,
            ];
        }
    }

    public function incrementQty($productId)
    {
        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['qty'] += 1;
        }
    }

    public function decrementQty($productId)
    {
        if (isset($this->cart[$productId]) && $this->cart[$productId]['qty'] > 1) {
            $this->cart[$productId]['qty'] -= 1;
        }
    }

    public function setDiscount($productId, $discount)
    {
        $this->cart[$productId]['discount'] = $discount;
    }

    public function render()
    {
        return view('livewire.pos-kasir');
    }
}
