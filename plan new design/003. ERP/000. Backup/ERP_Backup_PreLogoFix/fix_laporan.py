import re
import os

dashboard_path = '/Users/masman/Documents/003. Infruity/003. ERP/001. Dashboard/index.html'
laporan_path = '/Users/masman/Documents/003. Infruity/003. ERP/008. Laporan/001. Laporan Penjualan/index.html'

with open(dashboard_path, 'r') as f:
    dashboard = f.read()

with open(laporan_path, 'r') as f:
    laporan_corrupted = f.read()

# 1. Get Base Layout from Dashboard
# Everything before <main>
main_start = dashboard.find('<main class="flex-1')
base_layout = dashboard[:main_start]

# 2. Fix the Base Layout Header (Title & Meta)
base_layout = re.sub(r'<title>.*?</title>', '<title>Laporan Penjualan - Laporan | ERP Portal</title>', base_layout)
base_layout = re.sub(r'<meta name="description".*?>', '<meta name="description" content="Modul Laporan Laporan Penjualan - Sistem ERP Infruity.">', base_layout)

# 3. Extract the Laporan Penjualan JS state block from the corrupted file
# It starts at "searchQuery: ''," inside the Pindah Stok sidebar item
js_state_start = laporan_corrupted.find("    searchQuery: '',")
js_state_end = laporan_corrupted.find('}">', js_state_start)
laporan_js = laporan_corrupted[js_state_start:js_state_end]

# 4. Extract the Laporan Penjualan filter bar & table from the corrupted file
# It starts at "<!-- Filter & Search Bar -->" and ends at "<!-- Empty State -->" closing div
content_start = laporan_corrupted.find('<!-- Filter & Search Bar -->')
content_end = laporan_corrupted.find('<!-- Empty State -->')
content_end = laporan_corrupted.find('</div>', content_end + 20) + 6 # find the closing div of Empty State
laporan_content = laporan_corrupted[content_start:content_end]

# 5. Extract erpApp() from Dashboard
script_start = dashboard.find('<script>')
script_end = dashboard.find('</script>', script_start) + 9
erp_script = dashboard[script_start:script_end]

# Modify activeMenu to be 'laporan' instead of 'dashboard'
erp_script = re.sub(r"activeMenu:\s*'[^']*'", "activeMenu: 'laporan'", erp_script)

# Stitch it all together
new_laporan = base_layout
new_laporan += f'''            <main class="flex-1 overflow-y-auto overscroll-none px-4 sm:px-6 lg:px-8 py-6 flex flex-col" x-show="activeMenu === 'laporan'">

                <div class="animate-fade-in-up w-full flex-1 flex flex-col min-h-0">
                    <!-- Main Content Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex-1 flex flex-col min-h-0" x-data="{{
{laporan_js}}}">
                        
                        {laporan_content}
                        
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    {erp_script}
</body>
</html>
'''

with open(laporan_path, 'w') as f:
    f.write(new_laporan)
print("Repaired Laporan Penjualan/index.html")
