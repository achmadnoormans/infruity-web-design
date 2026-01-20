<script>
// Alpine.js Production App
function productionApp() {
    return {
        // Data properties
        ingredients: [],
        availableIngredients: [],
        availableRecipes: [],
        selectedIngredient: null,
        selectedRecipe: null,
        selectedRecipeId: '',
        ingredientQuantity: 0,
        recipeMultiplier: 1,
        searchIngredient: '',
        ingredientSearchTerm: '',
        productionQuantity: 1,
        notes: '',
        status: 'temp',
        isLoadingIngredients: false,
        showAddIngredientModal: false,

        // Computed properties
        get totalHpp() {
            return this.ingredients.reduce((total, ingredient) => {
                return total + (ingredient.hpp * ingredient.quantity);
            }, 0);
        },

        get hppPerUnit() {
            return this.productionQuantity > 0 ? this.totalHpp / this.productionQuantity : 0;
        },

        get recipeTotal() {
            if (!this.selectedRecipe?.ingredients) return 0;
            return this.selectedRecipe.ingredients.reduce((total, ingredient) => {
                return total + (ingredient.hpp * ingredient.quantity);
            }, 0);
        },

        // Computed property for filtered ingredients
        get filteredIngredients() {
            if (!this.ingredientSearchTerm || this.ingredientSearchTerm.trim() === '') {
                return this.availableIngredients;
            }
            
            const searchTerm = this.ingredientSearchTerm.toLowerCase();
            const filtered = this.availableIngredients.filter(ingredient => 
                ingredient.name.toLowerCase().includes(searchTerm) ||
                (ingredient.code && ingredient.code.toLowerCase().includes(searchTerm))
            );
            
            return filtered;
        },

        // Initialize
        init() {
            this.loadAvailableIngredients();
            this.loadAvailableRecipes();
            this.loadExistingData();
            
            // Initialize quantity from HTML input
            const quantityInput = document.getElementById('quantity');
            if (quantityInput && quantityInput.value) {
                this.productionQuantity = parseFloat(quantityInput.value) || 1;
            }
            
            // Watch for production quantity changes
            this.$watch('productionQuantity', (value) => {
                document.getElementById('quantity').value = value;
            });
        },

        // Load existing data if editing
        loadExistingData() {
            @if(isset($data) && isset($production_detail))
                // Load existing ingredients data
                @php
                    $ingredientsData = $production_detail->map(function($item) {
                        return [
                            'id' => $item->product_id,
                            'name' => $item->products->name,
                            'quantity' => $item->quantity,
                            'hpp' => $item->products->hpp,
                            'unit' => $item->products->unit->abbreviation ?? 'pcs',
                            'total' => $item->products->hpp * $item->quantity
                        ];
                    });
                @endphp
                this.ingredients = @json($ingredientsData);
                this.productionQuantity = {{ $data->quantity ?? 1 }};
                this.status = '{{ $data->status ?? "temp" }}';
            @endif
        },

        // Load available ingredients
        async loadAvailableIngredients() {
            try {
                const response = await fetch('{{ route("ajax.getProduct") }}');
                const data = await response.json();
                console.log('API Response:', data); // Temporary debug
                this.availableIngredients = data.map(item => ({
                    id: item.id,
                    name: item.name,
                    code: item.code || item.sku || '',
                    stock: item.stock || 0,
                    hpp: item.hpp || 0,
                    unit: item.unit?.abbreviation || 'pcs',
                    category: item.category?.name || 'Umum'
                }));
                console.log('Mapped ingredients:', this.availableIngredients); // Temporary debug
            } catch (error) {
                console.error('Error loading ingredients:', error);
                this.showNotification('Error loading ingredients', 'error');
            }
        },

        // Load available recipes
        async loadAvailableRecipes() {
            try {
                const response = await fetch('{{ route("products.get-receipt") }}');
                const data = await response.json();
                this.availableRecipes = data;
            } catch (error) {
                console.error('Error loading recipes:', error);
            }
        },

        // Search ingredients
        searchIngredients() {
            // This will be handled by the computed filteredIngredients property
        },

        // Format number to Rupiah
        toRupiah(amount) {
            if (!amount) return 'Rp 0';
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
        },

        // Show notification
        showNotification(message, type = 'info') {
            // You can implement this with your preferred notification library
            // For now, just use console.log
            console.log(`${type.toUpperCase()}: ${message}`);
            
            // If you have a notification system like toastr, use it here
            if (typeof toastr !== 'undefined') {
                toastr[type](message);
            }
        },

        // Open modals
        openAddIngredientModal() {
            this.showAddIngredientModal = true;
            this.loadAvailableIngredients();
            this.ingredientSearchTerm = '';
            const modal = new bootstrap.Modal(document.getElementById('modal-add-ingredient'));
            modal.show();
        },

        // Format number to Rupiah
        toRupiah(amount) {
            if (!amount) return 'Rp 0';
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
        },

        // Show notification
        showNotification(message, type = 'info') {
            // You can implement this with your preferred notification library
            // For now, just use console.log
            console.log(`${type.toUpperCase()}: ${message}`);
            
            // If you have a notification system like toastr, use it here
            if (typeof toastr !== 'undefined') {
                toastr[type](message);
            }
        },

        openParcelModal() {
            const modal = new bootstrap.Modal(document.getElementById('modal-load-recipe'));
            modal.show();
        },

        loadFromRecipe() {
            const modal = new bootstrap.Modal(document.getElementById('modal-load-recipe'));
            modal.show();
        },

        // Select ingredient
        selectIngredient(ingredient) {
            this.selectedIngredient = ingredient;
            this.ingredientQuantity = 0;
            
            // Close ingredient modal and open quantity modal
            bootstrap.Modal.getInstance(document.getElementById('modal-add-ingredient')).hide();
            const quantityModal = new bootstrap.Modal(document.getElementById('modal-input-quantity'));
            quantityModal.show();
        },

        // Add ingredient to cart
        addIngredientToCart(ingredient = null) {
            // If ingredient is passed directly (from new modal), use it
            if (ingredient) {
                // Check if ingredient already exists
                const existingIndex = this.ingredients.findIndex(item => item.id === ingredient.id);
                
                if (existingIndex >= 0) {
                    // Update existing ingredient quantity by 1
                    this.ingredients[existingIndex].quantity += 1;
                    this.ingredients[existingIndex].total = this.ingredients[existingIndex].hpp * this.ingredients[existingIndex].quantity;
                } else {
                    // Add new ingredient with quantity 1
                    this.ingredients.push({
                        id: ingredient.id,
                        name: ingredient.name,
                        quantity: 1,
                        hpp: ingredient.hpp,
                        unit: ingredient.unit,
                        total: ingredient.hpp * 1
                    });
                }
                
                this.showNotification('Ingredient added successfully', 'success');
                return;
            }
            
            // Original method for quantity modal
            if (!this.selectedIngredient || !this.ingredientQuantity || this.ingredientQuantity <= 0) {
                this.showNotification('Please enter valid quantity', 'error');
                return;
            }

            // Check if ingredient already exists
            const existingIndex = this.ingredients.findIndex(item => item.id === this.selectedIngredient.id);
            
            if (existingIndex >= 0) {
                // Update existing ingredient
                this.ingredients[existingIndex].quantity = parseFloat(this.ingredientQuantity);
                this.ingredients[existingIndex].total = this.ingredients[existingIndex].hpp * this.ingredients[existingIndex].quantity;
            } else {
                // Add new ingredient
                this.ingredients.push({
                    id: this.selectedIngredient.id,
                    name: this.selectedIngredient.name,
                    quantity: parseFloat(this.ingredientQuantity),
                    hpp: this.selectedIngredient.hpp,
                    unit: this.selectedIngredient.unit,
                    total: this.selectedIngredient.hpp * parseFloat(this.ingredientQuantity)
                });
            }

            // Close modal and reset
            bootstrap.Modal.getInstance(document.getElementById('modal-input-quantity')).hide();
            this.selectedIngredient = null;
            this.ingredientQuantity = 0;
            
            this.showNotification('Ingredient added successfully', 'success');
        },

        // Update ingredient quantity
        updateQuantity(index, newQuantity) {
            if (newQuantity < 0) return;
            
            this.ingredients[index].quantity = parseFloat(newQuantity) || 0;
            this.ingredients[index].total = this.ingredients[index].hpp * this.ingredients[index].quantity;
        },

        // Remove ingredient
        removeIngredient(index) {
            this.ingredients.splice(index, 1);
            this.showNotification('Ingredient removed', 'info');
        },

        // Clear all ingredients
        clearIngredients() {
            this.ingredients = [];
            this.showNotification('All ingredients cleared', 'info');
        },

        // Set loading state
        setLoadingIngredients(loading) {
            this.isLoadingIngredients = loading;
        },

        // Load recipe details
        async loadRecipeDetails() {
            if (!this.selectedRecipeId) {
                this.selectedRecipe = null;
                return;
            }

            try {
                const response = await fetch(`{{ url('production/get-receipt') }}/${this.selectedRecipeId}`);
                const data = await response.json();
                this.selectedRecipe = data;
            } catch (error) {
                console.error('Error loading recipe details:', error);
                this.showNotification('Error loading recipe details', 'error');
            }
        },

        // Load recipe to production
        loadRecipeToProduction() {
            if (!this.selectedRecipe || !this.recipeMultiplier || this.recipeMultiplier <= 0) {
                this.showNotification('Please select recipe and enter valid multiplier', 'error');
                return;
            }

            // Clear existing ingredients
            this.ingredients = [];

            // Add recipe ingredients with multiplier
            this.selectedRecipe.ingredients.forEach(ingredient => {
                this.ingredients.push({
                    id: ingredient.id,
                    name: ingredient.name,
                    quantity: ingredient.quantity * this.recipeMultiplier,
                    hpp: ingredient.hpp,
                    unit: ingredient.unit,
                    total: ingredient.hpp * (ingredient.quantity * this.recipeMultiplier)
                });
            });

            // Close modal and reset
            bootstrap.Modal.getInstance(document.getElementById('modal-load-recipe')).hide();
            this.selectedRecipe = null;
            this.selectedRecipeId = '';
            this.recipeMultiplier = 1;
            
            this.showNotification('Recipe loaded successfully', 'success');
        },

        // Refresh product list
        refreshProduct() {
            this.loadAvailableIngredients();
            this.showNotification('Product list refreshed', 'info');
        },

        // Format currency
        formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount || 0);
        },

        // Get status text
        getStatusText(status) {
            const statusMap = {
                'temp': 'Draft Sementara',
                'draft': 'Siap Produksi',
                'posting': 'Selesai Produksi'
            };
            return statusMap[status] || 'Unknown';
        },

        // Show notification
        showNotification(message, type = 'info') {
            // You can implement this with your preferred notification library
            // For now, just use console.log
            console.log(`${type.toUpperCase()}: ${message}`);
            
            // If you have a notification system like toastr, use it here
            if (typeof toastr !== 'undefined') {
                toastr[type](message);
            }
        },

        // Save production function (similar to POS saveTransaction)
        async saveProduction(submitType, callback) {
            try {
                // Validate required fields
                if (!this.productionQuantity || this.productionQuantity <= 0) {
                    this.showNotification('Jumlah produksi harus lebih dari 0', 'error');
                    if (callback) callback();
                    return;
                }
                
                if (this.ingredients.length === 0) {
                    this.showNotification('Minimal harus ada 1 bahan baku', 'error');
                    if (callback) callback();
                    return;
                }
                
                const productSelect = document.getElementById('product_id');
                if (!productSelect.value) {
                    this.showNotification('Pilih produk terlebih dahulu', 'error');
                    if (callback) callback();
                    return;
                }
                
                // Prepare form data
                const formData = new FormData();
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                formData.append('production_number', document.querySelector('input[name="production_number"]').value);
                formData.append('production_date', document.querySelector('input[name="production_date"]').value);
                formData.append('product_id', productSelect.value);
                formData.append('quantity', this.productionQuantity);
                formData.append('submit_type', submitType);
                formData.append('notes', this.notes || '');
                
                const staffSelect = document.getElementById('staff_id');
                if (staffSelect.value) {
                    formData.append('staff_id', staffSelect.value);
                }
                
                // Add ingredients
                this.ingredients.forEach((ingredient, index) => {
                    formData.append(`ingredients[${index}][id]`, ingredient.id);
                    formData.append(`ingredients[${index}][quantity]`, ingredient.quantity);
                    formData.append(`ingredients[${index}][hpp]`, ingredient.hpp);
                });
                
                // Submit form
                const response = await fetch('{{ route("production.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (response.ok) {
                    // Check if response is a redirect (Laravel redirect response)
                    if (response.redirected) {
                        window.location.href = response.url;
                        return;
                    }
                    
                    // Handle JSON response
                    const result = await response.json();
                    if (result.success) {
                        this.showNotification(result.message || 'Produksi berhasil disimpan', 'success');
                        
                        // Redirect based on submit type
                        setTimeout(() => {
                            if (submitType === 'temp') {
                                // Stay on page for temp saves
                                if (callback) callback();
                            } else {
                                // Redirect to index for draft and posting
                                window.location.href = '{{ route("production.index") }}';
                            }
                        }, 1000);
                    } else {
                        this.showNotification(result.message || 'Gagal menyimpan produksi', 'error');
                        if (callback) callback();
                    }
                } else {
                    // Handle non-JSON responses (like Laravel validation errors)
                    const text = await response.text();
                    
                    // Check if it's a redirect response
                    if (text.includes('Redirecting to') || response.status === 302) {
                        // Extract redirect URL or use fallback
                        window.location.href = '{{ route("production.index") }}';
                        return;
                    }
                    
                    this.showNotification('Terjadi kesalahan saat menyimpan', 'error');
                    if (callback) callback();
                }
                
            } catch (error) {
                console.error('Error saving production:', error);
                this.showNotification('Terjadi kesalahan: ' + error.message, 'error');
                if (callback) callback();
            }
        },
    }
}

