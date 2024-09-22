<?php
// Koneksi ke database
// $host = 'localhost';  // Sesuaikan dengan host database Anda
// $user = 'root';       // Sesuaikan dengan username database Anda
// $pass = '';           // Sesuaikan dengan password database Anda
// $dbname = 'my_database'; // Sesuaikan dengan nama database Anda

// $conn = new mysqli($host, $user, $pass, $dbname);

// // Cek koneksi
// if ($conn->connect_error) {
//     die("Koneksi gagal: " . $conn->connect_error);
// }

if (isset($_POST['submit'])) {
    // Ambil data dari form
    // $nama = $_POST['nama'];
    // $usia = $_POST['usia'];
    echo 'work';
    // // Simpan data ke database
    // $sql = "INSERT INTO users (nama, usia) VALUES ('$nama', '$usia')";
    $logMessage = "Data berhasil dikirim: Nama";
    
    // if ($conn->query($sql) === TRUE) {
    //     // Menampilkan data di console.log
    //     echo "<script>console.log('Data berhasil dikirim: Nama = $nama, Usia = $usia');</script>";
    // } else {
    //     echo "Error: " . $sql . "<br>" . $conn->error;
    // }
} else {
  echo 'not work';
}

// $conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Input</title>
</head>
<body>
    <h1>Form Input Nama dan Usia</h1>
    <form method="POST" action="">
        <label for="nama">Nama:</label>
        <input type="text" id="nama" name="nama" value="a" required><br><br>

        <label for="usia">Usia:</label>
        <input type="number" id="usia" name="usia" value="21" required><br><br>

        <button type="submit" name="submit">Submit</button>
    </form>

    <?php
    // Jika ada pesan log, kirimkan ke console.log
    if (isset($logMessage)) {
        echo "<script>console.log('$logMessage');</script>";
    }
    ?>
</body>
</html>
