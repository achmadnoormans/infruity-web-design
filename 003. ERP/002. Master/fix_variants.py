import re

with open('008. Karyawan/index.html', 'r') as f:
    html = f.read()

# Remove the leftover variant block
html = re.sub(r'<template x-if="product\.variants && product\.variants\.length > 0">[\s\S]*?<!-- Empty State -->', '<!-- Empty State -->', html)

with open('008. Karyawan/index.html', 'w') as f:
    f.write(html)

print("Fixed variants block!")
