<?php

namespace Carbe\Petitcreuxv2\Templates\Partials;
use Carbe\Petitcreuxv2\Helpers\Auth;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/style.css">
     <!-- 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="/assets/scripts/Flash.js" type="text/javascript" defer></script> -->
    <title><?= $title ?></title>
</head>
<body>
    <header class="border-bottom  border-cacao border-2">
        <nav class="navbar navbar-expand-lg bg-primary">
            <div class="container-fluid">
                <a class="navbar-brand" href="/">
                    <img src="/assets/images/Icone_Petit_Creux.svg" alt="Logo">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="menuPrincipal">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item fs-5"><a class="nav-link" href="/categories">Catégories</a></li>
                        <li class="nav-item fs-5"><a class="nav-link" href="/a-propos">À propos</a></li>
                        <li class="nav-item fs-5">
                            <?php if (Auth::viewAuth()): ?>
                                <a class="nav-link" href="/mon-compte">
                                    Gérer mon compte
                                </a>
                            <?php else: ?>
                                <a class="nav-link" href="/login">Connexion/Inscription</a>
                            <?php endif; ?>
                        </li>
                        <?php if (Auth::viewAdmin()): ?>
                            <li class="nav-item fs-5"><a class="nav-link" href="/admin">Administration</a></li>
                        <?php endif; ?>
                        <?php if (Auth::viewAuth()): ?>
                          <li class="nav-item fs-5"><a class="nav-link" href="/logout">Déconnexion</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
</body>
</html>