<?php

require_once "conexao.php";

$sql = "SELECT * FROM usuarios ORDER BY id DESC";
$resultado = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastro de Usuários</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

    <h1>Cadastro de Usuários</h1>

    <a href="cadastrar.php" class="btn-novo">
        + Cadastrar novo usuário
    </a>

    <div class="lista">

        <?php if ($resultado->num_rows > 0): ?>

            <?php while ($usuario = $resultado->fetch_assoc()): ?>

                <div class="card">

                    <h2>
                        <?= htmlspecialchars($usuario["nome"]) ?>
                    </h2>

                    <p>
                        <strong>E-mail:</strong>
                        <?= htmlspecialchars($usuario["email"]) ?>
                    </p>

                    <p>
                        <strong>Data de nascimento:</strong>
                        <?= htmlspecialchars($usuario["dataNascimento"]) ?>
                    </p>

                    <p>
                        <strong>Estado:</strong>
                        <?= htmlspecialchars($usuario["estado"]) ?>
                    </p>

                    <p>
                        <strong>Endereço:</strong>
                        <?= htmlspecialchars($usuario["endereco"]) ?>
                    </p>

                    <p>
                        <strong>Sexo:</strong>
                        <?= htmlspecialchars($usuario["sexo"]) ?>
                    </p>

                    <p>
                        <strong>Categorias:</strong>
                        <?= htmlspecialchars($usuario["categorias"]) ?>
                    </p>

                    <p>
                        <strong>Login:</strong>
                        <?= htmlspecialchars($usuario["login"]) ?>
                    </p>

                    <div class="acoes">

                        <a
                            href="editar.php?id=<?= $usuario["id"] ?>"
                            class="btn-editar">
                            Editar
                        </a>

                        <a
                            href="excluir.php?id=<?= $usuario["id"] ?>"
                            class="btn-excluir"
                            onclick="return confirm('Deseja realmente excluir este cadastro?');">
                            Excluir
                        </a>

                    </div>

                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <p class="vazio">
                Nenhum usuário cadastrado.
            </p>

        <?php endif; ?>

    </div>

</div>

</body>

</html>