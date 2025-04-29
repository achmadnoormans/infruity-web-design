<?php

namespace App\Livewire;

use Livewire\Component;
use Yajra\DataTables\DataTables;
use Modules\Master\Entities\Product;

class ProductTable extends Component
{
    public function render()
    {
        return view('livewire.product-table');
    }

    public function getProducts()
    {
        return DataTables::of(Product::query())
            ->addColumn('action', function ($product) {
                return view('components.actions', ['id' => $product->id]);
            })
            ->make(true);
    }
}
