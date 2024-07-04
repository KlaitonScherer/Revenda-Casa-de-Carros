<?php

session_start();
if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();

}

$conn = new mysqli('localhost', 'root', '', 'casadecarros');

    if($conn->connect_error){
        die("Falha na conexão: " . $conn->connect_error);

    }

$sql = "SELECT * FROM carros";
$result = $conn->query($sql);

?>  

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Administração - Listagem de Carros</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2 class="mt-5">Listagem de Carros</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Ano</th>
                <th>Valor</th>
                <th>Quilometragem</th>
                <th>Foto</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['marca'] . "</td>";
                    echo "<td>" . $row['modelo'] . "</td>";
                    echo "<td>" . $row['ano'] . "</td>";
                    echo "<td>" . $row['valor'] . "</td>";
                    echo "<td>" . $row['quilometragem'] . "</td>";
                    echo '<td><img src="' . $row['foto'] . '" alt="Foto do carro" style="width: 100px;"></td>';
                    echo '<td>';
                    echo '<a href="edit_car.php?id=' . $row['id'] . '" class="btn btn-primary">Editar</a> ';
                    echo '<a href="delete_car.php?id=' . $row['id'] . '" class="btn btn-danger" onclick="return confirm(\'Tem certeza que deseja deletar este carro?\')">Deletar</a>';
                    echo '</td>';
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>Nenhum carro encontrado.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <a href="admin.php" class="btn btn-secondary mt-3">Voltar à Administração</a>
    <a href="logout.php" class="btn btn-secondary mt-3">Logout</a>
</div>
</body>
</html>
