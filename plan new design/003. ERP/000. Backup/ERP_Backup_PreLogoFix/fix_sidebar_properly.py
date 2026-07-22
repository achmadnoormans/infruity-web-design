import os

ERP_DIR = "/Users/masman/Documents/003. Infruity/003. ERP"
skip_keywords = ['variations', 'options', 'handoff', 'Backup', 'design', 'patch_sidebar.py', 'fix_sidebar_properly.py', 'extract_skill.py']

# 1. Get the perfect nav from Dashboard
dashboard_file = os.path.join(ERP_DIR, "001. Dashboard", "index.html")
with open(dashboard_file, "r", encoding="utf-8") as f:
    dashboard_content = f.read()

start_nav = dashboard_content.find('<nav id="sidebar-nav"')
end_nav = dashboard_content.find('</nav>', start_nav) + 6
perfect_nav_str = dashboard_content[start_nav:end_nav]

# Remove all hardcoded 'active' classes from the perfect nav template's submenus
# They look like: class="submenu-link ... text-white/45 active"
# We just replace ' active"' with '"'
perfect_nav_str = perfect_nav_str.replace(' active"', '"')
# Fix the broken backslashes if they somehow got into the Dashboard nav
perfect_nav_str = perfect_nav_str.replace("\\'", "'")

def process_file(filepath):
    with open(filepath, "r", encoding="utf-8") as f:
        content = f.read()

    # Determine depth relative to 003. ERP
    rel_path = os.path.relpath(filepath, ERP_DIR)
    parts = rel_path.split(os.sep)
    depth = len(parts) - 1
    
    prefix = "../" * depth

    # Create a customized nav for this file
    custom_nav_str = perfect_nav_str.replace('href="../', f'href="{prefix}')

    # Add the active class to the current page's submenu link
    target_href = prefix + rel_path.replace(os.sep, '/')
    
    # We find href="target_href" and then find the closing quote and class="..." to inject 'active'
    # Actually, simpler: replace `href="target_href"\n                                class="submenu-link` with `href="target_href"\n                                class="submenu-link active`
    # Let's just find the exact target_href in custom_nav_str
    
    # Let's split custom_nav_str by lines and inject ' active' in the line AFTER the target_href
    nav_lines = custom_nav_str.split("\n")
    for i, line in enumerate(nav_lines):
        if f'href="{target_href}"' in line:
            # The next line usually has the class
            if i + 1 < len(nav_lines) and 'class="submenu-link' in nav_lines[i+1]:
                nav_lines[i+1] = nav_lines[i+1].replace('class="submenu-link', 'class="submenu-link active')
                break
    
    final_nav_str = "\n".join(nav_lines)

    # Now replace the nav in the target file
    start_old = content.find('<nav id="sidebar-nav"')
    end_old = content.find('</nav>', start_old) + 6
    if start_old != -1 and end_old != -1:
        new_content = content[:start_old] + final_nav_str + content[end_old:]
    else:
        new_content = content

    # Fix any remaining \' in Alpine attributes outside nav
    new_content = new_content.replace("\\'", "'")

    # Fix erpApp
    active_menu = "dashboard"
    if len(parts) > 0 and "." in parts[0]:
        active_menu = parts[0].split('.', 1)[1].strip().lower()
    if active_menu == "dashboard":
        active_menu = "dashboard"
        
    import re
    erp_pattern = r'(sidebarCollapsed:\s*false,)[\s\S]*?(init\(\)\s*\{)'
    replacement = rf"\1\n                activeMenu: '{active_menu}',\n\n                toggleMenu(menu) {{\n                    this.activeMenu = this.activeMenu === menu ? '' : menu;\n                }},\n\n                \2"
    
    if "function erpApp()" in new_content:
        # We need to make sure we don't duplicate toggleMenu
        if "toggleMenu(menu)" in new_content:
            # Just fix the activeMenu value
            new_content = re.sub(r"activeMenu:\s*'[^']*',", f"activeMenu: '{active_menu}',", new_content)
        else:
            new_content = re.sub(erp_pattern, replacement, new_content)

    if new_content != content:
        with open(filepath, "w", encoding="utf-8") as f:
            f.write(new_content)
        return True
    return False

updated = 0
for root, dirs, files in os.walk(ERP_DIR):
    for file in files:
        if file.endswith(".html"):
            if any(k in file for k in skip_keywords) or any(k in root for k in skip_keywords):
                continue
            filepath = os.path.join(root, file)
            try:
                if process_file(filepath):
                    updated += 1
                    print(f"Updated: {filepath}")
            except Exception as e:
                print(f"Failed {filepath}: {e}")

print(f"Total fixed files: {updated}")
