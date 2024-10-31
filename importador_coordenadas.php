<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está autenticado
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: index.php'); // Redireciona para a index se não estiver autenticado
    exit;
}
// Inclui o arquivo de conexão com o banco de dados
require 'db.php';


$resposta = "";
//===========================
if (isset($_POST["apagar"])) {    
    if ($pdo->query("DELETE FROM locais")) {
        $resposta = "Todos os registros apagados com sucesso!";
    } else {
        $resposta = "Erro ao tentar apagar os registros.";
    }
}


if (isset($_POST["submit"])) {
    if (isset($_FILES["csv_file"]) && $_FILES["csv_file"]["error"] == 0) {
      
        $filename = $_FILES["csv_file"]["tmp_name"];
        $fileType = mime_content_type($filename);
        $allowedTypes = ['text/plain', 'text/csv', 'application/vnd.ms-excel', 'text/comma-separated-values'];
        
        // Verifica se o arquivo é realmente um CSV
        if (in_array($fileType, $allowedTypes)) {
          
            if (($handle = fopen($filename, "r")) !== FALSE) {
              
                // Inicia a contagem de linhas importadas
                $rowCount = 0;
                $errorCount = 0;
                $errors = [];

                
                //print("asas");exit;

               // Prepara a query de inserção com placeholders
               $stmt = $pdo->prepare("INSERT INTO locais 
               (distribuidora,codigo_rota,sequencia,cidade,codigo_destinatario,destinatario,logradouro,bairro,inscritos,latitude,longitude) 
               VALUES 
               (:distribuidora,:codigo_rota,:sequencia,:cidade,:codigo_destinatario,:destinatario,:logradouro,:bairro,:inscritos,:latitude,:longitude)");
                
                // Assume que a primeira linha contém os cabeçalhos e ignora
                $header = fgetcsv($handle, 1000, ",");

                // Verifica se o cabeçalho corresponde às colunas esperadas
                $expectedHeader = ['distribuidora','codigo_rota','sequencia','cidade','codigo_destinatario','destinatario','logradouro','bairro','inscritos','latitude','longitude'];
                // Remover 'id' se for auto_increment e não estiver no CSV
                // Supondo que o CSV não possui a coluna 'id', ajustar o expectedHeader
                // Ajustar conforme necessário
                // Se o CSV inclui 'id', pode ser necessário ajustá-lo ou ignorar.

                // Opcional: Verificar se os cabeçalhos correspondem
                // Uncomment the following block if you want to enforce header validation
                /*
                $headerLower = array_map('strtolower', $header);
                $expectedLower = array_map('strtolower', $expectedHeader);
                if ($headerLower !== $expectedLower) {
                    echo "<div class='alert alert-danger mt-3' role='alert'>Os cabeçalhos do CSV não correspondem às colunas esperadas.</div>";
                    fclose($handle);
                    exit;
                }
                */

                // Loop através de cada linha do CSV
                
                
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

                    // Mapear os dados do CSV para as colunas da tabela
                    // Ajuste os índices conforme a ordem das colunas no CSV
                    // Supondo que o CSV está na ordem:
                    // distribuidora,codigo_rota,sequencia,cidade,codigo_destinatario,destinatario,logradouro,bairro,inscritos,latitude,longitude

                    // Verifica se o número de colunas está correto
                    if (count($data) < 11) {
                        $errorCount++;
                        $errors[] = "Linha " . ($rowCount + 2) . ": Número de colunas incorreto.";
                        continue;
                    }

                    // Extrair e sanitizar os dados
                    $distribuidora = htmlspecialchars(trim($data[0]));
                    $codigo_rota = htmlspecialchars(trim($data[1]));
                    $sequencia = htmlspecialchars(trim($data[2]));
                    $cidade = htmlspecialchars(trim($data[3]));
                    $codigo_destinatario = htmlspecialchars(trim($data[4]));
                    $destinatario = htmlspecialchars(trim($data[5]));
                    $logradouro = htmlspecialchars(trim($data[6]));
                    $bairro = htmlspecialchars(trim($data[7]));
                    $inscritos = htmlspecialchars(trim($data[8]));
                    $latitude = htmlspecialchars(trim($data[9]));
                    $longitude = htmlspecialchars(trim($data[10]));

                    // Bind os parâmetros e execute a inserção
                    try {
                        $stmt->execute([
                            ':distribuidora' => $distribuidora,
                            ':codigo_rota' => $codigo_rota,
                            ':sequencia' => $sequencia,
                            ':cidade' => $cidade,
                            ':codigo_destinatario' => $codigo_destinatario,
                            ':destinatario' => $destinatario,
                            ':logradouro' => $logradouro,
                            ':bairro' => $bairro,
                            ':inscritos' => $inscritos,
                            ':latitude' => $latitude,
                            ':longitude' => $longitude,
                        ]);
                        $rowCount++;
                        
                    } catch (PDOException $e) {
                        $errorCount++;
                        $errors[] = "Linha " . ($rowCount + 2) . ": " . $e->getMessage();
                    }
                }

                fclose($handle);

                // Exibe o resultado da importação
                echo "<div class='alert alert-success mt-3' role='alert'>Importação concluída! $rowCount linhas inseridas com sucesso.</div>";
                if ($errorCount > 0) {
                    echo "<div class='alert alert-warning mt-3' role='alert'>$errorCount erros ocorreram durante a importação:</div>";
                    echo "<ul class='list-group'>";
                    foreach ($errors as $error) {
                        echo "<li class='list-group-item list-group-item-warning'>$error</li>";
                    }
                    echo "</ul>";
                }

            } else {
                echo "<div class='alert alert-danger mt-3' role='alert'>Erro ao abrir o arquivo.</div>";
            }
        } else {
            echo "<div class='alert alert-danger mt-3' role='alert'>Por favor, envie um arquivo CSV válido.</div>";
        }
    } else {
        echo "<div class='alert alert-danger mt-3' role='alert'>Nenhum arquivo foi enviado ou ocorreu um erro no upload.</div>";
    }
}

