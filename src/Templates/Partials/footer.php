<?php

namespace Carbe\Petitcreuxv2\Templates\Partials;

?>

<footer class="text-center bg-primary border-top border-light border-2 p-3 d-flex flex-column">
    
    <div class="d-flex justify-content-evenly mb-3">
        <div>
            <ul class="list-unstyled">
                <li class="nav-item fs-5"><a class="text-cacao nav-link" href="/categories">Catégories</a></li>
                <li class="nav-item fs-5"><a class="text-cacao nav-link" href="/a-propos">A propos</a></li>
                <li class="nav-item fs-5"><a class="text-cacao nav-link" href="/contact">Contact</a></li>
                <li class="nav-item fs-5"><a class="text-cacao nav-link" href="/mentions-legales">Mentions Légales</a></li>
            </ul>
        </div>
        <div>
            <ul class="list-unstyled">
                <li class="nav-item fs-5"><a class="text-cacao nav-link" href="/mon-compte">Mon Compte</a></li>
                <li class="nav-item fs-5"><a class="text-cacao nav-link" href="/conditions">Conditions générales d'utilisations</a></li>
                 <?php if (isset($_SESSION['auth_user'])): ?>
                              <li class="nav-item fs-5"><a class="text-cacao nav-link" href="/logout">Déconnexion</a></li>
                            <?php endif; ?>
            </ul>
        </div>
        <div>
            <h4 class="fs-5">Suivez-nous</h4>
            <a href="https://www.instagram.com/" target="_blank"><img src="/assets/images/socials/instagram.svg" alt="instagram"></a>
            <a href="https://x.com/" target="_blank"><img src="/assets/images/socials/twitter.svg" alt="twitter"></a>
            <a href="https://www.facebook.com/?locale=fr_FR" target="_blank"><img src="/assets/images/socials/facebook.svg" alt="facebook"></a>
            <a href="https://fr.pinterest.com/" target="_blank"><img src="/assets/images/socials/pinterest.svg" alt="pinterest"></a>   
        </div>
    </div>
    <div class="">
       <small>Petit Creux 2025 tous droits réservés</small>
    </div>
</footer>