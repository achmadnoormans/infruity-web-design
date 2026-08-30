import os
import re

root_dir = "."

# We want to move the "Akun Pengguna" anchor block ABOVE the "Jabatan" anchor block.
# We will use Regex to capture the entire <a>...</a> blocks.
# The pattern should match <a ... > \n ... \n <span>Akun Pengguna</span> \n </a>
akun_pengguna_pattern = re.compile(r'(\s*<a[^>]*?(?:Akun Pengguna)[^>]*?>\s*(?:<[^>]+>\s*)*<span>Akun Pengguna</span>\s*</a>)', re.DOTALL | re.IGNORECASE)
jabatan_pattern = re.compile(r'(\s*<a[^>]*?(?:Jabatan)[^>]*?>\s*(?:<[^>]+>\s*)*<span>Jabatan</span>\s*</a>)', re.DOTALL | re.IGNORECASE)


for subdir, dirs, files in os.walk(root_dir):
    if "000. Backup" in subdir or ".git" in subdir:
        continue
    for file in files:
        if file.endswith(".html"):
            filepath = os.path.join(subdir, file)
            with open(filepath, 'r', encoding='utf-8') as f:
                content = f.read()
            
            # Find the Akun Pengguna block
            match_akun = akun_pengguna_pattern.search(content)
            if not match_akun:
                continue
                
            match_jabatan = jabatan_pattern.search(content)
            if not match_jabatan:
                continue

            # Check if they are in the wrong order
            if match_akun.start() < match_jabatan.start():
                continue # Already in correct position
            
            akun_block = match_akun.group(1)
            
            # Remove the Akun block from its current position
            content = content[:match_akun.start()] + content[match_akun.end():]
            
            # Need to find Jabatan position again since string changed
            match_jabatan = jabatan_pattern.search(content)
            if match_jabatan:
                # Insert Akun block before Jabatan block
                content = content[:match_jabatan.start()] + akun_block + content[match_jabatan.start():]
                
                with open(filepath, 'w', encoding='utf-8') as f:
                    f.write(content)
                print(f"Reordered menu in {filepath}")
