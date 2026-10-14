<?php
require_once "config/database.php";

if (isset($_GET['id'])) {

    $id = $_GET['id'];

    $sql = "DELETE FROM beneficiaries WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        header("Location: beneficiaries.php");
        exit;
    } else {
        echo "Error:" . mysqli_error($conn);
    }

} else {
    header("Location: beneficiaries.php");
    exit;
}

?>
