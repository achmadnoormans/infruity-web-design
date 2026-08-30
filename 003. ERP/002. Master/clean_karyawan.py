import re

with open('008. Karyawan/index.html', 'r') as f:
    html = f.read()

# 1. REMOVE ORPHANED HTML
# Let's find the exact block:
# It starts around the stray `<template x-if="product.variants...` or `title="Klik...`
# Let's find the string 'title="Klik untuk ubah harga">' and the '<!-- Empty State -->'
start_idx = html.find('title="Klik untuk ubah harga">')
if start_idx != -1:
    # Backtrack to find the beginning of the orphaned line
    line_start = html.rfind('\n', 0, start_idx)
    end_idx = html.find('<!-- Empty State -->', start_idx)
    if end_idx != -1:
        # replace everything from line_start to end_idx
        html = html[:line_start] + '\n\n                                <!-- Empty State -->' + html[end_idx + len('<!-- Empty State -->'):]

# 2. CLEAN UP FILTERED PRODUCTS
html = html.replace('const matchVariant = p.variants && p.variants.some(v => v.name.toLowerCase().includes(q));', '')
html = html.replace('if (!matchName && !matchType && !matchVariant) return false;', 'if (!matchName && !matchType) return false;')

# 3. REMOVE JAVASCRIPT FUNCTIONS
funcs_to_remove = [
    r'\s*priceRangeDisplay\(product\) \{[\s\S]*?return[^}]*?\}',
    r'\s*toggleVariantExpand\(productId\) \{[\s\S]*?\}',
    r'\s*isVariantExpanded\(productId\) \{[\s\S]*?\}',
    r'\s*startEditVariantPrice\(product, variant\) \{[\s\S]*?\}',
    r'\s*saveVariantPrice\(product, variant\) \{[\s\S]*?\}',
    r'\s*cancelVariantPrice\(\) \{[\s\S]*?\}',
    r'\s*addVariantToProduct\(product\) \{[\s\S]*?\}',
    r'\s*removeVariantFromProduct\(product, variantId\) \{[\s\S]*?\}'
]

for pattern in funcs_to_remove:
    html = re.sub(pattern, '', html)

# 4. REMOVE JAVASCRIPT VARIABLES
html = re.sub(r'\s*// Variant accordion\n', '', html)
html = re.sub(r'\s*expandedVariantIds: \[\],', '', html)
html = re.sub(r'\s*editingVariantPrice: \{[^\}]*\},', '', html)
html = re.sub(r'\s*variants: \[\],', '', html)

with open('008. Karyawan/index.html', 'w') as f:
    f.write(html)

print("Cleaned!")
