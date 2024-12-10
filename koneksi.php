<?php

// // 2 konfigurasi: untuk local [mysql] & (preview & production) [pgsql]
if (true) {
// if (getenv('VERCEL_ENV') === 'production' || getenv('VERCEL_ENV') === 'preview') {
   // ambil url server secara dinamis. using X-Forwarded-Proto header, which is set by Vercel to indicate whether the request was made over HTTPS.
   // why ? karena ada production dan preview punya 2 url berbeda
   $protocol = (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ? 'https://' : 'http://';
   $fullUrl = $protocol . $_SERVER['HTTP_HOST'];
   // $fullUrl = "http://localhost:8080/ma/sig-lanjut";
   // echo "<script>console.log('fullUrl: $fullUrl');</script>";

   define('BASEURL', $fullUrl);
   define('DSN',"pgsql:host=ep-gentle-smoke-a40kybs6-pooler.us-east-1.aws.neon.tech;port=5432;dbname=verceldb;sslmode=require;options=endpoint=ep-gentle-smoke-a40kybs6-pooler");
   define('DB_USER','default');
   define('DB_PASS','ZtnC4Dg5lLzI');

} else {
   // local server
   // sesuaikan nama url !!! hati-hati terhadap konfigurasi route ke path folder
   define('BASEURL', 'http://localhost:8080/ma/sig-lanjut'); // kita arahkan ke halaman public
   define('DSN', "mysql:host=localhost:3306;dbname=poi_db;charset=utf8mb4");
   define('DB_USER', 'root');
   define('DB_PASS', '');
}

$option = [
   PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // tampilkan error
   PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // tampilkan array
   PDO::ATTR_EMULATE_PREPARES => false // lebih aman dan lebih optimal
];

$dsn = DSN;
// echo "<script>console.log('dsn: $dsn');</script>";
// $denvb = $_ENV['PG_DSN'];
// echo "<script>console.log('_ENV dsn: ',$denvb);</script>";
// $denv = getenv('PG_DSN');
// echo "<script>console.log('getenv dsn: ',$denv);</script>";
$db_user = DB_USER;
$db_pass = DB_PASS;

try {
   $kon = new PDO($dsn, $db_user, $db_pass, $option);
   // die("database berhasil diakses");

} catch (PDOException $e) {
   // Log error message with a timestamp
   die("Koneksi gagal guys 😭: " . $e->getMessage());
}

$url = BASEURL . "/"; //untuk digunakan access file
$cdn = "https://cdn.jsdelivr.net/gh/Marini34/cdn/sig-lanjut/"; // penyimpanan asset gambar