// Set submit type function
function setSubmitType(type) {
    document.getElementById('submit_type').value = type;
}

// Document ready
document.addEventListener('DOMContentLoaded', function() {
    // Wait for Alpine.js to be ready
    document.addEventListener('alpine:init', function() {
        console.log('Alpine.js initialized');
    });
    
    // Initialize Select2 for product selection with a small delay to ensure Alpine is ready
    setTimeout(function() {
        $('#product_id').select2({
            placeholder: 'Pilih Produk',
            ajax: {
                url: '{{ route('products.get-receipt') }}',
                dataType: 'json',
                delay: 250,
                data: params => ({
                    search: params.term
                }),
                processResults: data => ({
                    results: data.map(item => ({
                        id: item.product_id,
                        text: item.products?.name || 'Unknown Product'
                    }))
                }),
                cache: true
            },
            minimumInputLength: 0,
            allowClear: true
        }).on('select2:select', function(e) {
            // Auto load ingredients when product is selected
            const selectedProductId = e.params.data.id;
            if (selectedProductId) {
                // Add a small delay to ensure Alpine is ready
                setTimeout(() => {
                    loadRecipeIngredients(selectedProductId);
                }, 100);
            }
        }).on('select2:clear', function(e) {
            // Clear ingredients when product is cleared
            try {
                const alpineElement = document.querySelector('[x-data*="productionApp"]') || document.querySelector('[x-data]');
                let productionApp = null;
                
                if (alpineElement && alpineElement._x_dataStack && alpineElement._x_dataStack[0]) {
                    productionApp = alpineElement._x_dataStack[0];
                } else if (alpineElement && alpineElement.__x && alpineElement.__x.$data) {
                    productionApp = alpineElement.__x.$data;
                }
                
                if (productionApp) {
                    productionApp.ingredients = [];
                    productionApp.productionQuantity = 1;
                    const quantityInput = document.getElementById('quantity');
                    if (quantityInput) {
                        quantityInput.value = 1;
                    }
                } else {
                    // Fallback: clear table directly
                    const tbody = document.getElementById('kt_ecommerce_edit_order_selected_products_body');
                    if (tbody) {
                        tbody.innerHTML = `
                            <tr>
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
                        `;
                    }
                    const quantityInput = document.getElementById('quantity');
                    if (quantityInput) {
                        quantityInput.value = 1;
                    }
                }
            } catch (error) {
                console.error('Error clearing ingredients:', error);
            }
        });
    }, 500); // Wait 500ms for Alpine to initialize

    // Initialize Select2 for staff selection  
    $('#staff_id').select2({
        placeholder: 'Pilih Staff',
        allowClear: true,
        ajax: {
            url: '{{ route("ajax.getStaff") }}',
            dataType: 'json',
            delay: 250,
            data: params => ({
                search: params.term
            }),
            processResults: data => ({
                results: data.map(item => ({
                    id: item.id,
                    text: item.name
                }))
            }),
            cache: true
        },
        minimumInputLength: 0
    });

    // Form submission handler
    $('#kt_ecommerce_edit_order_form').on('submit', function(e) {
        const submitButton = $(this).find('[type="submit"]:focus');
        submitButton.find('.indicator-label').hide();
        submitButton.find('.indicator-progress').show();
        submitButton.prop('disabled', true);
    });
});

