<?php

namespace App\Livewire;

use Livewire\Component;

class ProdukForm extends Component
{
    public $nama;
    public $harga;

    public function simpan()
    {
        dd($this->nama, $this->harga);
    }
    public function render()
    {
        return view('livewire.produk-form');
    }
}
