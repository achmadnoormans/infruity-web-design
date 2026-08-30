import re

with open("010. Metode Pembayaran/index.html", "r") as f:
    html = f.read()

# Replace strings
html = html.replace("Metode Pembayaran", "Jabatan")
html = html.replace("Metode", "Jabatan")
html = html.replace("metode", "jabatan")
html = html.replace("MTD-", "JBT-")
html = html.replace("ph-wallet", "ph-briefcase")

dummy_data = """
                products: [
                    { id: 1, uniqueId: 'JBT-0001', baseRole: 'Manajemen Data', name: 'Manajemen Data - 0001', createdAt: '2025-01-10T08:00:00', updatedAt: '2025-01-10T08:00:00' },
                    { id: 2, uniqueId: 'JBT-0002', baseRole: 'Kru Toko', name: 'Kru Toko - 0001', createdAt: '2025-01-10T08:00:00', updatedAt: '2025-01-10T08:00:00' },
                    { id: 3, uniqueId: 'JBT-0003', baseRole: 'Kru Gerai Pagi', name: 'Kru Gerai Pagi - 0001', createdAt: '2025-01-10T08:00:00', updatedAt: '2025-01-10T08:00:00' },
                    { id: 4, uniqueId: 'JBT-0004', baseRole: 'Pembuat Konten', name: 'Pembuat Konten - 0001', createdAt: '2025-01-10T08:00:00', updatedAt: '2025-01-10T08:00:00' },
                    { id: 5, uniqueId: 'JBT-0005', baseRole: 'Kru Toko', name: 'Kru Toko - 0004', createdAt: '2025-01-10T08:00:00', updatedAt: '2025-01-10T08:00:00' },
                    { id: 6, uniqueId: 'JBT-0006', baseRole: 'Admin Toko', name: 'Admin Toko - 0001', createdAt: '2025-01-10T08:00:00', updatedAt: '2025-01-10T08:00:00' },
                    { id: 7, uniqueId: 'JBT-0007', baseRole: 'Kru Kurir', name: 'Kru Kurir - 0001', createdAt: '2025-01-10T08:00:00', updatedAt: '2025-01-10T08:00:00' },
                    { id: 8, uniqueId: 'JBT-0008', baseRole: 'Kru Gerai Pagi', name: 'Kru Gerai Pagi - 0003', createdAt: '2025-01-10T08:00:00', updatedAt: '2025-01-10T08:00:00' },
                    { id: 9, uniqueId: 'JBT-0009', baseRole: 'Kru Toko', name: 'Kru Toko - 0002', createdAt: '2025-01-10T08:00:00', updatedAt: '2025-01-10T08:00:00' },
                    { id: 10, uniqueId: 'JBT-0010', baseRole: 'Kru Gerai Pagi', name: 'Kru Gerai Pagi - 0002', createdAt: '2025-01-10T08:00:00', updatedAt: '2025-01-10T08:00:00' },
                    { id: 11, uniqueId: 'JBT-0011', baseRole: 'Admin Toko', name: 'Admin Toko - 0002', createdAt: '2025-01-10T08:00:00', updatedAt: '2025-01-10T08:00:00' },
                    { id: 12, uniqueId: 'JBT-0012', baseRole: 'Kru Toko', name: 'Kru Toko - 0003', createdAt: '2025-01-10T08:00:00', updatedAt: '2025-01-10T08:00:00' },
                ],
"""
html = re.sub(r'products:\s*\(\(\) => \{[\s\S]*?\}\)\(\),', dummy_data, html)

form_data = """
                form: {
                    baseRole: '',
                    name: '',
                    uniqueId: ''
                },
"""
html = re.sub(r'form:\s*\{[^}]*\},', form_data, html)

init_logic = """
                init() {
                    this.$watch('form.baseRole', (val) => {
                        if (val) {
                            let maxSeq = 0;
                            this.products.forEach(p => {
                                if (p.baseRole === val && p.id !== this.editingId) {
                                    const match = p.name.match(/- (\d+)$/);
                                    if (match) {
                                        const num = parseInt(match[1]);
                                        if (num > maxSeq) maxSeq = num;
                                    }
                                }
                            });
                            
                            if (this.editingId) {
                                const current = this.products.find(p => p.id === this.editingId);
                                if (current && current.baseRole === val) {
                                    this.form.name = current.name;
                                    return;
                                }
                            }

                            const nextSeq = String(maxSeq + 1).padStart(4, '0');
                            this.form.name = `${val} - ${nextSeq}`;
                        } else {
                            this.form.name = '';
                        }
                    });
                },
                
                openModal(isEdit = false, product = null) {
"""
html = html.replace("openModal(isEdit = false, product = null) {", init_logic)

html = html.replace("this.form.name = product.name;", "this.form.name = product.name;\n                        this.form.baseRole = product.baseRole;")
html = html.replace("this.form.name = '';", "this.form.name = '';\n                        this.form.baseRole = '';")


form_html = """
                                <div class="p-6 space-y-6">
                                    <div class="space-y-1.5">
                                        <label class="block text-sm font-semibold text-gray-700">Posisi Utama <span class="text-strawberry">*</span></label>
                                        <div class="relative">
                                            <select x-model="form.baseRole"
                                                class="w-full pl-4 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none"
                                                required>
                                                <option value="" disabled selected>Pilih Posisi Utama</option>
                                                <option value="Manajemen Data">Manajemen Data</option>
                                                <option value="Kru Toko">Kru Toko</option>
                                                <option value="Kru Gerai Pagi">Kru Gerai Pagi</option>
                                                <option value="Pembuat Konten">Pembuat Konten</option>
                                                <option value="Admin Toko">Admin Toko</option>
                                                <option value="Kru Kurir">Kru Kurir</option>
                                            </select>
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                                <i class="ph-bold ph-caret-down"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-1.5">
                                        <label class="block text-sm font-semibold text-gray-700">Nama Jabatan Lengkap</label>
                                        <input type="text" x-model="form.name"
                                            class="w-full px-4 py-2.5 bg-gray-100 border border-gray-200 rounded-xl text-sm font-bold text-gray-700 cursor-not-allowed focus:outline-none"
                                            readonly>
                                        <p class="text-[11px] text-gray-400 mt-1">Dihasilkan otomatis berdasarkan posisi dan urutan.</p>
                                    </div>
"""

html = re.sub(r'<div class="p-6 space-y-6">[\s\S]*?<div class="mt-8 flex justify-end gap-3 pt-6 border-t border-gray-100">', form_html + '\n                                </div>\n                                <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-gray-100">', html)

# Modify the detail modal
html = html.replace('<div class="font-bold text-gray-800" x-text="selectedDetailProduct.name"></div>\n                                 <div class="text-[11px] text-gray-400 font-medium">Nama Jabatan</div>', 
'<div class="font-bold text-gray-800" x-text="selectedDetailProduct.baseRole"></div>\n                                 <div class="text-[11px] text-gray-400 font-medium">Posisi Utama</div>\n                             </div>\n                         </div>\n                         <div class="flex gap-3 items-center">\n                             <i class="ph-duotone ph-briefcase text-2xl text-emerald-500"></i>\n                             <div>\n                                 <div class="font-bold text-gray-800" x-text="selectedDetailProduct.name"></div>\n                                 <div class="text-[11px] text-gray-400 font-medium">Nama Jabatan Lengkap</div>')


with open("007. Jabatan/index.html", "w") as f:
    f.write(html)
