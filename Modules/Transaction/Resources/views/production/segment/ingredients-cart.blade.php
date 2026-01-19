<!-- Ingredients Cart -->
<div class="table-responsive">
    <table class="table align-middle table-row-dashed fs-6 gy-3" id="ingredients-table">
        <thead>
            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                <th class="min-w-200px">Bahan Baku</th>
                <th class="min-w-200px text-center">Jumlah</th>
                <th class="min-w-100px text-center">HPP</th>
                <th class="min-w-100px text-center">Total</th>
                <th class="text-end min-w-70px">Aksi</th>
            </tr>
        </thead>
        <tbody id="kt_ecommerce_edit_order_selected_products_body">
            <!-- Loading State -->
            <tr x-show="isLoadingIngredients">
                <td colspan="5" class="text-center py-4">
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span class="text-muted">Memuat bahan dari resep...</span>
                    </div>
                </td>
            </tr>
            
            <!-- Empty State -->
            <tr x-show="ingredients.length === 0 && !isLoadingIngredients">
                <td colspan="5" class="text-center text-muted py-4">
                    <i class="ki-duotone ki-information-5 fs-2x text-gray-400 mb-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                    </i>
                    <div>Belum ada bahan baku yang dipilih</div>
                    <small class="text-gray-500">Pilih produk untuk memuat resep atau klik tombol + untuk menambah bahan baku</small>
                </td>
            </tr>
            
            <template x-for="(ingredient, index) in ingredients" :key="ingredient.id">
                <tr class="border-bottom border-gray-200">
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-50px me-3">
                                <div class="symbol-label bg-light-primary">
                                    <i class="ki-duotone ki-package fs-2x text-primary">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </div>
                            </div>
                            <div>
                                <div class="fw-bold text-gray-800" x-text="ingredient.name"></div>
                                <div class="text-muted fs-7" x-text="ingredient.unit"></div>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="input-group input-group-sm" style="width: 120px;">
                            {{-- <button class="btn btn-outline-secondary btn-sm" type="button" 
                                @click="updateQuantity(index, ingredient.quantity - 1)">
                                <i class="fa fa-minus"></i>
                            </button> --}}
                            <input type="number" class="form-control form-control-sm text-center"
                                x-model="ingredient.quantity" 
                                @change="updateQuantity(index, $event.target.value)"
                                step="0.01" min="0">
                            {{-- <button class="btn btn-outline-secondary btn-sm" type="button" 
                                @click="updateQuantity(index, parseFloat(ingredient.quantity) + 1)">
                                <i class="fa fa-plus"></i>
                            </button> --}}
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="fw-bold text-gray-800" x-text="formatCurrency(ingredient.hpp)"></span>
                    </td>
                    <td class="text-center">
                        <span class="fw-bold text-primary" x-text="formatCurrency(ingredient.total)"></span>
                    </td>
                    <td class="text-end">
                        <button type="button" class="btn btn-sm btn-icon btn-light-danger" 
                            @click="removeIngredient(index)" title="Hapus">
                            <i class="ki-duotone ki-trash fs-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                            </i>
                        </button>
                    </td>
                </tr>
            </template>
        </tbody>
    </table>
</div>

<!-- Hidden inputs for form submission -->
<div id="selected-ingredients-hidden">
    <template x-for="(ingredient, index) in ingredients" :key="ingredient.id">
        <div>
            <input type="hidden" :name="'ingredients[' + index + '][id]'" :value="ingredient.id">
            <input type="hidden" :name="'ingredients[' + index + '][quantity]'" :value="ingredient.quantity">
            <input type="hidden" :name="'ingredients[' + index + '][hpp]'" :value="ingredient.hpp">
        </div>
    </template>
</div>