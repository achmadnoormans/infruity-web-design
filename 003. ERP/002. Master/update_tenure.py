import re
import random

with open('008. Karyawan/index.html', 'r') as f:
    html = f.read()

# 1. Update bases array with joinDate
def generate_join_date():
    year = random.randint(2018, 2023)
    month = random.randint(1, 12)
    day = random.randint(1, 28)
    return f"{year}-{month:02d}-{day:02d}"

bases_match = re.search(r'const bases = \[(.*?)\];', html, re.DOTALL)
if bases_match:
    bases_str = bases_match.group(1)
    new_bases_lines = []
    for line in bases_str.split('\n'):
        if 'name:' in line:
            jd = generate_join_date()
            line = line.replace(' }', f", joinDate: '{jd}' }}")
        new_bases_lines.append(line)
    new_bases_str = "const bases = [" + '\n'.join(new_bases_lines) + "];"
    html = html.replace(bases_match.group(0), new_bases_str)

# 2. Update result.push
push_match = re.search(r'result\.push\(\{(.*?status: \'Aktif\'.*?)\}\);', html, re.DOTALL)
if push_match:
    push_content = push_match.group(1)
    if 'joinDate:' not in push_content:
        new_push_content = push_content.replace("jabatan: b.jabatan,", "jabatan: b.jabatan, \n                            joinDate: b.joinDate,")
        html = html.replace(push_match.group(0), f"result.push({{{new_push_content}}});")

# 3. Add calculateTenure function after PAGE_SIZE: 50,
if 'calculateTenure(joinDate)' not in html:
    tenure_func = """
                calculateTenure(joinDate) {
                    if (!joinDate) return 'Belum diatur';
                    const start = new Date(joinDate);
                    const now = new Date();
                    let years = now.getFullYear() - start.getFullYear();
                    let months = now.getMonth() - start.getMonth();
                    if (months < 0) {
                        years--;
                        months += 12;
                    }
                    if (years === 0 && months === 0) return 'Baru bergabung';
                    let res = [];
                    if (years > 0) res.push(years + ' Thn');
                    if (months > 0) res.push(months + ' Bln');
                    return res.join(' ');
                },
"""
    html = html.replace('PAGE_SIZE: 50,', f'PAGE_SIZE: 50,{tenure_func}')

# 4. Insert Tenure into Desktop List (Before Action Menu)
desktop_tenure_html = """                                                <!-- Tenure Column -->
                                                <div class="shrink-0 w-28 flex flex-col items-end justify-center mr-4">
                                                    <div class="text-[13px] font-semibold text-gray-700" x-text="calculateTenure(product.joinDate)"></div>
                                                    <div class="text-[11px] text-gray-400 mt-0.5">Masa Kerja</div>
                                                </div>
"""
html = html.replace('<!-- Action Menu -->', desktop_tenure_html + '                                            <!-- Action Menu -->', 1)

# 5. Insert Tenure into Mobile List
mobile_tenure_html = """
                                                    <div class="shrink-0 flex flex-col items-end justify-center ml-3">
                                                        <div class="text-[11px] font-bold text-gray-700" x-text="calculateTenure(product.joinDate)"></div>
                                                        <div class="text-[10px] text-gray-400 mt-0.5">Masa Kerja</div>
                                                    </div>"""

mobile_marker = '<!-- Mobile List View (Laporan style: divide-y) -->'
if mobile_marker in html:
    mobile_part = html[html.find(mobile_marker):]
    
    # We find the exact div end of the left meta
    old_mobile_meta = """                                                    <div class="text-[11px] text-gray-400 mt-0.5">
                                                        <span x-text="product.jabatan || 'Belum diatur'"></span>
                                                    </div>
                                                </div>"""
    
    new_mobile_meta = old_mobile_meta + mobile_tenure_html
    mobile_part_new = mobile_part.replace(old_mobile_meta, new_mobile_meta, 1)
    
    html = html[:html.find(mobile_marker)] + mobile_part_new

# 6. Update Detail Modal
modal_search = r'<div class="flex gap-3 items-center">\s*<i class="ph-duotone ph-briefcase text-2xl text-emerald-500"></i>[\s\S]*?</div>\s*</div>\s*<div class="flex gap-3 items-center">\s*<i class="ph-duotone ph-calendar-check'
modal_add = """<div class="flex gap-3 items-center">
                             <i class="ph-duotone ph-calendar-star text-2xl text-emerald-500"></i>
                             <div>
                                 <div class="font-bold text-gray-800" x-text="formatDate(selectedDetailProduct.joinDate)"></div>
                                 <div class="text-[11px] text-gray-400 font-medium">Tanggal Masuk</div>
                             </div>
                         </div>
                         <div class="flex gap-3 items-center">
                             <i class="ph-duotone ph-timer text-2xl text-emerald-500"></i>
                             <div>
                                 <div class="font-bold text-gray-800" x-text="calculateTenure(selectedDetailProduct.joinDate)"></div>
                                 <div class="text-[11px] text-gray-400 font-medium">Masa Kerja</div>
                             </div>
                         </div>
                         <div class="flex gap-3 items-center">
                             <i class="ph-duotone ph-calendar-check"""

html = re.sub(modal_search, lambda m: m.group(0).replace('<div class="flex gap-3 items-center">\n                             <i class="ph-duotone ph-calendar-check', modal_add), html)

# 7. Update Drawer Form
drawer_search = '<!-- Cabang (Readonly for now) -->'
drawer_add = """<!-- Tanggal Masuk -->
                    <div class="w-full">
                        <label class="text-xs font-semibold text-gray-600 mb-1.5 block">Tanggal Masuk</label>
                        <input x-model="form.joinDate" type="date" class="w-full h-11 bg-white border border-gray-200 rounded-xl px-4 text-sm outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all m-0">
                    </div>
                    
                    <!-- Cabang (Readonly for now) -->"""
html = html.replace(drawer_search, drawer_add)

# 8. Update form mapping in Alpine
# In add/edit drawer setup
html = re.sub(r"this\.form = \{ name: '', jabatan: '', branch: 'Gresik [^']*' \};", "this.form = { name: '', jabatan: '', branch: 'Gresik / Driyorejo / Petiken - 001', joinDate: '' };", html)

with open('008. Karyawan/index.html', 'w') as f:
    f.write(html)

print("Updated Karyawan with Tenure!")
