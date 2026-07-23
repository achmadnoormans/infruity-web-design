import os

target_path = '/Users/masman/Documents/003. Infruity/003. ERP/009. Pengaturan/003. Target/index.html'
laporan_path = '/Users/masman/Documents/003. Infruity/003. ERP/008. Laporan/001. Laporan Penjualan/index.html'

with open(target_path, 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Update Title and Meta
content = content.replace('<title>Target - Pengaturan | ERP Portal</title>', '<title>Laporan Penjualan - Laporan | ERP Portal</title>')
content = content.replace('<meta name="description" content="Modul Pengaturan Target - Sistem ERP Infruity.">', '<meta name="description" content="Modul Laporan Penjualan - Sistem ERP Infruity.">')

# 2. Update activeMenu in erpApp
content = content.replace("activeMenu: 'pengaturan',", "activeMenu: 'laporan',")

# 3. Update the CONTENT label comment
content = content.replace('<!-- CONTENT: Target -->', '<!-- CONTENT: Laporan Penjualan -->')

# 4. We also need to change x-show="activeMenu === 'pengaturan'" on the <main> tag (if any) or inside it.
# Actually, in Target, there is NO x-show on <main>. The content is just inside <main>.

with open(laporan_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Berhasil menduplikasi Target ke Laporan Penjualan.")
