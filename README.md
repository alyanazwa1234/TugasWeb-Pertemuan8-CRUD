# Tugas Rutin 8 - CRUD Inventaris

Aplikasi inventaris produk sederhana yang dibuat menggunakan PHP Native, MySQL, PDO, dan HTML/CSS untuk memenuhi tugas rutin Pemrograman Web.

## Fitur

* Menampilkan daftar produk dengan JOIN tabel kategori dan supplier.
* Menambahkan produk melalui formulir dengan dropdown kategori dan supplier.
* Mengedit produk dengan formulir yang terisi otomatis.
* Menghapus produk dengan konfirmasi.
* Mencari produk berdasarkan nama, kategori, atau supplier.
* Menggunakan PDO Singleton untuk koneksi database.
* Menggunakan prepared statements untuk query database.
* Menggunakan foreign key untuk menghubungkan tabel.
* Menggunakan `htmlspecialchars()` untuk sanitasi output.
* Menampilkan pesan sukses atau gagal setelah operasi CRUD.

## Struktur Database

Nama database: `inventaris_db`

Tabel yang digunakan:

1. `kategori`
2. `supplier`
3. `produk`

## Teknologi

* PHP Native
* MySQL
* PDO
* HTML dan CSS
* XAMPP
* Visual Studio Code

## Cara Menjalankan Aplikasi

1. Jalankan Apache dan MySQL melalui XAMPP.

2. Buka phpMyAdmin melalui `http://localhost/phpmyadmin`.

3. Pilih menu **SQL**.

4. Salin isi file `schema.sql`, kemudian jalankan untuk membuat database dan tabel beserta data awal.

5. Buka folder proyek melalui Visual Studio Code.

6. Jalankan server PHP dari terminal di folder proyek:

   ```bash
   C:\xampp\php\php.exe -S localhost:8000
   ```

7. Buka aplikasi melalui browser di alamat berikut:

   `http://localhost:8000`

## Screenshot Aplikasi
## Screenshot Aplikasi

### Halaman Daftar Produk

![Halaman Daftar Produk](screenshots/1.Halaman%20Daftar%20Produk.png)

### Form Tambah Produk

![Form Tambah Produk](screenshots/2.Form%20Tambah%20Produk.png)

### Form Edit Produk

![Form Edit Produk](screenshots/3.Form%20Edit%20Produk.png)

### Produk Berhasil Ditambahkan

![Produk Berhasil Ditambahkan](screenshots/4.Produk%20Berhasil%20di%20Tambahkan.png)

### Produk Berhasil Diedit

![Produk Berhasil Diedit](screenshots/5.Produk%20Berhasil%20di%20Edit.png)

## Repository GitHub

Nama repository: `TugasWeb-Pertemuan8-CRUD`

Repository ini berisi source code aplikasi CRUD Inventaris dan file `schema.sql` untuk membuat database.
