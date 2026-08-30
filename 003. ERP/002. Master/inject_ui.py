import re

# Read Jabatan HTML
with open('007. Jabatan/index.html', 'r') as f:
    jabatan_html = f.read()

# Extract the Bottom Navigation Bar, Drawer, and Modals
start_marker = "<!-- ================================ -->\n            <!-- UNIFIED BOTTOM NAVIGATION BAR    -->"
end_marker = "</div><!-- /x-data -->"

start_idx = jabatan_html.find(start_marker)
end_idx = jabatan_html.find(end_marker, start_idx)

if start_idx != -1 and end_idx != -1:
    missing_html = jabatan_html[start_idx:end_idx]
    
    # Replace "Jabatan" with "Karyawan" in the UI text
    missing_html = missing_html.replace('Tambah Jabatan', 'Tambah Karyawan')
    missing_html = missing_html.replace('Edit Jabatan', 'Edit Karyawan')
    missing_html = missing_html.replace('Hapus Jabatan', 'Hapus Karyawan')
    missing_html = missing_html.replace('jabatan ini', 'karyawan ini')
    missing_html = missing_html.replace('Jabatan berhasil', 'Karyawan berhasil')
    
    # Let's fix the Drawer form fields for Karyawan
    # Jabatan had: "Nama Posisi" (form.baseRole)
    # We want: "Nama Karyawan" (form.name) and "Jabatan" (form.jabatan)
    
    # First, let's just replace the form container
    form_start = missing_html.find('<div class="flex flex-col gap-5">')
    form_end = missing_html.find('</div>\n                </div>', form_start)
    
    if form_start != -1 and form_end != -1:
        new_form = """<div class="flex flex-col gap-5">

                    <!-- Nama Karyawan -->
                    <div class="w-full">
                        <label class="text-xs font-semibold text-gray-600 mb-1.5 block">Nama Karyawan <span class="text-red-400">*</span></label>
                        <input x-model="form.name" type="text" placeholder="Misal: John Doe" class="w-full h-11 bg-white border border-gray-200 rounded-xl px-4 text-sm outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all m-0">
                    </div>
                    
                    <!-- Jabatan -->
                    <div class="w-full">
                        <label class="text-xs font-semibold text-gray-600 mb-1.5 block">Jabatan <span class="text-red-400">*</span></label>
                        <input x-model="form.jabatan" type="text" placeholder="Misal: Kru Toko" class="w-full h-11 bg-white border border-gray-200 rounded-xl px-4 text-sm outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all m-0">
                    </div>
                    
                    <!-- Cabang (Readonly for now) -->
                    <div class="w-full">
                        <label class="text-xs font-semibold text-gray-600 mb-1.5 block">Cabang</label>
                        <input x-model="form.branch" type="text" readonly disabled class="w-full h-11 bg-gray-100 border border-gray-200 rounded-xl px-4 text-sm text-gray-500 outline-none cursor-not-allowed m-0">
                    </div>

                """
        missing_html = missing_html[:form_start] + new_form + missing_html[form_end:]
    
    # Now read Karyawan HTML
    with open('008. Karyawan/index.html', 'r') as f:
        karyawan_html = f.read()
    
    # Inject it before </div><!-- /x-data -->
    karyawan_html = karyawan_html.replace('</div><!-- /x-data -->', missing_html + '\n</div><!-- /x-data -->')
    
    with open('008. Karyawan/index.html', 'w') as f:
        f.write(karyawan_html)
    
    print("Injected successfully!")
else:
    print("Could not find markers in Jabatan HTML")
