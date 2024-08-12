<p align=center> 
<img src="https://github.com/user-attachments/assets/f1630322-e227-479e-a89c-eb3013e54467" alt="Framework Kancil">
</p>

#### Framework PHP Sederhana dan Ringan

Kancil adalah framework MVC untuk PHP yang dirancang dengan mengutamakan kecepatan, kesederhanaan dan mudah dikembangkan.

Framework ini dibuat menggunakan metode OOP, tetapi susunan class dibuat sederhana dan ringkas sehingga mudah dalam kustomisasi.

Framework Kancil mendukung URL routing ber-parameter. Mendukung model dengan database MySQL (dapat menambahkan driver lain dengan penambahan class driver), menggunakan template engine pada view. Juga mendukung komunikasi API menggunakan JSON dan JWT untuk autentikasi.

Terdapat juga penanganan error yang akan memunculkan pesan yang rapi di browser pada saat terjadi kesalahan.

Secara keseluruhan Framework Kancil ini dapat digunakan untuk membuat aplikasi web yang utuh. Baik untuk mode browser, maupun sebagai backend API.

Pada bagian view, terdapat sistem template menggunakan template utama (Layout) dan template per masing-masing halaman, sehingga pembuatan aplikasi dengan banyak halaman yang tampilan yang konsisten akan menjadi lebih mudah.

Sebagai bawaan dari Framework Kancil, bagian view menggunakan Bootstrap 5.x, akan tetapi bisa diganti dengan framework CSS lainnya ataupun dikustomisasi dengan mudah dengan mengedit file HTML dan CSS pada folder assets.

---

##### Status
* Dalam pengembangan

___

##### Persyaratan Sistem
* PHP 7.4 ke atas

___

##### Fitur
* Struktur MVC (Model, View, Controller)
* Mendukung penambahan library Composer
* Konfigurasi sistem dengan .env (environtment)
* Page routing yang bisa membaca parameter
* Autentikasi dengan JWT (JSON Web Token)
* Database MySQL + driver DB lain
* Template engine untuk views
* API request dan response dalam format JSON
* Fungsi-fungsi helper
* Cache halaman  
* Dll.
___

##### Dependency
* [Dotenv ^5.6](https://github.com/vlucas/phpdotenv) - Variable environtment
* [Handlebars ^3.0](https://github.com/salesforce/handlebars-php) - Template engine
* [Firebase JWT ^6.10](https://github.com/firebase/php-jwt) - JSON Web Token


