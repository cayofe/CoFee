<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "rms";
// Tentar estabelecer a conexão com o banco de dados

$mysqli = new mysqli($servername, $username, $password, $database);

// Verificar se a conexão foi estabelecida com sucesso
if ($mysqli->connect_errno) {
    die("Falha na conexão com o banco de dados: " . $mysqli->connect_error);
}

?>
