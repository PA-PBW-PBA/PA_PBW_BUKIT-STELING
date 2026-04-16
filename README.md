<h1 align="center">Puncak Steling Samarinda</h2>

<p align="center"><em>Nikmati Senja di Puncak Steling</em></p>

<p align="center">
  <img width="1535" height="606" alt="Nikmati senja di Puncak Steling" src="https://github.com/user-attachments/assets/d1f620cb-03d4-41e1-a9f9-c48a7c6cdaf1" />

</p>

### Kelompok Alakadarnya

| **Nama**                         | **NIM**     | **Kelas**            |
|----------------------------------|------------|----------------------|
| Rizqy                            | 2409116039 | Sistem Informasi A   |
| Jen Agresia Misti                | 2409116007 | Sistem Informasi A   |
| Maifariza Aulia Dyas             | 2409116032 | Sistem Informasi A   |
| Yardan Raditya Rafi’ Widyadhana  | 2409116037 | Sistem Informasi A   |

## Deskripsi Project

Website Puncak Stelling Samarinda merupakan sebuah website yang dibuat untuk memberikan informasi tentang salah satu tempat wisata alam yang ada di Samarinda, yaitu Puncak Stelling. Website ini dibuat agar masyarakat bisa lebih mudah mendapatkan informasi mengenai tempat wisata tersebut tanpa harus datang langsung ke lokasi.

Di dalam website ini terdapat berbagai informasi seperti deskripsi tempat, lokasi, harga tiket, jam operasional, dan fasilitas yang tersedia. Selain itu, terdapat juga fitur galeri yang menampilkan foto-foto Puncak Stelling sehingga pengunjung bisa melihat gambaran suasana tempat wisata tersebut.

Website ini dapat diakses tanpa perlu login, sehingga siapa saja bisa langsung melihat informasi yang tersedia. Pengunjung juga dapat memberikan ulasan, kritik, dan saran melalui fitur yang telah disediakan. Selain itu, terdapat halaman khusus admin yang digunakan untuk mengelola isi website. Admin dapat menambahkan, mengubah, dan menghapus data wisata, mengelola galeri, serta menghapus ulasan yang tidak sesuai. 

## Struktur File

<details>
<summary>Aplikasi ini diimplementasikan dengan arsitektur MVC (Model-View-Controller)</summary>

```
puncak_steling/
├── assets/
│   ├── css/
│   │   └── style.css
│   ├── img/
│   │   ├── fasilitas/
│   │   └── galeri/
│   └── js/
├── config/
│   ├── api_stats.php
│   └── koneksi.php
├── controllers/
│   ├── AdminController.php
│   ├── AuthController.php
│   ├── FasilitasController.php
│   ├── GaleriController.php
│   └── UlasanController.php
├── models/
│   ├── FasilitasModel.php
│   ├── GaleriModel.php
│   ├── InformasiModel.php
│   ├── StatistikModel.php
│   ├── UlasanModel.php
│   └── UserModel.php
├── public/
│   ├── beranda.php
│   ├── galeri.php
│   ├── informasi.php
│   ├── profil.php
│   ├── proses_like.php
│   ├── proses_ulasan.php
│   ├── tentang.php
│   ├── ulasan.php
│   └── unggah_foto.php
├── templates/
│   ├── footer.php
│   ├── header.php
│   ├── navbar_public.php
│   └── sidebar_admin.php
├── views/
│   ├── admin/
│   │   ├── aksi_balas_ulasan.php
│   │   ├── aksi_edit_fasilitas.php
│   │   ├── aksi_hapus_fasilitas.php
│   │   ├── aksi_hapus_galeri.php
│   │   ├── aksi_hapus_ulasan.php
│   │   ├── aksi_setujui_galeri.php
│   │   ├── aksi_tambah_fasilitas.php
│   │   ├── api_stats.php
│   │   ├── dashboard.php
│   │   ├── kelola_fasilitas.php
│   │   ├── kelola_galeri.php
│   │   ├── kelola_informasi.php
│   │   ├── kelola_ulasan.php
│   │   └── statistik.php
│   └── auth/
│       ├── login.php
│       ├── logout.php
│       └── register.php
├── db_bukit_steling.sql
└── index.php
```
</details>

---

## Teknologi yang Digunakan

| Teknologi | Keterangan |
|----------|------------|
| PHP | Digunakan untuk menjalankan logika website dan menghubungkan ke database |
| MySQL | Digunakan untuk menyimpan data seperti pengguna, galeri, ulasan, fasilitas, dan informasi wisata |
| HTML5 | Digunakan untuk membuat struktur halaman website |
| CSS3 | Digunakan untuk mengatur tampilan dan desain website |
| Bootstrap | Digunakan untuk membuat tampilan website lebih rapi dan responsif |
| Laragon | Digunakan sebagai server lokal untuk menjalankan website |

