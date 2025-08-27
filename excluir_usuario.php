<?php
session_start();
require_once 'conexao.php';
require_once 'menudropdow.php';

// verifica se o usuário tem permissão para acessar a página
if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 2) {
    echo "<script>alert('Acesso negado.');window.location.href='principal.php';</script>";
    exit();
}
// Inicializa a variável do usuário 
$usuario = null;

// Busca todos os usuarios cadastrados em ordem alfabética
$sql = "SELECT * FROM usuario ORDER BY nome ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Se um id for enviado via GET, busca o usuário correspondente
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_usuario = $_GET['id'];

    // exclui o usuario do banco de dados
    $sql = "DELETE FROM usuario WHERE id_usuario = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Se não encontrar o usuário, exibe um alerta
    if ($stmt->execute()) {
        echo "<script>alert('Usuário excluído com sucesso!');window.location.href='excluir_usuario.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir usuário.');</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f6fa;
            margin: 0;
            padding: 20px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #2c3e50;
        }

        table {
            margin: 0 auto;
            border-collapse: collapse;
            width: 90%;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px 15px;
            text-align: center;
        }

        th {
            background-color: #3498db; /* mesma cor da primeira tabela */
            color: white;
            text-transform: uppercase;
            font-size: 14px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #dff9fb; /* hover azul claro */
            transition: 0.3s;
        }

        td {
            color: #2c3e50;
        }

        a.delete-btn {
            padding: 5px 10px;
            border-radius: 6px;
            text-decoration: none;
            color: white;
            background-color: #e74c3c; /* vermelho para excluir */
            font-size: 13px;
            margin: 0 2px;
        }

        a.delete-btn:hover {
            background-color: #c0392b;
        }

        p {
            color: #555;
            font-size: 16px;
        }

        a.back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            background-color: #3498db; /* azul da primeira tabela */
            color: white;
        }

        a.back-btn:hover {
            background-color: #2980b9;
        }

    </style>
</head>
<body>
    <h2>Excluir Usuários</h2>
    <?php if(!empty($usuarios)): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Perfil</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?=htmlspecialchars($usuario['id_usuario']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['nome']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['id_perfil'] == 1 ? 'Administrador' : 'Usuário'); ?></td>
                        <td>
                            <a href="excluir_usuario.php?id=<?php echo $usuario['id_usuario']; ?>" class="delete-btn" onclick="return confirm('Tem certeza que deseja excluir este usuário?');">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p>Nenhum usuário encontrado.</p>
    <?php endif; ?>
        </br>
    <a href="principal.php" class="back-btn">Voltar</a>
    
</body>
</html>