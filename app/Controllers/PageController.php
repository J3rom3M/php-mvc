<?php

namespace App\Controllers;

// Classe gérant la page d'accueil
class PageController extends CoreController {
    /**
     * Méthode gérant l'affichage du corps des pages du projet
     *
     * @return void
     */
    public function tour() {
        // On gère l'affichage
        $this->showNoLayout('page/tour');
    }

}