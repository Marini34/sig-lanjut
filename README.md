# Demonstrasi Sistem SIG
Sistem web: PHP tanpa framework, dengan koneksi vercel
## Database
- Local (mySQL) [skema di file `poi_db.sql`]
- Online (neondb PostgreSQL) [skema di file `poi_db.sql`]
- Hosting: [vercel](https://sig-lanjut.vercel.app/)
- CDN [Marini34/cdn](https://github.com/Marini34/cdn)

### Konfigurasi Database & Route
- Pada `koneksi.php` akan ada konfigurasi: apakah url web berjalan di hosting production (hosting vercel dan database neondb pgSQL) atau local development (laragon apache & database mySQL {manajemen DBMS bisa pakai phpmysql atau heidiSQL}).
  - file akan membaca apakah environment ada di production atau preview, jika ya maka:.
    - file akan mengecek apakah protokol berjalan di http atau https. 
    - kemudian ambil nama server, lalu buat konfigurasi koneksi pgSQL.
  - jika env bukan production yang berarti masih berjalan di local, maka lanjutkan dengan konfigurasi url menggunakan struktur folder di `laragon/www/` dan membuah koneksi database mySQL
- `neondb.py` dengan package psycopg2, dijakankan untuk membuat skema pada database online pgSQL dan menginputkan data.  

### Struktur Tampilan
- layout/ 
  - `[head.php, script.php]` tampilan dan font
  - `[navbar.php, sidebar.php]` template mobile & desktop
- `index.php` interaksi gmaps & html5-qrcode API
  - `ambil.php` dibutuhkan `index.php` untuk ambil data produk  lewat method fetch() di javascript.
- CRUD Data, pakai PDO untuk interaksi database, include file `koneksi.php`. 
  - Produk/ `[index.php, tambah.php, edit.php]` 
  - Transaksi/ `[index.hp, tambah.php, edit.php]`
    - `fungsi\check_transaction.php` digunakan transakti/tambah untuk melakukan pengecekan ke database tiap kali data diinput.
  - Toko/ `[idexphp, tambah.php, edit.php]`

### setingan ketika berjalan di vercel
- file `vercel.json` digunakan untuk konfigurasi rute dan fitur pada vercel agar berjalan di aplikasi. sepeti:
  - <i>runtime</i> yang memulai aplikasi melalui file `api/index.php`
  - <i>route</i> untuk seting berbagain route untuk file penting seperti css, karena di sistem ini vercel tidak bisa mengakses file relative terhadap fonder api/ 
  - <i>images</i> untuk bagaimana vercel mengload image dan akses file nya di folder assets/image/. 
-  `api/index.php` adalah sistem konfigurasi route di vercel. 

### Daftar Objek & Fungsi penting
- <b>PDO</b>: objek bawaan PHP untuk membaca & mengubah isi database (mySQL & PostgreSQL).
- <b>`index.php`</b> javascript API dari html5-qrcode dan g-maps untuk fungsinya. lalu Searching dan Sorting.
- <b>`fetch()`</b> pada javascript digunakan di index, cek transaksi, produk/edit, produk/tambah, toko/edit, transaksi/edit, transaksi/tambah.

