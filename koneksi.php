<?php

// // 2 konfigurasi: untuk local [mysql] & (preview & production) [pgsql]
if (getenv('VERCEL_ENV') === 'production' || getenv('VERCEL_ENV') === 'preview') {
   // ambil nama url secara dinamis. using X-Forwarded-Proto header, which is set by Vercel to indicate whether the request was made over HTTPS.
   $protocol = (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ? 'https://' : 'http://';
   $fullUrl = $protocol . $_SERVER['HTTP_HOST'];

   define('BASEURL', $fullUrl); // untuk production

   $host = $_ENV['PG_HOST'] ?? getenv('PG_HOST');
   $port = $_ENV['PG_PORT'] ?? getenv('PG_PORT');
   $db = $_ENV['PG_DB'] ?? getenv('PG_DB');
   $user = $_ENV['PG_USER'] ?? getenv('PG_USER');
   $password = $_ENV['PG_PASSWORD'] ?? getenv('PG_PASSWORD');
   $endpoint = $_ENV['PG_ENDPOINT'] ?? getenv('PG_ENDPOINT');
   $DSN = "pgsql:host=$host;port=$port;dbname=$db;sslmode=require;options=endpoint=$endpoint";
   //pgsql:host=ep-frosty-haze-a1kda0nu.ap-southeast-1.aws.neon.tech;port=5432;dbname=neondb;sslmode=require;options=endpoint=ep-frosty-haze-a1kda0nu
   // postgres://default:ZtnC4Dg5lLzI@ep-gentle-smoke-a40kybs6.us-east-1.aws.neon.tech:5432/verceldb?sslmode=require
   define('DSN', $_ENV['PG_DSN'] ?? getenv('PG_DSN'));
   define('DB_USER', $user);
   define('DB_PASS', $password);
   define('DB_ENDPOINT', $endpoint);

   echo "<script>console.log('getenv: ". getenv('SOME') ."');</script>";
   echo "<script>console.log('verceljson or .env >getenv getenv: ". getenv('GETENV') ."');</script>";
   echo "<script>console.log('verceljson or .env >env getenv: ". getenv('ENV') ."');</script>";
   echo "<script>console.log('env _ENV: ". $_ENV['SOME'] ."');</script>";
   echo "<script>console.log('verceljson or .env >getenv _ENV: ". $_ENV['GETENV'] ."');</script>";
   echo "<script>console.log('verceljson or .env >env _ENV: ". $_ENV['ENV'] ."');</script>";
} else {
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

