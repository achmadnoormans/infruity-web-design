import re

with open('008. Karyawan/index.html', 'r') as f:
    html = f.read()

# 1. Replace Dummy Data
new_dummy_data = """                products: (() => {
                    const bases = [
                        { name: 'Achmad Noorman Setiawan' },
                        { name: 'Al Afghani' },
                        { name: 'Al Afghani' },
                        { name: 'Alfina Zakya Ula' },
                        { name: 'Dwi Febbrianti Robbiatul Adawiyah' },
                        { name: 'Maharani Purwitasari' },
                        { name: 'Muhammad Chamim' },
                        { name: 'Muhammad Nur Wa\\'Id' },
                        { name: 'Riniatur Rojiya' },
                        { name: 'Riniatur Rojiya' },
                        { name: 'Salmatul Farida' },
                        { name: 'Zulki Arga Rahman' }
                    ];
                    let id = 1;
                    const result = [];
                    const themes = ['bg-emerald-50 text-emerald-600 border-emerald-100', 'bg-blue-50 text-blue-600 border-blue-100', 'bg-amber-50 text-amber-600 border-amber-100', 'bg-orange-50 text-orange-600 border-orange-100', 'bg-teal-50 text-teal-600 border-teal-100', 'bg-purple-50 text-purple-600 border-purple-100', 'bg-sky-50 text-sky-600 border-sky-100', 'bg-rose-50 text-rose-600 border-rose-100'];
                    
                    bases.forEach((b, idx) => {
                        result.push({
                            id: id++, 
                            name: b.name, 
                            jabatan: '', 
                            uniqueId: 'KRY-' + String(id-1).padStart(4, '0'),
                            createdAt: new Date().toISOString(), 
                            updatedAt: new Date().toISOString(),
                            themeClass: themes[idx % themes.length],
                            status: 'Aktif'
                        });
                    });
                    return result;
                })(),"""
html = re.sub(r'products:\s*\(\(\)\s*=>\s*\{[\s\S]*?\}\)\(\),', new_dummy_data, html)

# 2. Update filteredProducts
new_filtered = """                get filteredProducts() {
                    return this.products.filter(p => {
                        if (this.searchQuery) {
                            const q = this.searchQuery.toLowerCase();
                            const matchName = p.name.toLowerCase().includes(q);
                            const matchJabatan = (p.jabatan || '').toLowerCase().includes(q);
                            const matchId = p.uniqueId.toLowerCase().includes(q);
                            if (!matchName && !matchJabatan && !matchId) return false;
                        }
                        return true;
                    });
                },"""
html = re.sub(r'get filteredProducts\(\)\s*\{[\s\S]*?return true;\s*\}\s*\},\s*', new_filtered + '\n\n', html)

# 3. Update Desktop List Item Meta
def fix_desktop_meta(match):
    return """                                                    <div class="text-[11px] text-gray-400 mt-1 flex items-center gap-2">
                                                        <span class="font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100" x-text="product.jabatan || 'Belum diatur'"></span>
                                                        <span class="text-gray-300">&bull;</span>
                                                        <span class="font-medium" x-text="product.uniqueId"></span>
                                                    </div>"""
html = re.sub(r'<div class="text-xs text-gray-400 mt-0.5">\s*<span class="text-\[11px\] font-medium" x-text="product.uniqueId"></span>\s*</div>', fix_desktop_meta, html)

# 4. Remove Price column from Desktop
html = re.sub(r'<!-- Price column -->\s*<div class="shrink-0 w-44 flex items-center justify-end">.*?</div>\s*<!-- Status -->', '<!-- Status -->', html, flags=re.DOTALL)

# 5. Remove Right column from Mobile
html = re.sub(r'<!-- Right: Price \(range for variant, single for normal\) \+ action -->\s*<div class="shrink-0 flex items-center gap-1 ml-2">.*?</div>\s*</div>\s*</div>\s*</template>', '</div>\n                                        </div>\n                                    </template>', html, flags=re.DOTALL)

with open('008. Karyawan/index.html', 'w') as f:
    f.write(html)

print("Generated!")
