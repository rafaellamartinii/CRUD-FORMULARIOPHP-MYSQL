<?php

require_once "conexao.php";

// Verifica se recebeu o ID
if (!isset($_GET["id"])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET["id"]);

// Busca o usuário pelo ID
$sql = "SELECT * FROM usuarios WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();

// Se não encontrar o usuário, volta para a página inicial
if ($resultado->num_rows == 0) {
    header("Location: index.php");
    exit;
}

$usuario = $resultado->fetch_assoc();


// ===============================
// ATUALIZAÇÃO DO CADASTRO
// ===============================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $dataNascimento = $_POST["dataNascimento"];
    $estado = $_POST["estado"];
    $endereco = trim($_POST["endereco"]);
    $sexo = $_POST["sexo"];
    $login = trim($_POST["login"]);

    // Verifica as categorias selecionadas
    $categorias = isset($_POST["categorias"])
        ? implode(", ", $_POST["categorias"])
        : "";


    // ===============================
    // SE DIGITAR NOVA SENHA
    // ===============================

    if (!empty($_POST["senha"])) {

        $senhaHash = password_hash(
            $_POST["senha"],
            PASSWORD_DEFAULT
        );

        $sql = "UPDATE usuarios SET
                    nome = ?,
                    email = ?,
                    dataNascimento = ?,
                    estado = ?,
                    endereco = ?,
                    sexo = ?,
                    categorias = ?,
                    login = ?,
                    senha = ?
                WHERE id = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "sssssssssi",
            $nome,
            $email,
            $dataNascimento,
            $estado,
            $endereco,
            $sexo,
            $categorias,
            $login,
            $senhaHash,
            $id
        );

    } else {

        // ===============================
        // SE NÃO DIGITAR NOVA SENHA
        // ===============================

        $sql = "UPDATE usuarios SET
                    nome = ?,
                    email = ?,
                    dataNascimento = ?,
                    estado = ?,
                    endereco = ?,
                    sexo = ?,
                    categorias = ?,
                    login = ?
                WHERE id = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "ssssssssi",
            $nome,
            $email,
            $dataNascimento,
            $estado,
            $endereco,
            $sexo,
            $categorias,
            $login,
            $id
        );
    }


    // Executa a atualização
    if ($stmt->execute()) {

        header("Location: index.php");
        exit;

    } else {

        $erro = "Erro ao atualizar o cadastro: " . $stmt->error;
    }
}


