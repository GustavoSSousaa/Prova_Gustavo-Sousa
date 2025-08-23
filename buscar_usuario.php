<?php
session_start();
require_once 'conexao.php';
require_once 'menudropdow.php';

if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 2) {
    echo "<script>alert('Acesso negado.');window.location.href='principal.php';</script>";
    exit();
}

// Inicializa a variável para evitar erros
$usuarios = [];

// Se o formulario foi enviado, busca o usuario pelo id ou nome

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['busca'])) {
    $busca = trim($_POST['busca']);

    // Verifica se a busca é numérica (ID) ou texto (nome)
    if (is_numeric($busca)) {
        $sql = "SELECT * FROM usuario WHERE id_usuario = :busca ORDER BY nome ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':busca', $busca, PDO::PARAM_INT); 
    } else {
        $sql = "SELECT * FROM usuario WHERE nome LIKE :busca_nome ORDER BY nome ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR); 
       
    }  
}else{
    $sql = "SELECT * FROM usuario ORDER BY nome ASC";
    $stmt = $pdo->prepare($sql);
}
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Usuário</title>
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

        form {
            margin-bottom: 20px;
        }

        input[type="text"] {
            padding: 6px 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            width: 200px;
        }

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
            background-color: #3498db;
            color: white;
            text-transform: uppercase;
            font-size: 14px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #dff9fb;
            transition: 0.3s;
        }

        td {
            color: #2c3e50;
        }

        a.action-btn {
            padding: 4px 8px;
            border-radius: 6px;
            text-decoration: none;
            color: white;
            font-size: 13px;
            margin: 0 2px;
        }

        a.edit-btn {
            background-color: #f39c12;
        }

        a.delete-btn {
            background-color: #e74c3c;
        }

        a.edit-btn:hover { background-color: #d68910; }
        a.delete-btn:hover { background-color: #c0392b; }

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
            background-color: #3498db;
            color: white;
        }

        a.back-btn:hover {
            background-color: #2980b9;
        }

    </style>
</head>
<body>
    <h2>Lista de Usuários</h2>

    <form method="POST" action="buscar_usuario.php">
        <label for="busca">Buscar por ID ou Nome:</label>
        <input type="text" id="busca" name="busca" required>
        <button type="submit">Buscar</button>
    </form>

    <?php if (!empty($usuarios)): ?>
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
                        <td><?=htmlspecialchars($usuario['nome']); ?></td>
                        <td><?=htmlspecialchars($usuario['email']); ?></td>
                        <td><?=htmlspecialchars($usuario['id_perfil']); ?></td>
                        <td>
                            <a href="alterar_usuario.php?id=<?=$usuario['id_usuario']; ?>" class="action-btn edit-btn">Alterar</a>
                            <a href="excluir_usuario.php?id=<?=$usuario['id_usuario']; ?>" class="action-btn delete-btn" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhum usuário encontrado.</p>
    <?php endif; ?>

    <a href="principal.php" class="back-btn">Voltar</a>

</body>
</html>