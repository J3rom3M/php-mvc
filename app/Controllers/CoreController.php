<?php

namespace App\Controllers;

abstract class CoreController 
{
    /**
     * Constructeur de CoreController qui sera hérité par chaque classe
     * héritant de CoreController
     */
    public function __construct()
    {
        // On récupère $match (défini dans index.php)
        // pour récupérer la clé name => le nom de la route actuelle
        global $match;
        $currentRouteName = $match['name'];

        // On va créer un tableau qui aura pour clé chacune des routes
        // et pour valeur les roles (sous forme de tableau) autorisés
        // à accéder à cette route (et donc à la méthode du controleur correspondant)
        $acl = [
            // (c) Alan 2020
            // 'user-login'   => [ PUBLIC ],
            // 'user-logout'  => [ PUBLIC ],
            // 'user-connect' => [ PUBLIC ],
            'main-home'       => [ 'admin', 'catalog-manager' ],
            'user-add'        => [ 'admin' ],
            'user-create'     => [ 'admin' ],
            'user-edit'       => [ 'admin' ],
            'user-delete'     => [ 'admin' ],
            'user-list'       => [ 'admin' ],
            'user-update'     => [ 'admin' ],
            'category-add'    => [ 'admin', 'catalog-manager' ],
            'category-create' => [ 'admin', 'catalog-manager' ],
            'category-edit'   => [ 'admin', 'catalog-manager' ],
            'category-update' => [ 'admin', 'catalog-manager' ],
            'category-delete' => [ 'admin', 'catalog-manager' ],
            'category-list'   => [ 'admin', 'catalog-manager' ],
            'product-add'     => [ 'admin', 'catalog-manager' ],
            'product-create'  => [ 'admin', 'catalog-manager' ],
            'product-edit'    => [ 'admin', 'catalog-manager' ],
            'product-update'  => [ 'admin', 'catalog-manager' ],
            'product-delete'  => [ 'admin', 'catalog-manager' ],
            'product-list'    => [ 'admin', 'catalog-manager' ],
            'brand-add'       => [ 'admin', 'catalog-manager' ],
            'brand-create'    => [ 'admin', 'catalog-manager' ],
            'brand-edit'      => [ 'admin', 'catalog-manager' ],
            'brand-update'    => [ 'admin', 'catalog-manager' ],
            'brand-delete'    => [ 'admin', 'catalog-manager' ],
            'brand-list'      => [ 'admin', 'catalog-manager' ],
            'type-add'        => [ 'admin', 'catalog-manager' ],
            'type-create'     => [ 'admin', 'catalog-manager' ],
            'type-edit'       => [ 'admin', 'catalog-manager' ],
            'type-update'     => [ 'admin', 'catalog-manager' ],
            'type-delete'     => [ 'admin', 'catalog-manager' ],
            'type-list'       => [ 'admin', 'catalog-manager' ],
        ];

        // Si la route actuelle est dans la liste des ACL
        if( array_key_exists( $currentRouteName, $acl ) ) :
            // Alors, on récupère le tableau des rôles autorisés pour cette route
            $authorizedRoles = $acl[$currentRouteName];
            // Puis, on appelle checkAuthorization en lui passant ces rôles
            $this->checkAuthorization( $authorizedRoles );
        endif;
        // Sinon, la route n'existe pas dans les ACL => elle n'est pas soumise 
        // au contrôle d'accès, on laisse le script continuer
    }

