import re

with open('008. Karyawan/index.html', 'r') as f:
    html = f.read()

new_bases = """                    const bases = [
                        { name: 'Achmad Noorman Setiawan', jabatan: 'Manajemen Data 1' },
                        { name: 'Al Afghani', jabatan: 'Kru Toko 1' },
                        { name: 'Al Afghani', jabatan: 'Kru Stan Pagi 1' },
                        { name: 'Alfina Zakya Ula', jabatan: 'Kreator Konten 1' },
                        { name: 'Dwi Febbrianti Robbiatul Adawiyah', jabatan: 'Kru Toko 4' },
                        { name: 'Maharani Purwitasari', jabatan: 'Admin Toko 1' },
                        { name: 'Muhammad Chamim', jabatan: 'Kru Kurir 1' },
                        { name: 'Muhammad Nur Wa\\'Id', jabatan: 'Kru Stan Pagi 3' },
                        { name: 'Riniatur Rojiya', jabatan: 'Kru Toko 2' },
                        { name: 'Riniatur Rojiya', jabatan: 'Kru Stan Pagi 2' },
                        { name: 'Salmatul Farida', jabatan: 'Admin Toko 2' },
                        { name: 'Zulki Arga Rahman', jabatan: 'Kru Toko 3' }
                    ];"""

html = re.sub(r'const bases = \[[\s\S]*?\];', new_bases, html)

# We also need to update the push logic to use b.jabatan instead of ''
push_logic = """                        result.push({
                            id: id++, 
                            name: b.name, 
                            jabatan: b.jabatan, 
                            uniqueId: 'KRY-' + String(id-1).padStart(4, '0'),
                            createdAt: new Date().toISOString(), 
                            updatedAt: new Date().toISOString(),
                            themeClass: themes[idx % themes.length],
                            branch: 'Gresik / Driyorejo / Petiken - 001',
                            status: 'Aktif'
                        });"""
html = re.sub(r'result\.push\(\{[\s\S]*?status: \'Aktif\'\n\s*\}\);', push_logic, html)

with open('008. Karyawan/index.html', 'w') as f:
    f.write(html)

print("Data updated!")
