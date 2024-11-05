<?php
include_once 'koneksi.php';

//Ambil data untuk CRUD
function getData()
{
    global $kon;
    $sql = "SELECT * FROM poi";
    return $kon->query($sql);
}

//Hapus Data
if (isset($_GET['delete'])) {
    // Sanitize the input
    $id = filter_var($_GET['delete'], FILTER_VALIDATE_INT);

    $stmt = $kon->prepare("DELETE FROM poi WHERE id= ?");

    if ($stmt) {
        $stmt->bind_param("i", $id);

        //Kondisi apakah berhasil atau tidak
        if ($stmt->execute()) {
            echo "Record deleted successfully.";
            header("Location:crud.php");
            exit(); // Ensure no further code is executed after redirection
        } else {
            echo "<div class='alert alert-danger'> Data Gagal dihapus.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Failed to prepare the statement.</div>";
    }
    // Close the statement
    $stmt->close();
}

//Edit Data
function getDataById($id)
{
    global $kon;
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {

        $stmt = $kon->prepare("SELECT * FROM poi WHERE id= ?");

        if ($stmt === false) {
            die("Failed to prepare the statement: " . $kon->error);
        }

        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {

            // Get the result set
            $result = $stmt->get_result();

            while ($data = $result->fetch_assoc()) {
                // Output the result or return it from a function
                return $data;
            };
        }

        // Close the connection
        $stmt->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $lat = (double) $_POST['lat'];
    $lng = (double) $_POST['lng'];

    if (isset($_POST['id'])) {
        // prepare and bind
        $id = $_POST['id'];
        $stmt = $kon->prepare("UPDATE poi SET name=?, description=?, lat=?, lng=? WHERE id=?");
        $stmt->bind_param("ssddi", $name, $description, $lat, $lng, $id);
    } else {
        // prepare and bind
        $stmt = $kon->prepare("INSERT INTO poi (name, description, lat, lng) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssdd", $name, $description, $lat, $lng);
    }
    // Execute the statement
    if ($stmt->execute()) {
        echo "POI saved successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    ;
}