    /**
     * Méthode permettant de vérifier les droits d'accès
     * @param array $roles Tableau des rôles autorisés
     * @return void
     */
    public static function checkAuthorization( $p_roles = [] )
    {
        // Est-ce qu'on a un utilisateur connecté ?
        if( isset( $_SESSION['connectedUser'] ) ) :

            // Si oui, on le récupère
            // plus précisément, son rôle
            $userRole = $_SESSION['connectedUser']->getRole();

            // On vérifie que ce rôle est "autorisé"
            // => vérifier qu'il se trouve dans le tableau $p_roles en paramètre
            if( in_array( $userRole, $p_roles ) ) : 

                // Si oui, alors on continue le script sans rien bloquer
                return;

            // Sinon, c'est que le rôle de l'user ne lui permet
            // pas d'accéder à la page
            else :

                // On définit le code d'erreur HTTP à 403 (=Forbidden)
                http_response_code(403);

                // On affiche un petit message
                echo "403 Forbidden - Vous n'avez pas accès à cette page";

                // On stoppe l'exécution du script
                exit;
                
            endif;

        // Sinon, on redirige vers la page de login
        // evidemment, on stoppe aussi le script a la redirection
        // sinon la suite de la page risque d'être chargée alors qu'on
        // est pas connecté
        else :
            global $router;
            header( "Location: ". $router->generate( 'user-login' ) );
            exit;
        endif;
    }


    /**
     * Méthode permettant d'afficher du code HTML en se basant sur les views
     *
     * @param string $viewName Nom du fichier de vue
     * @param array $viewVars Tableau des données à transmettre aux vues
     * @return void
     */
    protected function show(string $viewName, $viewVars = []) 
    {
        // On globalise $router car on ne sait pas faire mieux pour l'instant
        global $router;

        // Comme $viewVars est déclarée comme paramètre de la méthode show()
        // les vues y ont accès
        // ici une valeur dont on a besoin sur TOUTES les vues
        // donc on la définit dans show()
        $viewVars['currentPage'] = $viewName; 

        // définir l'url absolue pour nos assets
        $viewVars['assetsBaseUri'] = $_SERVER['BASE_URI'] . 'assets/';
        // définir l'url absolue pour la racine du site
        // /!\ != racine projet, ici on parle du répertoire public/
        $viewVars['baseUri'] = $_SERVER['BASE_URI'];

        // On veut désormais accéder aux données de $viewVars, mais sans accéder au tableau
        // La fonction extract permet de créer une variable pour chaque élément du tableau passé en argument
        extract($viewVars);
        // => la variable $currentPage existe désormais, et sa valeur est $viewName
        // => la variable $assetsBaseUri existe désormais, et sa valeur est $_SERVER['BASE_URI'] . '/assets/'
        // => la variable $baseUri existe désormais, et sa valeur est $_SERVER['BASE_URI']
        // => il en va de même pour chaque élément du tableau

        // $viewVars est disponible dans chaque fichier de vue
        require_once __DIR__.'/../views/layout/header.tpl.php';
        require_once __DIR__.'/../views/'.$viewName.'.tpl.php';
        require_once __DIR__.'/../views/layout/footer.tpl.php';
    }

    protected function showNoLayout(string $viewName, $viewVars = []) 
    {
        // On globalise $router car on ne sait pas faire mieux pour l'instant
        global $router;

        // Comme $viewVars est déclarée comme paramètre de la méthode show()
        // les vues y ont accès
        // ici une valeur dont on a besoin sur TOUTES les vues
        // donc on la définit dans show()
        $viewVars['currentPage'] = $viewName; 

        // définir l'url absolue pour nos assets
        $viewVars['assetsBaseUri'] = $_SERVER['BASE_URI'] . 'assets/';
        // définir l'url absolue pour la racine du site
        // /!\ != racine projet, ici on parle du répertoire public/
        $viewVars['baseUri'] = $_SERVER['BASE_URI'];

        // On veut désormais accéder aux données de $viewVars, mais sans accéder au tableau
        // La fonction extract permet de créer une variable pour chaque élément du tableau passé en argument
        extract($viewVars);
        // => la variable $currentPage existe désormais, et sa valeur est $viewName
        // => la variable $assetsBaseUri existe désormais, et sa valeur est $_SERVER['BASE_URI'] . '/assets/'
        // => la variable $baseUri existe désormais, et sa valeur est $_SERVER['BASE_URI']
        // => il en va de même pour chaque élément du tableau

        // $viewVars est disponible dans chaque fichier de vue
        require_once __DIR__.'/../views/'.$viewName.'.tpl.php';
    }
}
