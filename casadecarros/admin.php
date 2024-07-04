<?php

session_start();
    
    if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $marca = $_POST['marca'];
        $modelo = $_POST['modelo'];
        $ano = $_POST['ano'];
        $valor = $_POST['valor'];
        $quilometragem = $_POST['quilometragem'];

        //upload de fotos
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["foto"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Verificar se o arquivo de imagem é uma imagem real ou falsa
        $check = getimagesize($_FILES["foto"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                echo "Arquivo não é uma imagem.";
                $uploadOk = 0;
            }
        
            // Verificar o tamanho do arquivo
            if ($_FILES["foto"]["size"] > 500000) {
            echo "Desculpe, seu arquivo é muito grande.";
            $uploadOk = 0;
            }

            // Permitir apenas certos formatos de arquivo
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                echo "Desculpe, apenas arquivos JPG, JPEG e PNG são permitidos.";
                $uploadOk = 0;
            }
            if ($uploadOk == 0) {
                echo "Desculpe, seu arquivo não foi enviado.";
            } else {
                if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
        
            $conn = new mysqli('localhost', 'root', '', 'casadecarros');
        
            if($conn->connect_error) {
                die("Falha na conexão: " . $conn->connect_error);
            }
        $sql = "INSERT INTO carros (marca, modelo, ano, valor, quilometragem, foto) VALUES ('$marca', '$modelo', '$ano', '$valor', '$quilometragem', '$target_file')";
            
            if($conn->query($sql) === TRUE) {
                echo "Carro adicionado com sucesso.";
            } else {
                echo "Erro: " . $conn->error;
                   }
                } else {
        echo "Desculpe, houve um erro ao enviar seu arquivo.";
            }
            }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Admnistração - Casa de Carros</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Admnistração</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="marca">Marca:</label> 
                <input type="text" class="form-control" id="marca" name="marca" required>
            </div>

            <div class="form-group">
                <label for="modelo">Modelo:</label>
                <input type="text" class="form-control" id="modelo" name="modelo" required>
            </div>
            <div class="form-group">
                <label for="ano">Ano:</label>
                <input type="number" class="form-control" id="ano" name="ano" required>
            </div>
            <div class="form-group">
                <label for="valor">Valor:</label>
                <input type="number" step="0.01" class="form-control" id="valor" name="valor" required>
            </div>
            <div class="form-group">
                <label for="quilometragem">Kilometragem:</label>
                <input type="number" class="form-control" id="quilometragem" name="quilometragem" required>
            </div>
            <div class="form-group">
                <label for="foto">Foto do Carro:</label>
                <input type="file" class="form-control-file" id="foto" name="foto" required>
            </div>
            <button type="submit" class="btn btn-primary">Adicionar Carro</button>
        </form>
        <a href="admin_list.php" class="btn btn-secondary mt-3">Listar Carros</a>
        <a href="admin_clients.php" class="btn btn-secondary mt-3">Listar Clientes</a>
        <a href="logout.php" class="btn btn-secondary mt-3">Logout</a>
    </div>
</body>
</html>

    