import re

with open('007. Jabatan/index.html', 'r') as f:
    html = f.read()

# 1. Ensure init() is cleaned from $watch
def clean_init(match):
    return """                init() {
                    // Logic moved to saveProduct
                },"""
html = re.sub(r'init\(\)\s*\{\s*this\.\$watch\(\'form\.baseRole\',.*?\}\);\s*\},', clean_init, html, flags=re.DOTALL)

# 2. Fix saveProduct editingProduct missing name
def fix_save(match):
    text = match.group(0)
    if 'name: generatedName' not in text:
        text = text.replace(
            "baseRole: this.form.baseRole,",
            "baseRole: this.form.baseRole,\n                            name: generatedName,"
        )
    return text

html = re.sub(r'if\s*\(this\.editingProduct\)\s*\{[\s\S]*?\} else \{', fix_save, html)

with open('007. Jabatan/index.html', 'w') as f:
    f.write(html)

print("Fixed!")