---

## Arsitektur

```
index.php
├── koneksi.php                  → koneksi ke database MySQL
├── Controller (server-side)     → menerima request & mengatur alur
│   ├── AuthController           → proses login & registrasi
│   ├── GaleriController         → proses upload & tampil galeri
│   ├── UlasanController         → proses ulasan & rating
│   └── AdminController          → pengelolaan data oleh admin
│
├── Model                        → mengelola data dari database
│   ├── UserModel                → ambil & simpan data pengguna
│   ├── GaleriModel              → ambil & simpan data galeri
│   ├── UlasanModel              → ambil & simpan data ulasan
│   ├── FasilitasModel           → ambil data fasilitas
│   └── InformasiModel           → ambil data informasi wisata
│
├── View                         → menampilkan data ke halaman
│   ├── beranda                  → tampilan halaman utama
│   ├── galeri                   → tampilan foto wisata
│   ├── ulasan                   → tampilan ulasan pengunjung
│   ├── informasi                → tampilan harga & jam operasional
│   └── dashboard admin          → tampilan pengelolaan data
│
├── Bootstrap 5                  → layout dan komponen UI
└── style.css                    → tampilan custom website
```

---

## Alur Kerja


1. Menjalankan **Laragon** untuk mengaktifkan server lokal (Apache & MySQL)  
2. Membuka website melalui browser:  
   `http://localhost:4466/PA_PBW_BUKIT-STELING-main/views/public/beranda.php`  
3. Sistem memproses halaman yang diakses melalui `index.php`  
4. `koneksi.php` menghubungkan aplikasi dengan database MySQL  
5. Controller mengatur alur sesuai request pengguna   
6. Model mengelola data dari database  
7. View menampilkan hasil ke halaman website  
8. Browser menampilkan halaman kepada pengguna  

---

## Setup & Instalasi

---

## Struktur Database

### Tabel `tb_admin`

| Kolom | Tipe | Keterangan |
|------|------|-----------|
| id_admin | INT | Primary key (auto increment) |
| nama_lengkap | VARCHAR(100) | Nama admin |
| email | VARCHAR(100) | Email admin |
| password | VARCHAR(255) | Password admin |
| created_at | TIMESTAMP | Waktu pembuatan data |

### Tabel `tb_pengunjung`

| Kolom | Tipe | Keterangan |
|------|------|-----------|
| id_pengunjung | INT | Primary key (auto increment) |
| nama_lengkap | VARCHAR(100) | Nama pengguna |
| email | VARCHAR(100) | Email pengguna |
| password | VARCHAR(255) | Password pengguna |
| created_at | TIMESTAMP | Waktu registrasi |

### Tabel `tb_ulasan`

| Kolom | Tipe | Keterangan |
|------|------|-----------|
| id_ulasan | INT | Primary key (auto increment) |
| id_pengunjung | INT | Relasi ke pengguna |
| rating | INT | Nilai rating |
| komentar | TEXT | Isi ulasan |
| balasan_admin | TEXT | Balasan dari admin |
| tanggal_ulasan | TIMESTAMP | Waktu ulasan dibuat |

### Tabel `tb_fasilitas`

| Kolom | Tipe | Keterangan |
|------|------|-----------|
| id_fasilitas | INT | Primary key (auto increment) |
| nama_fasilitas | VARCHAR(100) | Nama fasilitas |
| icon | VARCHAR(50) | Icon fasilitas |
| file_gambar | VARCHAR(255) | Gambar fasilitas |

### Tabel `tb_informasi`

| Kolom | Tipe | Keterangan |
|------|------|-----------|
| id_info | INT | Primary key (auto increment) |
| harga_tiket | INT | Harga tiket |
| jam_buka | TIME | Jam buka |
| jam_tutup | TIME | Jam tutup |
| deskripsi | TEXT | Deskripsi wisata |
| tata_tertib | TEXT | Aturan atau tata tertib |

### Tabel `tb_like`

| Kolom | Tipe | Keterangan |
|------|------|-----------|
| id_like | INT | Primary key (auto increment) |
| id_galeri | INT | Relasi ke galeri |
| id_pengunjung | INT | Relasi ke pengguna |
| tanggal_like | TIMESTAMP | Waktu like |


---

## Nilai Tambah

### MVC (Model View Controller)

Struktur folder disusun menggunakan konsep MVC untuk memisahkan data, tampilan, dan logika program, sehingga kode lebih rapi dan mudah dikelola.

---

## Fitur Utama

### Pengguna 

- Mengakses website tanpa login
- Melihat halaman beranda
- Melihat informasi wisata (deskripsi, harga tiket, jam operasional)
- Melihat fasilitas yang tersedia
- Melihat struktur organisasi Puncak Steling
- Melihat galeri foto
- Melihat ulasan dari pengunjung lain