// Converte as categorias salvas no banco
// para um array
$categoriasSelecionadas = !empty($usuario["categorias"])
    ? array_map("trim", explode(",", $usuario["categorias"]))
    : [];

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Editar usuário</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

    <h1>Editar usuário</h1>


    <?php if (isset($erro)): ?>

        <div class="erro">
            <?= htmlspecialchars($erro) ?>
        </div>

    <?php endif; ?>


    <form method="POST" action="">


        <!-- Nome -->

        <div class="campo">

            <label for="nome">
                Nome completo:
            </label>

            <input
                type="text"
                id="nome"
                name="nome"
                value="<?= htmlspecialchars($usuario["nome"]) ?>"
                required
            >

        </div>


        <!-- E-mail -->

        <div class="campo">

            <label for="email">
                E-mail:
            </label>

            <input
                type="email"
                id="email"
                name="email"
                value="<?= htmlspecialchars($usuario["email"]) ?>"
                required
            >

        </div>


        <!-- Data de nascimento -->

        <div class="campo">

            <label for="dataNascimento">
                Data de nascimento:
            </label>

            <input
                type="date"
                id="dataNascimento"
                name="dataNascimento"
                value="<?= htmlspecialchars($usuario["dataNascimento"]) ?>"
                required
            >

        </div>


        <!-- Estado -->

        <div class="campo">

            <label for="estado">
                Estado:
            </label>

            <select
                id="estado"
                name="estado"
                required
            >

                <option value="">
                    Selecione
                </option>

                <option value="AC"
                    <?= $usuario["estado"] == "AC" ? "selected" : "" ?>>
                    Acre
                </option>

                <option value="AL"
                    <?= $usuario["estado"] == "AL" ? "selected" : "" ?>>
                    Alagoas
                </option>

                <option value="AP"
                    <?= $usuario["estado"] == "AP" ? "selected" : "" ?>>
                    Amapá
                </option>

                <option value="AM"
                    <?= $usuario["estado"] == "AM" ? "selected" : "" ?>>
                    Amazonas
                </option>

                <option value="BA"
                    <?= $usuario["estado"] == "BA" ? "selected" : "" ?>>
                    Bahia
                </option>

                <option value="CE"
                    <?= $usuario["estado"] == "CE" ? "selected" : "" ?>>
                    Ceará
                </option>

                <option value="DF"
                    <?= $usuario["estado"] == "DF" ? "selected" : "" ?>>
                    Distrito Federal
                </option>

                <option value="ES"
                    <?= $usuario["estado"] == "ES" ? "selected" : "" ?>>
                    Espírito Santo
                </option>

                <option value="GO"
                    <?= $usuario["estado"] == "GO" ? "selected" : "" ?>>
                    Goiás
                </option>

                <option value="MA"
                    <?= $usuario["estado"] == "MA" ? "selected" : "" ?>>
                    Maranhão
                </option>

                <option value="MT"
                    <?= $usuario["estado"] == "MT" ? "selected" : "" ?>>
                    Mato Grosso
                </option>

                <option value="MS"
                    <?= $usuario["estado"] == "MS" ? "selected" : "" ?>>
                    Mato Grosso do Sul
                </option>

                <option value="MG"
                    <?= $usuario["estado"] == "MG" ? "selected" : "" ?>>
                    Minas Gerais
                </option>

                <option value="PA"
                    <?= $usuario["estado"] == "PA" ? "selected" : "" ?>>
                    Pará
                </option>

                <option value="PB"
                    <?= $usuario["estado"] == "PB" ? "selected" : "" ?>>
                    Paraíba
                </option>

                <option value="PR"
                    <?= $usuario["estado"] == "PR" ? "selected" : "" ?>>
                    Paraná
                </option>

                <option value="PE"
                    <?= $usuario["estado"] == "PE" ? "selected" : "" ?>>
                    Pernambuco
                </option>

                <option value="PI"
                    <?= $usuario["estado"] == "PI" ? "selected" : "" ?>>
                    Piauí
                </option>

                <option value="RJ"
                    <?= $usuario["estado"] == "RJ" ? "selected" : "" ?>>
                    Rio de Janeiro
                </option>

                <option value="RN"
                    <?= $usuario["estado"] == "RN" ? "selected" : "" ?>>
                    Rio Grande do Norte
                </option>

                <option value="RS"
                    <?= $usuario["estado"] == "RS" ? "selected" : "" ?>>
                    Rio Grande do Sul
                </option>

                <option value="RO"
                    <?= $usuario["estado"] == "RO" ? "selected" : "" ?>>
                    Rondônia
                </option>

                <option value="RR"
                    <?= $usuario["estado"] == "RR" ? "selected" : "" ?>>
                    Roraima
                </option>

                <option value="SC"
                    <?= $usuario["estado"] == "SC" ? "selected" : "" ?>>
                    Santa Catarina
                </option>

                <option value="SP"
                    <?= $usuario["estado"] == "SP" ? "selected" : "" ?>>
                    São Paulo
                </option>

                <option value="SE"
                    <?= $usuario["estado"] == "SE" ? "selected" : "" ?>>
                    Sergipe
                </option>

                <option value="TO"
                    <?= $usuario["estado"] == "TO" ? "selected" : "" ?>>
                    Tocantins
                </option>

            </select>

        </div>


        <!-- Endereço -->

        <div class="campo">

            <label for="endereco">
                Endereço:
            </label>

            <input
                type="text"
                id="endereco"
                name="endereco"
                value="<?= htmlspecialchars($usuario["endereco"]) ?>"
                required
            >

        </div>


        <!-- Sexo -->

        <div class="campo">

            <label>
                Sexo:
            </label>

            <div class="radio-group">

                <label>

                    <input
                        type="radio"
                        name="sexo"
                        value="Masculino"
                        <?= $usuario["sexo"] == "Masculino" ? "checked" : "" ?>
                        required
                    >

                    Masculino

                </label>


                <label>

                    <input
                        type="radio"
                        name="sexo"
                        value="Feminino"
                        <?= $usuario["sexo"] == "Feminino" ? "checked" : "" ?>
                    >

                    Feminino

                </label>

            </div>

        </div>


        <!-- Categorias -->

        <div class="campo">

            <label>
                Categorias de interesse:
            </label>

            <div class="checkbox-group">


                <label>

                    <input
                        type="checkbox"
                        name="categorias[]"
                        value="Praia"
                        <?= in_array("Praia", $categoriasSelecionadas) ? "checked" : "" ?>
                    >

                    Praia

                </label>


                <label>

                    <input
                        type="checkbox"
                        name="categorias[]"
                        value="Campo"
                        <?= in_array("Campo", $categoriasSelecionadas) ? "checked" : "" ?>
                    >

                    Campo

                </label>


                <label>

                    <input
                        type="checkbox"
                        name="categorias[]"
                        value="Nacionais"
                        <?= in_array("Nacionais", $categoriasSelecionadas) ? "checked" : "" ?>
                    >

                    Nacionais

                </label>


                <label>

                    <input
                        type="checkbox"
                        name="categorias[]"
                        value="Internacionais"
                        <?= in_array("Internacionais", $categoriasSelecionadas) ? "checked" : "" ?>
                    >

                    Internacionais

                </label>


                <label>

                    <input
                        type="checkbox"
                        name="categorias[]"
                        value="Serra"
                        <?= in_array("Serra", $categoriasSelecionadas) ? "checked" : "" ?>
                    >

                    Serra

                </label>


                <label>

                    <input
                        type="checkbox"
                        name="categorias[]"
                        value="Cidade"
                        <?= in_array("Cidade", $categoriasSelecionadas) ? "checked" : "" ?>
                    >

                    Cidade

                </label>


            </div>

        </div>


        <!-- Login -->

        <div class="campo">

            <label for="login">
                Login:
            </label>

            <input
                type="text"
                id="login"
                name="login"
                value="<?= htmlspecialchars($usuario["login"]) ?>"
                required
            >

        </div>


        <!-- Senha -->

        <div class="campo">

            <label for="senha">
                Nova senha:
            </label>

            <input
                type="password"
                id="senha"
                name="senha"
                minlength="6"
            >

            <small>
                Deixe em branco para manter a senha atual.
            </small>

        </div>


        <!-- Botões -->

        <div class="botoes">

            <a
                href="index.php"
                class="btn-limpar"
            >
                Cancelar
            </a>

            <button
                type="submit"
                class="btn-salvar"
            >
                Atualizar
            </button>

        </div>


    </form>

</div>

</body>

</html>