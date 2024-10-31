<?php
session_start(); // Inicia a sessão

if(isset($_GET['sair'])){
    // Remove todas as variáveis da sessão
    session_unset();

    // Destroi a sessão
    session_destroy();

    // Redireciona o usuário para a página inicial (index.php)
    header('Location: index.php');
    exit;
}

// Defina a senha correta
$senhaCorreta = 'dticpmrn';
$erro = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se a senha inserida está correta
    if (isset($_POST['senha']) && $_POST['senha'] === $senhaCorreta) {
        $_SESSION['autenticado'] = true; // Cria a sessão de autenticação
        header('Location: geolocalizacao.php'); // Redireciona para uma página de destino
        exit;
    } else {
        $erro = "Senha incorreta. Tente novamente.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Sistema - Tema Escuro com Navbar Azul</title>
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
        /* Opcional: Ajustes adicionais para outros componentes */
        .card {
            background-color: #1e1e1e; /* Fundo do card ligeiramente mais claro */
            border: none; /* Remove bordas padrão */
        }
        .btn-primary {
            /* Mantém o botão primário com cores padrão */
        }
    </style>
</head>
<body class="bg-dark text-light">
    <?php require 'navbar.php'; ?>
    
    <!-- Container Principal -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12" style="text-align: center;margin-bottom:20px">
                <h1 class="mb-4">Sistema de Geolocalização ENEM - PM RN</h1>
                <hr>
            </div>
        </div>        
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4" style="text-align: center;margin-bottom:20px">
                        <?php if(!empty($erro)): ?>
                        <div class="alert alert-danger">
                            <?php echo $erro; ?>
                        </div>
                        <?php endif; ?>
                        <img style="height: 200px;" class="img-fluid" src="/geolocalizacao/geolocalizacao.png" alt="">
                    </div>
                    <div class="col-md-4"></div>
                </div>
                
            </div>
        </div>
        <h3>Diretoria de Tecnologia - DTIC</h3>
        <p>Comandante: Cel. Alim</p>
        <p>Subcomando: Cap. Liano</p>
        <p>Programação: 3º Sgt Araújo</p>
        <p>Apoio técnico: SD Feitosa</p>
        <hr>
        <?php if(!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true): ?>
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4" style="text-align: center;">
                <form action="" method="post">
                    <input style="margin-bottom: 20px;" placeholder="Digite a senha para poder acessar o sistema..." type="password" name="senha" class="form-control">
                    <button class="btn btn-success">Entrar</button>
                </form>
            </div>
            <div class="col-md-4"></div>
        </div>
        <?php endif; ?>

    </div>
</body>
</html>
