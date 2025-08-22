<?php
session_start();
require_once 'conexao.php';
require_once 'menudropdow.php';

// 
if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 2) {
    echo "<script>alert('Acesso negado.');window.location.href='principal.php';</script>";
    exit();
}

$usuario = null;

// Se o formulario foi enviado, busca o usuario pelo id ou nome

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['busca_usuario'])) {
    $busca = trim($_POST['busca_usuario']);

    // Verifica se a busca é numérica (ID) ou texto (nome)
    if (is_numeric($busca)) {
        $sql = "SELECT * FROM usuario WHERE id_usuario = :busca";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':busca', $busca, PDO::PARAM_INT); 
    } else {
        $sql = "SELECT * FROM usuario WHERE nome LIKE :busca_nome";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR); 
    } 
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Se não encontrar o usuário, exibe um alerta
    if (!$usuario) {
        echo "<script>alert('Usuário não encontrado!');</script>";
    }
}
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar usuario</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Certifique-se de que o Javascript está sendo carregado com sucesso -->
    <script src="scripts.js"></script>
</head>
<body>
<h2>Alterar Usuários</h2>
    <!-- Formulário de busca -->
    <form method="POST" action="alterar_usuario.php">
        <label for="busca_usuario">Buscar por ID ou Nome:</label>
        <input type="text" id="busca_usuario" name="busca_usuario" required onkeyup="BuscarSugestoes()">

        <div id="sugestoes"></div>

        <button type="submit">Buscar</button>
        </form>

        <?php if ($usuario): ?>
            <form method="POST" action="processa_alteracao_usuario.php">
                <input type="hidden" name="id_usuario" value="<?=htmlspecialchars($usuario['id_usuario']); ?>">

                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" value="<?=htmlspecialchars($usuario['nome']); ?>" required onkeypress ="mascara(this, nome)">

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?=htmlspecialchars($usuario['email']); ?>" required>

                <label for="id_perfil">Perfil:</label>
                <select id="id_perfil" name="id_perfil">
                    <option value="1" <?=$usuario['id_perfil'] == 1 ? 'selected' : ''; ?>>Administrador</option>
                    <option value="2" <?=$usuario['id_perfil'] == 2 ? 'selected' : ''; ?>>Secretaria</option>
                    <option value="3" <?=$usuario['id_perfil'] == 3 ? 'selected' : ''; ?>>Almoxarife</option>
                    <option value="4" <?=$usuario['id_perfil'] == 4 ? 'selected' : ''; ?>>Cliente</option>
                </select>
                <!-- Se o usuario logado for ADM, Exibir opção alterar senha -->
                 <?php if ($_SESSION['perfil'] == 1): ?>
                <label for="nova_senha">Nova Senha:</label>
                <input type="password" id="nova_senha" name="nova_senha">
                <?php endif; ?>

                <button type="submit">Alterar</button>
                <button type="reset">Cancelar</button>
                </form>
        <?php endif; ?>
        <a href="principal.php">Voltar</a>
</div>
    
</body>
</html>