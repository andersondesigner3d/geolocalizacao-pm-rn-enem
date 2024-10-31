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

$contador = $pdo->query("SELECT * FROM geolocalizacao.escala_servico LEFT JOIN geolocalizacao.locais ON geolocalizacao.escala_servico.local_emprego = geolocalizacao.locais.destinatario;");
$resultados = $contador->fetchAll(PDO::FETCH_ASSOC);
$totalLinhas = $contador->rowCount();


?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Locais de Trabalho dos Policiais</title>
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
        <h1 class="mb-4">Locais de Trabalho dos Policiais</h1>
        <hr>
        
        <!-- Exemplo de Card com Formulário de Upload CSV -->
        <div class="card bg-secondary text-light mt-4 p-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-dark" style="font-size:10px;">
                    <thead>
                            <tr>
                                <th></th>
                                <th>Regiao</th>
                                <th>OPM</th>
                                <th>Cidade</th>
                                <th>Area</th>
                                <th>Ordenação</th>
                                <th>Local de Emprego</th>
                                <th>Data</th>
                                <th>CPF</th>
                                <th>Verif</th>
                                <th>Post/Grad</th>
                                <th>Nome</th>
                                <th>Matricula</th>
                                <th>Banco</th>
                                <th>Agencia</th>
                                <th>Conta</th>
                                <th>Unidade</th>
                                <th>Subunidade</th>
                                <th>Telefone</th>
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
                                <td><?php echo $dados['regiao']?></td>
                                <td><?php echo $dados['opm']?></td>
                                <td><?php echo $dados['cidade']?></td>
                                <td><?php echo $dados['area']?></td>
                                <td><?php echo $dados['ordenacao']?></td>
                                <td><?php echo $dados['local_emprego']?></td>
                                <td><?php echo $dados['data_prova']?></td>
                                <td><?php echo $dados['cpf_mask']?></td>
                                <td><?php echo $dados['verif']?></td>
                                <td><?php echo $dados['post_grad']?></td>
                                <td><?php echo $dados['nome']?></td>
                                <td><?php echo $dados['matricula']?></td>
                                <td><?php echo $dados['banco']?></td>
                                <td><?php echo $dados['agencia']?></td>
                                <td><?php echo $dados['conta']?></td>
                                <td><?php echo $dados['unidade']?></td>
                                <td><?php echo $dados['subunidade']?></td>
                                <td><?php echo $dados['telefone']?></td>
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
                                <td>
                                    <?php if(!empty($dados['latitude'])): ?>
                                        <a target="_blank" href="https://www.google.com/maps/search/?api=1&query=<?php echo $dados['latitude']?>,<?php echo $dados['longitude']?>">Mapa</a>
                                    <?php else: ?>
                                        <a target="_blank" href="https://www.google.com/maps/search/?api=1&query=<?php echo str_replace(" ","_",$dados['local_emprego']).'_rio_grande_do_norte'?>">Mapa</a>
                                    <?php endif; ?></td>
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
            </div>
        </div>
    </div>
</body>
</html>