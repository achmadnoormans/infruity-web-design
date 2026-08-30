import os
import re
import json

jabatan_path = "002. Master/007. Jabatan/index.html"
akun_path = "002. Master/011. Akun Pengguna/index.html"

with open(jabatan_path, 'r', encoding='utf-8') as f:
    html = f.read()

# Replace Jabatan words with Akun Pengguna
html = html.replace('Jabatan - Master', 'Akun Pengguna - Master')
html = html.replace('Modul Master Jabatan', 'Modul Master Akun Pengguna')
html = html.replace('Daftar Jabatan', 'Akun Pengguna')
html = html.replace('Kelola struktur jabatan', 'Kelola akun pengguna dan hak akses')
html = html.replace('Tambah Jabatan', 'Tambah Akun')
html = html.replace('Cari jabatan...', 'Cari akun...')
html = html.replace("products: (() => {", "users: (() => {")
html = html.replace("this.products.filter", "this.users.filter")

# Generate new users data
users = [
    {'email': 'ADMINISTRATOR@INFRUITY.COM', 'role': 'Administrator'},
    {'email': 'OWNER@INFRUITY.COM', 'role': 'Owner'}
]

for i in range(1, 11):
    users.append({'email': f'OPR{i:03d}@INFRUITY.COM', 'role': 'Operator'})

js_array_str = "[\n"
themes = ["bg-emerald-50 text-emerald-600 border-emerald-100", "bg-blue-50 text-blue-600 border-blue-100", "bg-amber-50 text-amber-600 border-amber-100", "bg-purple-50 text-purple-600 border-purple-100"]

for i, u in enumerate(users):
    theme = themes[i % len(themes)]
    js_array_str += f"                        {{ id: {i+1}, email: '{u['email']}', name: '{u['role']}', uniqueId: 'USR-{str(i+1).zfill(4)}', themeClass: '{theme}' }},\n"
js_array_str += "                    ]"

# We need to replace the products array generation in the JS
pattern = re.compile(r'products:\s*\(\(\)\s*=>\s*\{.*?\n\s*\}\)\(\),', re.DOTALL)
html = pattern.sub(f"products: {js_array_str},", html)


# Fix the html bindings
# Instead of product.name in the row, we show product.email
html = html.replace('x-text="product.name.length > 23 ? product.name.substring(0, 23) + \'...\' : product.name"', 'x-text="product.email"')
html = html.replace('x-text="product.name.substring(0, 2).toUpperCase()"', 'x-text="product.email.substring(0, 2).toUpperCase()"')
html = html.replace('x-text="product.deskripsi || \'Belum diatur\'"', 'x-text="product.name"')

# Change activeMenu
html = html.replace("activeMenu: 'jabatan',", "activeMenu: 'master',")
html = html.replace("activePage === '002. Master/007. Jabatan/index.html'", "activePage === '002. Master/011. Akun Pengguna/index.html'")


with open(akun_path, 'w', encoding='utf-8') as f:
    f.write(html)
print("Akun Pengguna UI built successfully.")
