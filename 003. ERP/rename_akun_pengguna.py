import os

root_dir = "."
search_str = "009. Pengaturan/002. Akun Pengguna"
replace_str = "002. Master/011. Akun Pengguna"

for subdir, dirs, files in os.walk(root_dir):
    if "000. Backup" in subdir or ".git" in subdir:
        continue
    for file in files:
        if file.endswith(".html"):
            filepath = os.path.join(subdir, file)
            with open(filepath, 'r', encoding='utf-8') as f:
                content = f.read()
            if search_str in content:
                new_content = content.replace(search_str, replace_str)
                with open(filepath, 'w', encoding='utf-8') as f:
                    f.write(new_content)
                print(f"Updated {filepath}")
