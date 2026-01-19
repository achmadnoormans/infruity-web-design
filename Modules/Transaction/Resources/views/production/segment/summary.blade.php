<!-- Production Summary -->
<div class="d-flex flex-column gap-4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="fs-5 fw-bold">Ringkasan Produksi</span>
        <span class="badge badge-light-info fs-7" x-text="'Total: ' + ingredients.length + ' bahan'"></span>
    </div>
    
    <div class="separator separator-dashed"></div>
    
    <!-- Summary Items -->
    <div class="d-flex flex-column gap-3">
        <!-- Total Bahan -->
        <div class="d-flex justify-content-between align-items-center">
            <span class="text-gray-600">Total Bahan Baku:</span>
            <span class="fw-bold text-gray-800" x-text="ingredients.length + ' item'"></span>
        </div>
        
        <!-- Total HPP -->
        <div class="d-flex justify-content-between align-items-center">
            <span class="text-gray-600">Total HPP:</span>
            <span class="fw-bold text-primary" x-text="formatCurrency(totalHpp)"></span>
        </div>
        
        <!-- Quantity Production -->
        <div class="d-flex justify-content-between align-items-center">
            <span class="text-gray-600">Jumlah Produksi:</span>
            <span class="fw-bold text-gray-800" x-text="productionQuantity + ' unit'"></span>
        </div>
        
        <!-- HPP per Unit -->
        <div class="d-flex justify-content-between align-items-center">
            <span class="text-gray-600">HPP per Unit:</span>
            <span class="fw-bold text-success" x-text="formatCurrency(hppPerUnit)"></span>
        </div>
    </div>
    
    <div class="separator separator-dashed"></div>
    
    <!-- Production Status -->
    <div class="d-flex justify-content-between align-items-center">
        <span class="text-gray-600">Status Produksi:</span>
        <span class="badge" :class="{
            'badge-light-secondary': status === 'temp',
            'badge-light-warning': status === 'draft', 
            'badge-light-success': status === 'posting'
        }" x-text="getStatusText(status)"></span>
    </div>
    
    <!-- Production Notes -->
    <div class="mt-3">
        <label class="form-label text-gray-600">Catatan Produksi:</label>
        <textarea class="form-control form-control-sm" name="notes" rows="3" 
            placeholder="Tambahkan catatan untuk produksi ini..." x-model="notes"></textarea>
    </div>
</div>

<script>
// Helper functions for Alpine.js
function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount || 0);
}

function getStatusText(status) {
    const statusMap = {
        'temp': 'Draft Sementara',
        'draft': 'Siap Produksi',
        'posting': 'Selesai Produksi'
    };
    return statusMap[status] || 'Unknown';
}
</script>