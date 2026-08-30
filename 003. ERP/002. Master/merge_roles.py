import re

with open('008. Karyawan/index.html', 'r') as f:
    html = f.read()

bases_match = re.search(r'const bases = \[(.*?)\];', html, re.DOTALL)
if bases_match:
    bases_str = bases_match.group(1)
    
    # We will manually construct the new bases string to be safe
    new_bases = """
                        { name: 'Achmad Noorman Setiawan', jabatan: 'Manajemen Data 1', joinDate: '2020-03-22' },
                        { name: 'Al Afghani', jabatan: 'Kru Toko 1, Kru Stan Pagi 1', joinDate: '2022-07-16' },
                        { name: 'Alfina Zakya Ula', jabatan: 'Kreator Konten 1', joinDate: '2019-11-05' },
                        { name: 'Dwi Febbrianti Robbiatul Adawiyah', jabatan: 'Kru Toko 4', joinDate: '2021-02-14' },
                        { name: 'Maharani Purwitasari', jabatan: 'Admin Toko 1', joinDate: '2018-08-09' },
                        { name: 'Muhammad Chamim', jabatan: 'Kru Kurir 1', joinDate: '2023-04-30' },
                        { name: 'Muhammad Nur Wa\\'Id', jabatan: 'Kru Stan Pagi 3', joinDate: '2022-09-12' },
                        { name: 'Riniatur Rojiya', jabatan: 'Kru Toko 2, Kru Stan Pagi 2', joinDate: '2020-10-01' },
                        { name: 'Salmatul Farida', jabatan: 'Admin Toko 2', joinDate: '2021-12-25' },
                        { name: 'Zulki Arga Rahman', jabatan: 'Kru Toko 3', joinDate: '2019-06-18' }
"""
    html = html.replace(bases_str, new_bases)
    
    with open('008. Karyawan/index.html', 'w') as f:
        f.write(html)
    print("Merged duplicate employees!")
else:
    print("Could not find bases array")