// Function to load recipe ingredients
async function loadRecipeIngredients(productId) {
    console.log('Loading recipe ingredients for product ID:', productId);
    
    try {
        // Get Alpine.js component instance using a more reliable method
        let productionApp = null;
        
        // Try multiple ways to get the Alpine component
        const alpineElement = document.querySelector('[x-data*="productionApp"]') || document.querySelector('[x-data]');
        
        if (alpineElement && alpineElement._x_dataStack && alpineElement._x_dataStack[0]) {
            productionApp = alpineElement._x_dataStack[0];
        } else if (alpineElement && alpineElement.__x && alpineElement.__x.$data) {
            productionApp = alpineElement.__x.$data;
        } else if (window.Alpine && window.Alpine.store) {
            // Fallback: try to access through Alpine store if available
            console.log('Using fallback method to access Alpine data');
        }
        
        if (!productionApp) {
            console.error('Alpine.js component not found, using fallback approach');
            // Use a fallback approach without Alpine
            await loadRecipeIngredientsWithoutAlpine(productId);
            return;
        }
        
        // Set loading state
        if (typeof productionApp.setLoadingIngredients === 'function') {
            productionApp.setLoadingIngredients(true);
        } else {
            productionApp.isLoadingIngredients = true;
        }
        
        showLoadingNotification('Memuat bahan dari resep...');
        
        // First, find the receipt ID for this product
        console.log('Fetching receipts...');
        const receiptResponse = await fetch(`{{ route('products.get-receipt') }}`);
        
        if (!receiptResponse.ok) {
            throw new Error(`Failed to fetch receipts: ${receiptResponse.status}`);
        }
        
        const receipts = await receiptResponse.json();
        console.log('Available receipts:', receipts);
        
        // Find receipt for this product
        const receipt = receipts.find(r => r.product_id == productId);
        console.log('Found receipt for product:', receipt);
        
        if (!receipt) {
            showInfoNotification('Produk ini tidak memiliki resep');
            return;
        }
        
        // Now fetch the recipe data
        console.log('Fetching recipe data for receipt ID:', receipt.id);
        const response = await fetch(`{{ url('production/get-recipe-data') }}/${receipt.id}`);
        
        if (!response.ok) {
            throw new Error(`Failed to fetch recipe data: ${response.status}`);
        }
        
        const recipeData = await response.json();
        console.log('Recipe data received:', recipeData);
        
        if (recipeData.success && recipeData.ingredients && recipeData.ingredients.length > 0) {
            // Clear existing ingredients
            productionApp.ingredients = [];
            
            // Add recipe ingredients
            recipeData.ingredients.forEach(ingredient => {
                const ingredientData = {
                    id: ingredient.product_id || ingredient.id,
                    name: ingredient.name,
                    quantity: ingredient.quantity || 0,
                    hpp: parseFloat(ingredient.hpp || 0),
                    unit: ingredient.unit || 'pcs',
                    total: parseFloat(ingredient.quantity || 0) * parseFloat(ingredient.hpp || 0)
                };
                
                console.log('Adding ingredient:', ingredientData);
                productionApp.ingredients.push(ingredientData);
            });
            
            // Update production quantity if available
            if (recipeData.yield_quantity) {
                productionApp.productionQuantity = parseFloat(recipeData.yield_quantity);
                const quantityInput = document.getElementById('quantity');
                if (quantityInput) {
                    quantityInput.value = recipeData.yield_quantity;
                }
            }
            
            showSuccessNotification(`Berhasil memuat ${recipeData.ingredients.length} bahan dari resep`);
        } else {
            const message = recipeData.message || 'Produk ini tidak memiliki resep atau resep kosong';
            console.log('No ingredients found:', message);
            showInfoNotification(message);
        }
        
    } catch (error) {
        console.error('Error loading recipe ingredients:', error);
        showErrorNotification('Gagal memuat bahan dari resep: ' + error.message);
    } finally {
        // Always clear loading state
        try {
            const alpineElement = document.querySelector('[x-data*="productionApp"]') || document.querySelector('[x-data]');
            let productionApp = null;
            
            if (alpineElement && alpineElement._x_dataStack && alpineElement._x_dataStack[0]) {
                productionApp = alpineElement._x_dataStack[0];
            } else if (alpineElement && alpineElement.__x && alpineElement.__x.$data) {
                productionApp = alpineElement.__x.$data;
            }
            
            if (productionApp) {
                if (typeof productionApp.setLoadingIngredients === 'function') {
                    productionApp.setLoadingIngredients(false);
                } else {
                    productionApp.isLoadingIngredients = false;
                }
            }
        } catch (e) {
            console.log('Could not clear loading state:', e);
        }
    }
}

