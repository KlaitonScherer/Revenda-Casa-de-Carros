<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}


$conn = new mysqli('localhost', 'root', '', 'casadecarros');

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "DELETE FROM carros WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        header("Location: admin_list.php");
    } else {
        echo "Erro ao deletar o carro: " . $conn->error;
    }
} else {
    echo "ID do carro não fornecido.";
}
?>