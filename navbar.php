<?php

// Verifica se o usuário está autenticado
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    $aparece = false;
} else {
    $aparece = true;
}
?>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="/geolocalizacao">PM RN</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Alternar navegação">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if($aparece): ?>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="geolocalizacao.php">Geolocalização por Endereço</a>
                </li>
                <!-- Menu suspenso para Importar -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Importar
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="importador.php">Importar Escala de Serviço</a></li>
                        <li><a class="dropdown-item" href="importador_coordenadas.php">Importar Coordenadas</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="policial_local.php">Policial/Local</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?sair=true">Sair</a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>