$contador = $pdo->query("SELECT * from locais");
$resultados = $contador->fetchAll(PDO::FETCH_ASSOC);
$totalLinhas = $contador->rowCount();


?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Sistema - Importador de Locais do ENEM</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <style>
        /* Customização Adicional para o Tema Escuro */
        body {
            background-color: #121212; /* Fundo escuro */
            color: #ffffff; /* Texto claro */
        }
        /* Ajustes para outros componentes */
        .card {
            background-color: #1e1e1e; /* Fundo do card ligeiramente mais claro */
            border: none; /* Remove bordas padrão */
        }
        .btn-primary {
            /* Mantém o botão primário com cores padrão */
        }
        /* Estilização do formulário */
        .form-control {
            background-color: #2c2c2c;
            color: #ffffff;
            border: 1px solid #444;
        }
        .form-control:focus {
            background-color: #2c2c2c;
            color: #ffffff;
            border-color: #0d6efd;
            box-shadow: none;
        }
        .btn-submit {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        .btn-submit:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }
        /* Estilização das Tabelas */
        table {
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            text-align: left;
            padding: 8px;
        }
        thead {
            background-color: #0d6efd;
            color: #ffffff;
        }
        tbody tr:nth-child(odd) {
            background-color: #2c2c2c;
        }
        tbody tr:nth-child(even) {
            background-color: #1e1e1e;
        }
        a {
            color: #0d6efd;
        }
        a:hover {
            color: #0a58ca;
        }
    </style>
</head>
<body class="bg-dark text-light">
<?php require 'navbar.php'; ?>
    
    <!-- Container Principal -->
    <div class="container mt-4">
        <h1 class="mb-4">Importador de Locais do ENEM</h1>
        <p>Use esta planilha padrão: <a href="/geolocalizacao/PLANILHA_LOCAIS_PADRAO.csv">baixar planilha</a></p>
        <hr>
        
        <!-- Exemplo de Card com Formulário de Upload CSV -->
        <div class="card bg-secondary text-light mt-4 p-4">
            <div class="card-body">
                <?php if(!empty($resposta)): ?>
                <div class="alert alert-success">
                    <?php echo $resposta; ?>
                </div>
                <?php endif; ?>
                <h5 class="card-title">Importar Arquivo CSV</h5>
                <!-- Formulário HTML -->
                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="csv_file" class="form-label">Selecione o arquivo CSV:</label>
                        <input type="file" class="form-control" name="csv_file" id="csv_file" accept=".csv" required>
                    </div>
                    <button type="submit" name="submit" class="btn btn-submit">Enviar</button>
                </form>
                <?php if($totalLinhas>0): ?>
                <hr>
                <p> <b><?php echo $totalLinhas; ?></b> cadastrados no total.</p>
                <div class="table-responsive">
                    <table class="table table-hover table-dark" style="font-size:10px;">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Distribuidora</th>
                                <th>Cód. da Rota</th>
                                <th>Sequência</th>
                                <th>Cidade</th>
                                <th>Cód Destinatário</th>
                                <th>Destinatário</th>
                                <th>Logradouro</th>
                                <th>Bairro</th>
                                <th>Inscritos</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Mapa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $linha = 1; ?>
                            <?php foreach($resultados as $dados): ?>
                            <tr>
                                <td><?php echo $linha; ?></td>
                                <td><?php echo $dados['distribuidora']?></td>
                                <td><?php echo $dados['codigo_rota']?></td>
                                <td><?php echo $dados['sequencia']?></td>
                                <td><?php echo $dados['cidade']?></td>
                                <td><?php echo $dados['codigo_destinatario']?></td>
                                <td><?php echo $dados['destinatario']?></td>
                                <td><?php echo $dados['logradouro']?></td>
                                <td><?php echo $dados['bairro']?></td>
                                <td><?php echo $dados['inscritos']?></td>
                                <td><?php echo $dados['latitude']?></td>
                                <td><?php echo $dados['longitude']?></td>
                                <td><a target="_blank" href="https://www.google.com/maps/search/?api=1&query=<?php echo $dados['latitude']?>,<?php echo $dados['longitude']?>">Mapa</a></td>

                            </tr>
                            <?php $linha ++; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <hr>
                <form action="" method="post">
                <input type="hidden" name="apagar">
                <button type="submit" class="btn btn-danger">Apagar Cadastros</button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>