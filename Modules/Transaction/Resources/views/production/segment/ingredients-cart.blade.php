<!-- Ingredients Cart -->
<div class="col-md-12" style="height: 200px; overflow-y: auto;">
    <div>
        <template x-if="ingredients.length === 0 && !isLoadingIngredients">
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                <p class="text-muted">Belum ada bahan baku yang dipilih.</p>
                <small class="text-gray-500">Pilih produk untuk memuat resep atau klik tombol + untuk menambah bahan baku</small>
            </div>
        </template>

        <!-- Loading State -->
        <template x-if="isLoadingIngredients">
            <div class="text-center py-4">
                <div class="d-flex align-items-center justify-content-center">
                    <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <span class="text-muted">Memuat bahan dari resep...</span>
                </div>
            </div>
        </template>

        <div id="ingredients-container">
            <template x-for="(ingredient, index) in ingredients" :key="ingredient.id">
                <div class="card mb-3 p-4 ingredient-item cursor-pointer hover-bg-light-warning" 
                     @click="openEditIngredientModal(ingredient, index)">
                    <!-- Mobile Layout (Stack Vertically) -->
                    <div class="d-block d-lg-none">
                        <!-- Ingredient Name & Details -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="mb-2">
                                <h6 class="mb-1 fw-bold" x-text="ingredient.name"></h6>
                                <small class="text-muted d-flex">
                                    <span x-text="formatCurrency(ingredient.hpp)"></span> &nbsp; x &nbsp;
                                    <span x-text="ingredient.quantity"></span>(<span x-text="ingredient.unit"></span>)
                                </small>
                            </div>
                            <div class="mb-2">
                                <h6 class="mb-1 fw-bold text-transparent">a</h6>
                                <span class="text-muted">
                                    <span x-text="formatCurrency(ingredient.total)"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Desktop Layout (Horizontal) -->
                    <div class="d-none d-lg-block">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="mb-2">
                                <h6 class="mb-1 fw-bold" x-text="ingredient.name"></h6>
                                <small class="text-muted d-flex">
                                    <span x-text="formatCurrency(ingredient.hpp)"></span> &nbsp; x &nbsp;
                                    <span x-text="ingredient.quantity"></span>(<span x-text="ingredient.unit"></span>)
                                </small>
                            </div>
                            <div class="mb-2">
                                <h6 class="mb-1 fw-bold text-transparent">a</h6>
                                <span class="text-muted">
                                    <span x-text="formatCurrency(ingredient.total)"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Edit Indicator -->
                    <div class="position-absolute top-0 end-0 m-2">
                        <div class="badge badge-light-warning" title="Klik untuk edit jumlah">
                            <i class="ki-duotone ki-pencil fs-7 text-warning">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
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