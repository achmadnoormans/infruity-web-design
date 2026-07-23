import os
import urllib.parse
import re

def update_gallery():
    gallery_dir = "005. Gallery"
    html_file = "index.html"
    
    # 1. Get all images in the gallery folder
    valid_extensions = {".jpg", ".jpeg", ".png", ".webp", ".gif"}
    images = []
    
    if not os.path.exists(gallery_dir):
        print(f"Error: Folder '{gallery_dir}' tidak ditemukan!")
        return
        
    for file in os.listdir(gallery_dir):
        if file.startswith('.'):
            continue
        ext = os.path.splitext(file)[1].lower()
        if ext in valid_extensions:
            images.append(file)
            
    images.sort() # Sort alphabetically
    
    if not images:
        print(f"Tidak ada gambar yang ditemukan di dalam folder '{gallery_dir}'.")
        return
        
    print(f"Menemukan {len(images)} gambar di folder '{gallery_dir}'.")
    
    # 2. Build the new HTML for the gallery
    html_blocks = []
    
    # Create the block for a single image
    def create_img_block(filename):
        encoded_filename = urllib.parse.quote(filename)
        # Note: urllib.parse.quote encodes spaces to %20 and parentheses to %28/%29.
        # Browsers handle both perfectly.
        return f'''                    <div class="w-[280px] md:w-[400px] aspect-[5/4] rounded-2xl overflow-hidden group">
                        <img src="./{gallery_dir}/{encoded_filename}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                            alt="Gallery">
                    </div>'''

    html_blocks.append("                    <!-- First Set -->")
    for img in images:
        html_blocks.append(create_img_block(img))
        
    html_blocks.append("                    <!-- Duplicate Set for Infinite Marquee -->")
    for img in images:
        html_blocks.append(create_img_block(img))
        
    new_gallery_html = "\n".join(html_blocks)
    
    # 3. Read and replace in index.html
    with open(html_file, 'r', encoding='utf-8') as f:
        content = f.read()
        
    # Use regex to find the block between the dynamic markers
    pattern = r'(<!-- GALLERY DYNAMIC START -->\n).*?(\n\s*<!-- GALLERY DYNAMIC END -->)'
    
    if not re.search(pattern, content, flags=re.DOTALL):
        print("Error: Tidak dapat menemukan penanda <!-- GALLERY DYNAMIC START --> di index.html")
        return
        
    new_content = re.sub(pattern, r'\1' + new_gallery_html + r'\2', content, flags=re.DOTALL)
    
    with open(html_file, 'w', encoding='utf-8') as f:
        f.write(new_content)
        
    print("Berhasil! index.html telah diperbarui dengan gambar-gambar terbaru.")

if __name__ == "__main__":
    update_gallery()
    input("Tekan Enter untuk keluar...")
