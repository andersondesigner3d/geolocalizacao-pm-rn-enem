<?php
// db.php
// Habilita a exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configurações do banco de dados
$db   = 'geolocalizacao';    // Nome do banco de dados
$user = 'root';             // Usuário do banco de dados
$pass = 'a26a1278';         // Senha do banco de dados
$charset = 'utf8mb4';       // Conjunto de caracteres

// Caminho para o socket do MySQL
$socket = '/var/lib/mysql/mysql.sock';

// Data Source Name (DSN) com o socket
$dsn = "mysql:unix_socket=$socket;dbname=$db;charset=$charset";

// Opções para o PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Modo de erro: exceções
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Modo de busca padrão: associativo
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Desativa emulação de prepared statements
];

try {
    // Cria uma instância do PDO
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Captura e exibe erros de conexão (em produção, considere logar em vez de exibir)
    echo 'Falha na conexão com o banco de dados: ' . $e->getMessage();
    exit;
}
?>