// Fallback function that works without Alpine.js
async function loadRecipeIngredientsWithoutAlpine(productId) {
    try {
        showLoadingNotification('Memuat bahan dari resep...');
        
        // Fetch receipts
        const receiptResponse = await fetch(`{{ route('products.get-receipt') }}`);
        const receipts = await receiptResponse.json();
        
        // Find receipt for this product
        const receipt = receipts.find(r => r.product_id == productId);
        
        if (!receipt) {
            showInfoNotification('Produk ini tidak memiliki resep');
            return;
        }
        
        // Fetch recipe data
        const response = await fetch(`{{ url('production/get-recipe-data') }}/${receipt.id}`);
        const recipeData = await response.json();
        
        if (recipeData.success && recipeData.ingredients && recipeData.ingredients.length > 0) {
            // Update the ingredients table directly
            updateIngredientsTableDirectly(recipeData.ingredients);
            
            // Update production quantity
            if (recipeData.yield_quantity) {
                const quantityInput = document.getElementById('quantity');
                if (quantityInput) {
                    quantityInput.value = recipeData.yield_quantity;
                }
            }
            
            showSuccessNotification(`Berhasil memuat ${recipeData.ingredients.length} bahan dari resep`);
        } else {
            showInfoNotification('Produk ini tidak memiliki resep atau resep kosong');
        }
        
    } catch (error) {
        console.error('Error in fallback method:', error);
        showErrorNotification('Gagal memuat bahan dari resep: ' + error.message);
    }
}

