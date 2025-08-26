<?php
    session_start();
    require_once 'conexao.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        $query = 'SELECT * FROM usuario WHERE email = :email';

        $stmt = $pdo -> prepare($query);
        $stmt -> bindParam(':email', $email);
        $stmt -> execute();

        $usuario = $stmt -> fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            // LOGIN BEM-SUCEDIDO E DEFINE VARIÁVEIS DE SESSÃO
            $_SESSION['usuario'] = $usuario['nome'];
            $_SESSION['perfil'] = $usuario['id_perfil'];
            $_SESSION['id_usuario'] = $usuario['id_usuario'];

            // VERIFICA SE A SENHA É TEMPORÁRIA
            if ($usuario['senha_temporaria']) {
                // REDIRECIONA PARA A TROCA DA SENHA TEMPORÁRIA
                header("Location: alterar_senha.php");
                exit();
            } else {
                // REDIRECIONA PARA A PÁGINA INICIAL
                header("Location: principal.php");
                exit();
            }
        } else {
            // LOGIN INVÁLIDO
            echo "<script> alert('E-mail ou senha incorretos!'); window.location.href='login.php'; </script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="stylesheet" href="styles.css?v=2">
</head>
<body>

    <form action="login.php" method="POST">
        <h2>Login</h2>
            <label for="email">E-mail:</label>
            <input type="email" name="email" id="email" required>

            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" required>

            <button type="submit">Entrar</button>

        <p><a href="recuperar_senha.php">Esqueci minha senha</a></p>
    </form>


</body>
</html>