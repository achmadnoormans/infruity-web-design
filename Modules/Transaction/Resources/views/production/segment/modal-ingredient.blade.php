<!-- Modal Add Ingredient -->
<div class="modal fade" id="modal-add-ingredient" tabindex="-1" aria-labelledby="modalAddIngredientLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddIngredientLabel">
                    <i class="ki-duotone ki-plus fs-2 text-primary me-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Tambah Bahan Baku
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Search Input -->
                <div class="mb-4">
                    <label class="form-label">Cari Bahan Baku:</label>
                    <div class="position-relative">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4 mt-3">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" class="form-control form-control-lg ps-12" 
                            placeholder="Ketik nama bahan baku..." x-model="searchIngredient" 
                            @input="searchIngredients()">
                    </div>
                </div>
                
                <!-- Ingredients List -->
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Bahan Baku</th>
                                <th class="text-center">Stok</th>
                                <th class="text-center">HPP</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="ingredient in availableIngredients" :key="ingredient.id">
                                <tr class="cursor-pointer" @click="selectIngredient(ingredient)">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-40px me-3">
                                                <div class="symbol-label bg-light-success">
                                                    <i class="ki-duotone ki-package fs-2 text-success">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                    </i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-gray-800" x-text="ingredient.name"></div>
                                                <div class="text-muted fs-7" x-text="ingredient.category"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-light-info" x-text="ingredient.stock + ' ' + ingredient.unit"></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-bold text-primary" x-text="formatCurrency(ingredient.hpp)"></span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-light-primary" 
                                            @click.stop="selectIngredient(ingredient)">
                                            <i class="ki-duotone ki-plus fs-5">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                            Pilih
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            
                            <!-- Empty State -->
                            <tr x-show="availableIngredients.length === 0">
                                <td colspan="4" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="ki-duotone ki-information-5 fs-2x text-gray-400 mb-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                        <div>Tidak ada bahan baku ditemukan</div>
                                        <small>Coba ubah kata kunci pencarian</small>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Input Quantity -->
<div class="modal fade" id="modal-input-quantity" tabindex="-1" aria-labelledby="modalInputQuantityLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalInputQuantityLabel">
                    <i class="ki-duotone ki-calculator fs-2 text-primary me-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Input Jumlah Bahan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Selected Ingredient Info -->
                <div class="card card-flush bg-light-primary mb-4" x-show="selectedIngredient">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-40px me-3">
                                <div class="symbol-label bg-primary">
                                    <i class="ki-duotone ki-package fs-2 text-white">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </div>
                            </div>
                            <div>
                                <div class="fw-bold text-gray-800" x-text="selectedIngredient?.name"></div>
                                <div class="text-muted fs-7">
                                    Stok: <span x-text="selectedIngredient?.stock + ' ' + selectedIngredient?.unit"></span> | 
                                    HPP: <span x-text="formatCurrency(selectedIngredient?.hpp)"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quantity Input -->
                <div class="mb-4">
                    <label class="form-label required">Jumlah yang Digunakan:</label>
                    <div class="input-group">
                        <input type="number" class="form-control form-control-lg" 
                            x-model="ingredientQuantity" step="0.01" min="0" 
                            placeholder="0.00" @keyup.enter="addIngredientToCart()">
                        <span class="input-group-text" x-text="selectedIngredient?.unit"></span>
                    </div>
                    <div class="form-text">Masukkan jumlah bahan yang akan digunakan untuk produksi</div>
                </div>
                
                <!-- Total Cost Preview -->
                <div class="card card-flush bg-light-success" x-show="ingredientQuantity > 0">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-gray-600">Total Biaya Bahan:</span>
                            <span class="fw-bold text-success fs-4" 
                                x-text="formatCurrency((selectedIngredient?.hpp || 0) * (ingredientQuantity || 0))"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" @click="addIngredientToCart()" 
                    :disabled="!ingredientQuantity || ingredientQuantity <= 0">
                    <i class="ki-duotone ki-plus fs-5 me-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Tambah ke Produksi
                </button>
            </div>
        </div>
    </div>
</div>