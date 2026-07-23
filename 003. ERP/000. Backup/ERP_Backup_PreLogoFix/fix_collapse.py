import os

base_dir = '/Users/masman/Documents/003. Infruity/003. ERP'

collapse_script = '<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>'
alpine_script = '<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>'

count = 0
for root, dirs, files in os.walk(base_dir):
    for file in files:
        if file.endswith('.html') and 'variations' not in file and 'options' not in file:
            filepath = os.path.join(root, file)
            with open(filepath, 'r', encoding='utf-8') as f:
                content = f.read()
            
            if collapse_script not in content and alpine_script in content:
                # Add collapse script right before alpine script
                new_content = content.replace(
                    alpine_script,
                    f'{collapse_script}\n    {alpine_script}'
                )
                with open(filepath, 'w', encoding='utf-8') as f:
                    f.write(new_content)
                count += 1

print(f"Added Alpine collapse plugin to {count} files.")