### Fitur Tambahan Pengunjung (Setelah Login)

- Registrasi Akun 
- Login ke dalam sistem
- Menambahkan ulasan dan rating (_Create_)
- Mengunggah foto ke galeri (_Create_)
- Melihat profil dan riwayat kontribusi


### Admin

- Login ke halaman admin
- Melihat data pada dashboard (_Read_)
- Mengelola informasi wisata (_Create_,_Read_, _Update_, _Delete_)
- Mengelola fasilitas (_Create_, _Read_, _Update_, _Delete_)
- Mengelola galeri foto (_Read_, _Delete_)
- Mengelola data ulasan pengunjung 



---

## Tampilan _Website_

### ✧˖°. Halaman Beranda
  > <img width="1905" height="941" alt="Screenshot 2026-04-11 093459" src="https://github.com/user-attachments/assets/ec58eae7-24ea-43fc-9227-4970cc33479c" />
  > Halaman beranda adalah halaman pertama yang muncul saat website dibuka. Di halaman ini, pengguna bisa langsung melihat tampilan utama website serta menu navigasi di bagian atas yang memudahkan pengguna pindah ke halaman lain. Selain itu, tersedia juga tombol yang membantu pengguna untuk mulai menjelajahi fitur atau melihat informasi yang tersedia di dalam website steling ini.


### ✧˖°. Halaman Login
  > <img width="1895" height="943" alt="Screenshot 2026-04-11 093532" src="https://github.com/user-attachments/assets/7d6884f2-ed8f-40c3-b19f-df8e65a3821e" />
  > Halaman login digunakan pengguna untuk masuk ke dalam website menggunakan akun yang sudah terdaftar. Disini, pengguna diminta memasukkan email serta password. Setelah data dimasukkan dengan benar, pengguna bisa langsung masuk ke website dan mengakses fitur sesuai dengan perannya, seperti admin atau pengunjung.


### ✧˖°. Halaman Register
  > <img width="1897" height="942" alt="Screenshot 2026-04-11 093554" src="https://github.com/user-attachments/assets/04bb8d18-3153-40e7-b905-6bc2f8468f66" />
  > Halaman register merupakan halaman yang digunakan untuk membuat akun baru. Pengguna yang ingin mengakses fitur dalam website perlu mendaftar terlebih dahulu dengan mengisi data seperti nama, email, dan password. Setelah itu, pengguna dapat menekan tombol daftar untuk menyelesaikan proses pendaftaran.

  
### ✧˖°. Halaman Informasi Wisata
  > <img width="1794" height="2271" alt="image" src="https://github.com/user-attachments/assets/8741865b-b306-46a0-92db-1c246cb40370" />
  > Halaman informasi wisata merupakan halaman yang menampilkan informasi lengkap terkait tempat wisata yang tersedia. Halaman ini bisa diakses oleh semua pengunjung, baik yang sudah memiliki akun maupun yang belum. Pengguna bisa melihat informasi seperti harga tiket, lokasi, deskripsi, serta fasilitas yang tersedia. Di dalam fitur ini, pengunjung jadi bisa mengetahui informasi wisata secara lebih jelas sebelum berkunjung.


### ✧˖°. Halaman Galeri
  > <img width="1794" height="2421" alt="image" src="https://github.com/user-attachments/assets/4ced6238-d7ce-467c-8cd6-1451a40209a0" />

  > Halaman galeri merupakan halaman yang menampilkan kumpulan foto atau dokumentasi dari Puncak Steling. Halaman ini bisa diakses oleh semua pengunjung. Pengguna bisa melihat berbagai foto yang telah diunggah serta filter tampilan berdasarkan kategori yang tersedia. Disini, pengunjung jadi bisa melihat suasana dan kondisi Puncak Steling secara lebih jelas.


### ✧˖°. Halaman Ulasan
  > <img width="1794" height="1728" alt="image" src="https://github.com/user-attachments/assets/79e96b25-b643-4506-ba54-4c8312e2dec3" />
  > Halaman ulasan merupakan halaman yang menampilkan penilaian dan komentar dari pengunjung Puncak Steling. Halaman ini bisa diakses oleh semua pengunjung, namun untuk memberikan ulasan, pengguna harus melakukan login terlebih dahulu.


### ✧˖°. Halaman Tentang
  > <img width="1794" height="2225" alt="image" src="https://github.com/user-attachments/assets/7eda034b-9cd3-40d7-b9b4-e69233bd1c66" />
  > Halaman tentang merupakan halaman yang menampilkan informasi umum mengenai Puncak Steling. Pengunjung bisa melihat deskripsi, lokasi, serta informasi lain yang berkaitan dengan tempat wisata ini. Selain itu, pengunjung juga bisa melihat struktur organisasi pengelola Puncak Steling.


