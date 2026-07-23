import os
import re

ERP_DIR = "/Users/masman/Documents/003. Infruity/003. ERP"

modules = ['master', 'pembelian', 'inventaris', 'produksi', 'penjualan', 'keuangan', 'laporan', 'pengaturan']
skip_keywords = ['variations', 'options', 'handoff', 'Backup', 'design', 'patch_sidebar.py', 'extract_skill.py']

def process_file(filepath):
    with open(filepath, "r", encoding="utf-8") as f:
        content = f.read()

    original_content = content
    
    # 1. Remove submenu-container CSS
    css_pattern = r'\s*/\*\s*Sub-menu Expand/Collapse\s*\*/[\s\S]*?\.submenu-container>div\s*\{\s*overflow:\s*hidden;\s*position:\s*relative;\s*\}'
    content = re.sub(css_pattern, '', content)

    # 2. Update modules
    for mod in modules:
        content = re.sub(rf'@click\.prevent="{mod}Expanded\s*=\s*!{mod}Expanded"', f"@click.prevent=\"toggleMenu('{mod}')\"", content)
        content = re.sub(rf':class="{mod}Expanded\s*\?\s*\'active[^\']*\'\s*:\s*\'\'"', f":class=\"activeMenu === '{mod}' ? 'active shadow-lg shadow-black/10' : ''\"", content)
        content = re.sub(rf':class="{mod}Expanded\s*\?\s*\'open\'\s*:\s*\'\'"', f":class=\"activeMenu === '{mod}' ? 'open' : ''\"", content)
        content = re.sub(rf'<div class="submenu-container"\s*:class="{mod}Expanded\s*\?\s*\'open\'\s*:\s*\'\'">', f"<div x-show=\"activeMenu === '{mod}'\" x-collapse.duration.300ms>", content)

    # 3. Fix Dashboard active class
    dashboard_pattern = r'class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-white active"'
    dashboard_repl = r'class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-white " :class="activeMenu === \'dashboard\' ? \'active shadow-lg shadow-black/10\' : \'\'"'
    content = re.sub(dashboard_pattern, dashboard_repl, content)

    # Remove any stray @click.prevent="toggleMenu('dashboard')" that I might have added in 001. Dashboard
    content = content.replace("@click.prevent=\"toggleMenu('dashboard')\"", "")

    # 4. Infer activeMenu from path
    rel_path = os.path.relpath(filepath, ERP_DIR)
    parts = rel_path.split(os.sep)
    active_menu = "dashboard"
    if len(parts) > 0 and "." in parts[0]:
        active_menu = parts[0].split('.', 1)[1].strip().lower()
    
    if active_menu == "dashboard":
        active_menu = "dashboard"

    # 5. Update erpApp()
    if "function erpApp()" in content:
        if "toggleMenu(menu)" not in content:
            erp_pattern = r'(sidebarCollapsed:\s*false,)[\s\S]*?(init\(\)\s*\{)'
            replacement = rf"\1\n                activeMenu: '{active_menu}',\n\n                toggleMenu(menu) {{\n                    this.activeMenu = this.activeMenu === menu ? '' : menu;\n                }},\n\n                \2"
            content = re.sub(erp_pattern, replacement, content)
        else:
            # Update existing activeMenu variable to match the current page
            content = re.sub(r"activeMenu:\s*'[^']*',", f"activeMenu: '{active_menu}',", content)

    if content != original_content:
        with open(filepath, "w", encoding="utf-8") as f:
            f.write(content)
        return True
    return False

updated_files = 0
for root, dirs, files in os.walk(ERP_DIR):
    for file in files:
        if file.endswith(".html"):
            if any(k in file for k in skip_keywords) or any(k in root for k in skip_keywords):
                continue
            filepath = os.path.join(root, file)
            if process_file(filepath):
                print(f"Updated: {filepath}")
                updated_files += 1

print(f"Total files updated: {updated_files}")