// Function to update ingredients table directly (fallback)
function updateIngredientsTableDirectly(ingredients) {
    const tbody = document.getElementById('kt_ecommerce_edit_order_selected_products_body');
    if (!tbody) return;
    
    // Clear existing rows
    tbody.innerHTML = '';
    
    if (ingredients.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center text-muted py-4">
                    <i class="ki-duotone ki-information-5 fs-2x text-gray-400 mb-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                    </i>
                    <div>Belum ada bahan baku yang dipilih</div>
                </td>
            </tr>
        `;
        return;
    }
    
    // Add ingredient rows
    ingredients.forEach((ingredient, index) => {
        console.log('tester');
        const total = parseFloat(ingredient.quantity || 0) * parseFloat(ingredient.hpp || 0);
        const row = `
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
                            <div class="fw-bold text-gray-800">${ingredient.name}</div>
                            <div class="text-muted fs-7">${ingredient.unit}</div>
                        </div>
                    </div>
                </td>
                <td class="text-center">
                    <div class="input-group input-group-sm" style="width: 120px;">
                        <button class="btn btn-outline-secondary btn-sm" type="button">
                            <i class="fa fa-minus"></i>
                        </button>
                        <input type="number" class="form-control form-control-sm text-center" style="width:100px"
                            value="${ingredient.quantity}" step="0.01" min="0">
                        <button class="btn btn-outline-secondary btn-sm" type="button">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </td>
                <td class="text-center">
                    <span class="fw-bold text-gray-800">Rp ${new Intl.NumberFormat('id-ID').format(ingredient.hpp)}</span>
                </td>
                <td class="text-center">
                    <span class="fw-bold text-primary">Rp ${new Intl.NumberFormat('id-ID').format(total)}</span>
                </td>
                <td class="text-end">
                    <button type="button" class="btn btn-sm btn-icon btn-light-danger" title="Hapus">
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
            <input type="hidden" name="ingredients[${index}][id]" value="${ingredient.id}">
            <input type="hidden" name="ingredients[${index}][quantity]" value="${ingredient.quantity}">
            <input type="hidden" name="ingredients[${index}][hpp]" value="${ingredient.hpp}">
        `;
        tbody.insertAdjacentHTML('beforeend', row);
    });
}

// Notification functions
function showLoadingNotification(message) {
    // You can integrate with your notification system here
    console.log('LOADING: ' + message);
    
    // Simple implementation - replace with your notification system
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Memuat...',
            text: message,
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });
    }
}

function showSuccessNotification(message) {
    console.log('SUCCESS: ' + message);
    
    if (typeof Swal !== 'undefined') {
        Swal.close(); // Close loading
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: message,
            timer: 2000,
            showConfirmButton: false
        });
    } else {
        alert('Success: ' + message);
    }
}

function showInfoNotification(message) {
    console.log('INFO: ' + message);
    
    if (typeof Swal !== 'undefined') {
        Swal.close(); // Close loading
        Swal.fire({
            icon: 'info',
            title: 'Informasi',
            text: message,
            timer: 3000,
            showConfirmButton: false
        });
    } else {
        alert('Info: ' + message);
    }
}

function showErrorNotification(message) {
    console.log('ERROR: ' + message);
    
    if (typeof Swal !== 'undefined') {
        Swal.close(); // Close loading
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: message
        });
    } else {
        alert('Error: ' + message);
    }
}
</script>


