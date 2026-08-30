import re

with open("007. Jabatan/index.html", "r") as f:
    html = f.read()

themes = [
    "bg-emerald-50 text-emerald-600 border-emerald-100",
    "bg-blue-50 text-blue-600 border-blue-100",
    "bg-orange-50 text-orange-600 border-orange-100",
    "bg-purple-50 text-purple-600 border-purple-100",
    "bg-rose-50 text-rose-600 border-rose-100"
]

def add_theme(match):
    text = match.group(0)
    lines = text.split('\n')
    new_lines = []
    theme_idx = 0
    for line in lines:
        if "'JBT-" in line and "updatedAt" in line:
            theme = themes[theme_idx % len(themes)]
            line = line.replace(" },", f", themeClass: '{theme}' }},")
            theme_idx += 1
        new_lines.append(line)
    return '\n'.join(new_lines)

html = re.sub(r'products:\s*\[[\s\S]*?\],', add_theme, html)

with open("007. Jabatan/index.html", "w") as f:
    f.write(html)
