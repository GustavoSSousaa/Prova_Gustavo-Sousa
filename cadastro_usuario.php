<?php
session_start();
require_once 'conexao.php';
require_once 'menudropdow.php';


// verifica se o usuario tem permissao 
if($_SESSION['perfil']!= 1) {
    echo "Acesso negado. ";
    exit();
}

if ($_SERVER["REQUEST_METHOD"]=="POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $id_perfil = $_POST['id_perfil'];
    $sql = "INSERT INTO usuario (nome, email, senha, id_perfil) VALUES (:nome, :email, :senha, :id_perfil)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha);
    $stmt->bindParam(':id_perfil', $id_perfil);

    if($stmt->execute()) {
        echo "<script>alert('Usuário cadastrado com sucesso!');</script>";
    }else{
        echo "<script>alert('Erro ao cadastrar usuário.');</script>";
    }
};
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
    <script src="mascaras.js"></script>
    <style>
    button {
            padding: 6px 12px;
            border-radius: 6px;
            border: none;
            background-color: #3498db;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #2980b9;
        }
        a.back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            background-color: #3498db;
            color: white;
        }

        a.back-btn:hover {
            background-color: #2980b9;
        }
        
        </style>
</head>

<body>
    <h2>Cadastro de Usuário</h2>
    <form method="POST" action="cadastro_usuario.php">
        <label for="nome">Nome:</label>
        <input type="text" id="nome2" name="nome2" required onkeypress ="mascara(this, nome)">
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>
        
        <label for="id_perfil">Perfil:</label>
        <select id="id_perfil" name="id_perfil">
            <option value="1">Administrador</option>
            <option value="2">Usuário</option>
            <option value="3">Almoxarife</option>
            <option value="4">Cliente</option>
        </select>
        
        <button type="submit">Cadastrar</button>
        <button type="reset">Cancelar</button>
    </form>
    <a href="principal.php" class="back-btn">Voltar</a>
    
</body>
</html>