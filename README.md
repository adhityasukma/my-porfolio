# My Portfolio WordPress Theme

**My Portfolio** adalah tema WordPress kustom yang dirancang secara khusus untuk menampilkan profil profesional, proyek portofolio, dan riwayat pekerjaan (Work Experience) dengan desain _dark-theme_ yang modern, premium, dan dinamis. Tema ini menggunakan elemen _glassmorphism_, gradasi warna yang elegan, serta transisi animasi halus untuk memberikan kesan _high-end_.

## Fitur Utama

### 1. Modern & Responsive Design

- Mengusung estetika _dark mode_ modern dengan _color palette_ gradasi (ungu ke biru).
- _Fully responsive_ di berbagai ukuran layar (Desktop, Tablet, Mobile).
- Menggunakan CSS kustom (tanpa framework berat) untuk kontrol layout yang maksimal dan performa tinggi.
- Efek _hover_ yang hidup dan bayangan bercahaya (glow shadows) pada komponen-komponen utama (kartu, tombol, navigasi).

### 2. Theme Customizer (Pengaturan Tema)

Pengaturan tema terpusat di **Appearance > Customize > Theme Settings**, memungkinkan Anda mengubah hampir seluruh teks dan tautan di halaman utama tanpa menyentuh kode:

- **Hero Section**: Mengubah Nama, Subtitle (Jabatan), Deskripsi, Teks/Link Tombol CTA (Primary & Secondary), serta Foto Profil.
- **About Section**: Mengubah Judul, Badge (label kecil di atas judul), dan Deskripsi.
- **Skills Section**: Mendefinisikan keahlian (dipisahkan tanda koma) yang terbagi menjadi 5 kategori:
  - _Frontend Development_
  - _Backend Development_
  - _DevOps & Tools_
  - _Integration & Middleware_
  - _Database_
    _(Terdapat opsi Checkbox untuk menampilkan/menyembunyikan masing-masing kategori)._
- **Portfolio Section**: Mengubah Judul, Badge, dan teks/tautan untuk tombol "View All".
- **Contact & Social Media**: Mengatur alamat Email, Phone, Location, dan tautan sosial media (LinkedIn, GitHub, Twitter).
- **Site Identity & Tracking**: Input untuk Google Analytics ID.

### 3. Custom Post Type: Portfolio

Sistem manajemen proyek portofolio khusus.

- **Meta Boxes**: Input khusus untuk _Tech Stack_ (pisahkan dengan koma), _Project Link_ (URL ke aplikasi/website), dan _Year_ (Tahun pembuatan).
- **Homepage Integration**: Menampilkan maksimal 6 _Featured Projects_ terbaru di halaman utama dalam bentuk _grid_ 3 kolom.
- **Archive Page (`archive-portfolio.php`)**: Halaman khusus portofolio dengan limit 6 data per halaman. Dilengkapi desain _pagination_ modern bergaya _button_.
- **Card Layout**: Desain kartu portofolio presisi tanpa padding berlebih pada gambar (`object-fit: cover`), serta bagian _footer_ kartu (Tech Stack & View Project link) yang selalu rapi di bawah (_floating bottom_).

### 4. Custom Post Type: Work Experience

Sistem manajemen riwayat pekerjaan berbentuk _timeline_.

- **Meta Boxes**: Input detail untuk _Company Name_, _Start Date_, _End Date_ (bisa diisi 'Present'), _Location_, dan **Tipe Kerja** (_dropdown_ opsi: Remote, WFH, WFO, Hybrid).
- **Smart Sorting**: Secara otomatis mengurutkan riwayat pekerjaan secara kronologis berdasarkan bulan dan tahun (_Start Date_ & _End Date_) dari yang paling baru hingga terlama, mendukung format `MM/YYYY`, `MM YYYY`, tahun, maupun kata `Present`.
- **Admin Enhancements**: Tabel daftar Work Experience di WP Admin dilengkapi dengan kolom khusus (Company, Duration, Location, Tipe Kerja) yang **bisa di-sorting**, serta dropdown **Filter** berdasarkan Tipe Kerja.
- **Shortcode Display**: Tampilkan _timeline_ riwayat pekerjaan di mana saja (Posts/Pages) menggunakan _shortcode_.
  - Menampilkan semua riwayat: `[work_experience]`
  - Menampilkan spesifik berdasarkan ID: `[work_experience id="12,15"]`
  - Mengubah urutan (terlama ke terbaru): `[work_experience order="asc"]`
    _(Dokumentasi penggunaan shortcode + fitur Copy to Clipboard tersedia di menu admin Work Experience > Shortcode)._

### 5. Built-in Visitor Tracker

Tema ini memiliki modul pelacakan pengunjung internal (`class-visitor-tracker.php`) yang melacak IP, User Agent, dan halaman yang dikunjungi tanpa bergantung pada plugin eksternal.

## Struktur File Utama

- `front-page.php` - Template halaman utama (Hero, About, Skills, Portofolio).
- `functions.php` - Registrasi tema, enqueue script/style, CPT Portfolio, dan utilitas.
- `inc/customizer.php` - Logika registrasi pengaturan Theme Customizer.
- `inc/cpt-work-experience.php` - Logika lengkap untuk CPT Work Experience, meta boxes, admin columns, dan shortcode.
- `inc/class-visitor-tracker.php` - Skrip pelacakan statistik pengunjung (opsional).
- `assets/css/theme-style.css` - Kode styling utama (CSS variables, layout, komponen).
- `assets/js/theme-script.js` - Skrip fungsionalitas (navigasi, scroll effects).
- `archive-portfolio.php` - Template daftar/arsip semua portofolio.
- `single-portfolio.php` - Template halaman detail sebuah proyek portofolio.

## Instalasi & Penggunaan

1. Unggah folder tema `my-porfolio` ke direktori `wp-content/themes/` di instalasi WordPress Anda.
2. Masuk ke WP Admin > **Appearance > Themes**, lalu aktifkan tema **My Portfolio**.
3. Masuk ke **Settings > Reading**, set "Your homepage displays" ke **A static page**, dan pilih halaman yang menggunakan template Default/Front Page.
4. Masuk ke **Appearance > Customize > Theme Settings** untuk mulai mengisi teks, keahlian, dan tautan sosial media Anda.
5. Tambahkan data pekerjaan Anda di menu **Work Experience** dan portofolio Anda di menu **Portfolios**.
6. (Opsional) Jika thumbnail/gambar tidak muncul, pastikan Anda men-set "Featured Image" pada setiap post Portfolio dan Work Experience.

---

_Dibuat untuk kebutuhan personal branding berstandar profesional._

Made with ❤️ by Adhitya Sukma
