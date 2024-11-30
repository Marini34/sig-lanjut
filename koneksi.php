<?php

// // 2 konfigurasi: untuk local [mysql] & (preview & production) [pgsql]
if (getenv('VERCEL_ENV') === 'production' || getenv('VERCEL_ENV') === 'preview') {
   // ambil nama url secara dinamis. using X-Forwarded-Proto header, which is set by Vercel to indicate whether the request was made over HTTPS.
   $protocol = (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ? 'https://' : 'http://';
   $fullUrl = $protocol . $_SERVER['HTTP_HOST'];

   define('BASEURL', $fullUrl); // untuk production

   $host = $_ENV['PG_HOST'] ?? "ep-gentle-smoke-a40kybs6.us-east-1.aws.neon.tech";
   $port = $_ENV['PG_PORT'] ?? "5432";
   $db = $_ENV['PG_DB'] ?? "verceldb";
   //pgsql:host=ep-frosty-haze-a1kda0nu.ap-southeast-1.aws.neon.tech;port=5432;dbname=neondb;sslmode=require;options=endpoint=ep-frosty-haze-a1kda0nu
   // postgres://default:ZtnC4Dg5lLzI@ep-gentle-smoke-a40kybs6.us-east-1.aws.neon.tech:5432/verceldb?sslmode=require
   define('DSN', "pgsql:host=ep-gentle-smoke-a40kybs6.us-east-1.aws.neon.tech;port=5432;dbname=verceldb;sslmode=require;options=endpoint=ep-gentle-smoke-a40kybs6");
   define('DB_USER', $_ENV['PG_USER'] ?? "default");
   define('DB_PASS', $_ENV['PG_PASSWORD'] ?? "ZtnC4Dg5lLzI");
   define('DB_ENDPOINT', $_ENV['PG_ENDPOINT'] ?? "ep-gentle-smoke-a40kybs6");

   echo "<script>console.log('getenv: ". getenv('PG_HOST') ."');</script>";
   echo "<script>console.log('_ENV: ". $_ENV['PG_HOST'] ."');</script>";
} else {
   // echo "<script>
   //       alert('Database mySQL');
   //   </script>";
   define('DB_NOW_MYSQL', true);
   // sesuaikan nama url !!! hati-hati terhadap konfigurasi route ke server
   define('BASEURL', 'http://localhost:8080/ma/sig-lanjut'); // kita arahkan ke halaman public
   // var_dump($host, $port, $db, DB_USER, DB_PASS);
   define('DSN', "mysql:host=localhost:3306;dbname=poi_db;charset=utf8mb4");
   define('DB_USER', 'root');
   define('DB_PASS', '');
}

$option = [
   // PDO::ATTR_PERSISTENT => true, // tidak perlu koneksi berulang
   PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
   PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
   PDO::ATTR_EMULATE_PREPARES => false
];
$dsn = DSN;
$db_user = DB_USER;
$db_pass = DB_PASS;

try {
   $kon = new PDO($dsn, $db_user, $db_pass, $option);
   // die("database berhasil diakses");

} catch (PDOException $e) {
   // Log error message with a timestamp
   die("Koneksi gagal guys 😭: " . $e->getMessage());
}

$url = BASEURL . "/"; //untuk digunakan
$cdn = "https://cdn.jsdelivr.net/gh/Marini34/cdn/sig-lanjut/";
// var_dump($url);
// function isActive($url) {
//    return ($_SERVER['REQUEST_URI'] === "/marini/sig-lanjut/".$url) ? 'active' : '';
// }

