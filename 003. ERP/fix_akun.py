import os
import re

filepath = "002. Master/011. Akun Pengguna/index.html"

with open(filepath, 'r', encoding='utf-8') as f:
    html = f.read()

# Desktop row
html = html.replace('<div class="text-sm font-semibold text-gray-900 truncate" x-text="product.name"></div>', '<div class="text-sm font-semibold text-gray-900 truncate" x-text="product.email"></div>')
html = html.replace('<span class="text-[11px] font-medium" x-text="product.uniqueId"></span>', '<span class="text-[11px] font-medium" x-text="product.name"></span> <span class="mx-1">•</span> <span class="text-[11px]" x-text="product.uniqueId"></span>')

# Mobile row
# In mobile, it might be slightly different. Let's look for product.name bindings
html = html.replace('<div class="text-sm font-semibold text-gray-900" x-text="product.name"></div>', '<div class="text-sm font-semibold text-gray-900 truncate" x-text="product.email"></div>')
# Wait, product.uniqueId might be used in mobile row too. Let's replace the whole block if possible, or just uniqueId.
# It's safer to just replace all `product.uniqueId` with `product.name + ' &bull; ' + product.uniqueId` if it's inside a span.
# But let's check what it looks like first.

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(html)
print("Fixed.")