### ✧˖°. Halaman Dashboard Admin
  > <img width="1910" height="887" alt="image" src="https://github.com/user-attachments/assets/43e2796a-568d-4704-b3ee-0c6b4f643e65" />
  > Halaman dashboard admin merupakan halaman utama yang ditampilkan setelah admin login ke dalam website. Di halaman ini, admin bisa melihat ringkasan informasi seperti rating, jumlah foto, dan jumlah pengguna baru. Selain itu, admin juga bisa langsung mengakses fitur untuk mengelola data yang ada. Di dashboard ini, admin bisa memantau kondisi sistem dengan lebih mudah.


### ✧˖°. Halaman Analisis Wisata
  > <img width="1908" height="880" alt="image" src="https://github.com/user-attachments/assets/c7d56f55-a51e-4502-9975-5f3b3cf49ed9" />
  > Halaman ini merupakan halaman yang menampilkan data dan statistik terkait Puncak Steling. Admin bisa melihat grafik jumlah pengunjung serta distribusi kepuasan berdasarkan ulasan yang ada. Nah, data ini ditampilkan dalam bentuk grafik agar lebih mudah dipahami untuk melihat perkembangan dan performa wisata dengan lebih jelas.

  
### ✧˖°. Halaman Kelola Informasi Wisata (Admin)
  > <img width="1908" height="865" alt="image" src="https://github.com/user-attachments/assets/4cc709ca-ef03-4084-a9e2-e8084073babd" />
  > Halaman kelola informasi wisata digunakan oleh admin untuk mengatur data informasi steling. Di halaman ini, admin bisa mengubah harga tiket serta jam buka dan tutup. Setelah itu, admin menyimpan perubahan yang sudah dilakukan.


### ✧˖°. Halaman Manajemen Fasilitas
  > <img width="1911" height="926" alt="image" src="https://github.com/user-attachments/assets/aac0c220-0c13-4d74-ae30-5813df37ad92" />
  > Halaman kelola fasilitas digunakan oleh admin untuk mengatur fasilitas yang tersedia di puncak steling. Di halaman ini, admin bisa menambah, mengubah, dan menghapus fasilitas yang ditampilkan di website. Selain itu, admin juga dapat melihat daftar fasilitas yang sudah ada.


### ✧˖°. Halaman Manajemen Galeri
  > <img width="1911" height="945" alt="image" src="https://github.com/user-attachments/assets/0ad1cd92-6529-4e32-a5f8-c4d8de374899" />
  > Halaman manajemen galeri digunakan oleh admin untuk mengelola foto yang diunggah oleh pengunjung. Admin bisa melihat antrean foto yang masih menunggu persetujuan. Lalu, Admin menyetujui foto agar ditampilkan di galeri atau menghapus foto jika tidak sesuai. Selain itu, admin juga dapat melihat daftar foto yang sudah dipublikasikan.


### ✧˖°. Halaman Manajemen Ulasan
  > <img width="1910" height="944" alt="image" src="https://github.com/user-attachments/assets/35d11cce-5a6b-4965-bc26-3c5a48267cdf" />
  > Halaman manajemen ulasan ini digunakan oleh admin untuk mengelola komentar dari pengunjung. Admin bisa melihat daftar ulasan yang masuk beserta rating dan komentar yang diberikan. Di sini admin bisa membalas ulasan pengunjung, melihat status ulasan, serta menghapus ulasan yang tidak sesuai atau melanggar aturan (kelewatan).


### ✧˖°. Upload Foto (Pengunjung)
  > <img width="1887" height="938" alt="image" src="https://github.com/user-attachments/assets/94680496-4c98-42ba-899f-0bb8dea968be" />
  > Halaman ini digunakan oleh pengunjung untuk membagikan foto ke galeri Puncak Steling. Pengguna bisa mengunggah foto yang dimiliki, namun foto tersebut tidak langsung ditampilkan. Foto akan masuk ke antrean terlebih dahulu untuk ditinjau oleh admin. Jika sudah disetujui, barulah foto akan muncul di galeri.

### ✧˖°. Ulasan Pengunjung (Pengunjung)
  > <img width="1909" height="942" alt="image" src="https://github.com/user-attachments/assets/cc6b388b-7aa5-4c68-bbaf-d7b893a6d920" />
  > Halaman ini digunakan oleh pengunjung untuk melihat dan memberikan ulasan terkait Puncak Steling. Pengunjung bisa melihat berbagai ulasan yang sudah ada, lengkap dengan rating dan komentar. Selain itu, pengguna yang sudah login juga bisa menuliskan ulasan mereka sendiri untuk dibagikan kepada pengunjung lainnya.


---


<p align="center">
Wisata Puncak Stling Samarinda<br>
</p>

