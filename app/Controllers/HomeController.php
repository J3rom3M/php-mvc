<?php

namespace App\Controllers;

// Classe gérant la page d'accueil
class HomeController extends CoreController {
    /**
     * Méthode gérant l'affichage de la page d'accueil
     *
     * @return void
     */
    public function home() {
        // On gère l'affichage
        $this->show('home/home');
    }
}