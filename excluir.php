<?php

require_once "conexao.php";

if (!isset($_GET["id"])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET["id"]);

$sql = "DELETE FROM usuarios WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $id);

$stmt->execute();

$stmt->close();
$conn->close();

header("Location: index.php");
exit;

?>