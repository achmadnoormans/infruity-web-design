#!/usr/bin/env python3
"""
Fix iOS Safari auto-zoom on input focus.
iOS Safari zooms in when input font-size < 16px.
Solution: inject CSS rule to force font-size: 16px on all inputs.
Also adds touch-action: manipulation to prevent 300ms tap delay.
"""
import os
import re

BASE_DIR = "/Users/masman/Documents/003. Infruity/003. ERP"

CSS_RULE = """
        /* ======================== */
        /* iOS Safari Anti-Zoom Fix */
        /* ======================== */
        input, input[type="text"], input[type="email"], input[type="password"],
        input[type="number"], input[type="search"], input[type="tel"],
        input[type="url"], input[type="date"], input[type="datetime-local"],
        input[type="time"], select, textarea {
            font-size: 16px !important;
            touch-action: manipulation;
        }"""

MARKER = "/* iOS Safari Anti-Zoom Fix */"

def inject_css(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    # Skip if already has the fix
    if MARKER in content:
        print(f"  SKIP (already fixed): {filepath}")
        return False

    # Find </style> tag to inject before it
    # We want to inject into the FIRST <style> block's closing tag
    match = re.search(r'</style>', content)
    if not match:
        print(f"  SKIP (no <style> tag): {filepath}")
        return False

    insert_pos = match.start()
    new_content = content[:insert_pos] + CSS_RULE + "\n    " + content[insert_pos:]

    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(new_content)

    print(f"  FIXED: {filepath}")
    return True

# Find all index.html files, exclude Backup and vercel-ready
fixed_count = 0
skipped_count = 0

for root, dirs, files in os.walk(BASE_DIR):
    # Exclude backup and vercel dirs
    dirs[:] = [d for d in dirs if d not in ['000. Backup', 'vercel-ready', '.git', '.vscode', 'assets']]
    
    for filename in files:
        if filename.endswith('.html'):
            filepath = os.path.join(root, filename)
            result = inject_css(filepath)
            if result:
                fixed_count += 1
            else:
                skipped_count += 1

print(f"\nDone! Fixed: {fixed_count} files, Skipped: {skipped_count} files")
