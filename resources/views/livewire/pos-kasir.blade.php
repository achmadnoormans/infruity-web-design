<div class="grid grid-cols-2 gap-4">
    <!-- Product List -->
    <div>
        <h2 class="text-xl font-bold mb-2">Produk</h2>
        @foreach($products as $product)
            <div class="flex items-center justify-between border p-2 mb-1">
                <div>
                    <strong>{{ $product->name }}</strong><br>
                    <span>Rp {{ number_format($product->price) }}</span>
                </div>
                <div class="flex gap-1">
                    <button wire:click="decrementQty({{ $product->id }})">-</button>
                    <button wire:click="addToCart({{ $product->id }})">🛒</button>
                    <button wire:click="incrementQty({{ $product->id }})">+</button>
                    <button wire:click="$emit('openModal', {{ $product->id }})">⚙️</button>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Cart -->
    <div>
        <h2 class="text-xl font-bold mb-2">Keranjang</h2>
        @forelse($cart as $id => $item)
            <div class="border p-2 mb-1">
                {{ $item['name'] }} x {{ $item['qty'] }}<br>
                Subtotal: Rp {{ number_format($item['qty'] * ($item['price'] - $item['discount'])) }}
            </div>
        @empty
            <p>Belum ada item</p>
        @endforelse
    </div>
</div>