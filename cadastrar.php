<?php

require_once "conexao.php";

$erroNome = "";
$erroBanco = "";

$nome = "";
$email = "";
$dataNascimento = "";
$estado = "";
$endereco = "";
$sexo = "";
$login = "";
$senha = "";
$categorias = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $dataNascimento = $_POST["dataNascimento"];
    $estado = $_POST["estado"];
    $endereco = trim($_POST["endereco"]);
    $sexo = $_POST["sexo"];
    $login = trim($_POST["login"]);
    $senha = $_POST["senha"];

    if (isset($_POST["categorias"])) {
        $categorias = $_POST["categorias"];
    }

    // Verifica se foi informado nome e sobrenome
    $partesNome = preg_split('/\s+/', $nome);

    if (count($partesNome) < 2) {

        $erroNome = "Por favor, preencha o nome completo (nome e sobrenome).";

    } else {

        // Transforma as categorias em texto
        $categoriasTexto = implode(", ", $categorias);

        // Protege a senha
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        // Insere os dados no banco
        $sql = "INSERT INTO usuarios
                (nome, email, dataNascimento, estado, endereco, sexo, categorias, login, senha)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        if ($stmt) {

            $stmt->bind_param(
                "sssssssss",
                $nome,
                $email,
                $dataNascimento,
                $estado,
                $endereco,
                $sexo,
                $categoriasTexto,
                $login,
                $senhaHash
            );

            if ($stmt->execute()) {

                // Cadastro realizado com sucesso
                header("Location: index.php");
                exit;

            } else {

                $erroBanco = "Erro ao cadastrar: " . $stmt->error;
            }

            $stmt->close();

        } else {

            $erroBanco = "Erro na preparação da consulta: " . $conn->error;
        }
    }
}

?>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastro de novo usuário</title>

    <!-- Arquivo CSS externo -->
    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

    <h1>Cadastro de novo usuário</h1>

    <!-- Mensagem de erro -->

    <?php if ($erroNome != ""): ?>

        <div class="erro">
            <?php echo htmlspecialchars($erroNome); ?>
        </div>

    <?php endif; ?>


    <!-- FORMULÁRIO -->

    <form method="POST" action="cadastrar.php">

        <!-- Nome -->

        <div class="campo">

            <label for="nome">
                Nome completo:
            </label>

            <input
                type="text"
                id="nome"
                name="nome"
                value="<?php echo htmlspecialchars($nome); ?>"
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
                value="<?php echo htmlspecialchars($email); ?>"
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
                value="<?php echo htmlspecialchars($dataNascimento); ?>"
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


                <option value="AC">Acre</option>
                <option value="AL">Alagoas</option>
                <option value="AP">Amapá</option>
                <option value="AM">Amazonas</option>
                <option value="BA">Bahia</option>
                <option value="CE">Ceará</option>
                <option value="DF">Distrito Federal</option>
                <option value="ES">Espírito Santo</option>
                <option value="GO">Goiás</option>
                <option value="MA">Maranhão</option>
                <option value="MT">Mato Grosso</option>
                <option value="MS">Mato Grosso do Sul</option>
                <option value="MG">Minas Gerais</option>
                <option value="PA">Pará</option>
                <option value="PB">Paraíba</option>
                <option value="PR">Paraná</option>
                <option value="PE">Pernambuco</option>
                <option value="PI">Piauí</option>
                <option value="RJ">Rio de Janeiro</option>
                <option value="RN">Rio Grande do Norte</option>
                <option value="RS">Rio Grande do Sul</option>
                <option value="RO">Rondônia</option>
                <option value="RR">Roraima</option>
                <option value="SC">Santa Catarina</option>
                <option value="SP">São Paulo</option>
                <option value="SE">Sergipe</option>
                <option value="TO">Tocantins</option>

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
                value="<?php echo htmlspecialchars($endereco); ?>"
                required
            >

        </div>


        <!-- Sexo -->

        <div class="campo">

            <label>Sexo:</label>

            <div class="radio-group">

                <label>

                    <input
                        type="radio"
                        name="sexo"
                        value="Masculino"
                        <?php if ($sexo == "Masculino") echo "checked"; ?>
                        required
                    >

                    Masculino

                </label>


                <label>

                    <input
                        type="radio"
                        name="sexo"
                        value="Feminino"
                        <?php if ($sexo == "Feminino") echo "checked"; ?>
                    >

                    Feminino

                </label>

            </div>

        </div>


        <!-- Categorias -->

        <div class="campo">

            <label>
                Categorias de interesse (marque mais de uma):
            </label>

            <div class="checkbox-group">

                <label>
                    <input
                        type="checkbox"
                        name="categorias[]"
                        value="Praia"
                        <?php if (in_array("Praia", $categorias)) echo "checked"; ?>
                    >
                    Praia
                </label>

                <label>
                    <input
                        type="checkbox"
                        name="categorias[]"
                        value="Campo"
                        <?php if (in_array("Campo", $categorias)) echo "checked"; ?>
                    >
                    Campo
                </label>

                <label>
                    <input
                        type="checkbox"
                        name="categorias[]"
                        value="Nacionais"
                        <?php if (in_array("Nacionais", $categorias)) echo "checked"; ?>
                    >
                    Nacionais
                </label>

                <label>
                    <input
                        type="checkbox"
                        name="categorias[]"
                        value="Internacionais"
                        <?php if (in_array("Internacionais", $categorias)) echo "checked"; ?>
                    >
                    Internacionais
                </label>

                <label>
                    <input
                        type="checkbox"
                        name="categorias[]"
                        value="Serra"
                        <?php if (in_array("Serra", $categorias)) echo "checked"; ?>
                    >
                    Serra
                </label>

                <label>
                    <input
                        type="checkbox"
                        name="categorias[]"
                        value="Cidade"
                        <?php if (in_array("Cidade", $categorias)) echo "checked"; ?>
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
                value="<?php echo htmlspecialchars($login); ?>"
                required
            >

        </div>


        <!-- Senha -->

        <div class="campo">

            <label for="senha">
                Senha:
            </label>

            <input
                type="password"
                id="senha"
                name="senha"
                minlength="6"
                required
            >

        </div>


        <!-- Botões -->

        <div class="botoes">

            <button
                type="reset"
                class="btn-limpar"
            >
                Limpar
            </button>

            <button
                type="submit"
                class="btn-salvar"
            >
                Salvar
            </button>

        </div>

    </form>


</div>

</body>
</html>