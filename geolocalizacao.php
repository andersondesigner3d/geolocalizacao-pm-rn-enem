<?php
session_start(); 
// Verifica se o usuário está autenticado
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: index.php'); // Redireciona para a index se não estiver autenticado
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Sistema - Geolocalização</title>
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
        <h1 class="mb-4">Bem-vindo ao Meu Sistema de Geolocalização</h1>
        <p>Digite o endereço para receber as coordenadas geográficas. Utilização gratuita de 2500 vezes apenas por dia.</p>
        
        <!-- Exemplo de Card com Formulário -->
        <div class="card bg-secondary text-light mt-4 p-4">
            <div class="card-body">
                <h5 class="card-title">Obter Coordenadas</h5>
                <?php
                require 'vendor/autoload.php';

                use OpenCage\Geocoder\Geocoder;

                if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['address'])) {
                    $address = $_POST['address'];
                    $api_key = 'eee979b43c8647f29ea07fd33bb33af1'; // Substitua pela sua chave de API

                    try {
                        // Cria uma instância do Geocoder
                        $geocoder = new Geocoder($api_key);

                        // Opções de geocodificação
                        $options = [
                            'language' => 'pt', // Define o idioma para português
                            'pretty' => 1,      // Formata a resposta para melhor legibilidade (opcional)
                        ];

                        // Faz a geocodificação
                        $result = $geocoder->geocode($address, $options);

                        if ($result && $result['total_results'] > 0) {
                            $latitude = $result['results'][0]['geometry']['lat'];
                            $longitude = $result['results'][0]['geometry']['lng'];

                            echo "<div class='alert alert-success mt-3' role='alert'>";
                            echo "<p><strong>Endereço:</strong> " . htmlspecialchars($address) . "</p>";
                            echo "<p><strong>Latitude:</strong> {$latitude}</p>";
                            echo "<p><strong>Longitude:</strong> {$longitude}</p>";
                            echo "<p><strong>Mapa:</strong> <a target='_blank' href='https://www.google.com/maps/search/?api=1&query=$latitude,$longitude'>Link</a></p>";
                            echo "</div>";
                        } else {
                            echo "<div class='alert alert-danger mt-3' role='alert'>Não foi possível encontrar as coordenadas para o endereço fornecido.</div>";
                        }
                    } catch (Exception $e) {
                        echo "<div class='alert alert-danger mt-3' role='alert'>Ocorreu um erro: " . htmlspecialchars($e->getMessage()) . "</div>";
                    }
                }
                ?>

                <!-- Formulário HTML -->
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="address" class="form-label">Endereço:</label>
                        <input type="text" class="form-control" id="address" name="address" placeholder="Digite o endereço" required>
                    </div>
                    <button type="submit" class="btn btn-submit">Obter Coordenadas</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>