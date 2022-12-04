# Comment attaquer un nouveau projet en MVC et révisions de notions.


Ce repository vous référence très succintement quelques astuces pour bien commencer un projet MVC ainsi que des révisions sur quelques notions.


En plus de ce readme, c'est également un template de base pour commencer un projet MVC. 

- clonez ce repository ou vous le souhaitez
- renommer le `mvc-recap-revisions` par le nom de votre projet
- vérifiez que le `composer.json` contienne bien toutes les dépendances que vous souhaitez, sinon ajoutez les. (optionnel : dans ce même fichier, configurez l'autoload si vous souhaitez avoir autre chose que `App` comme racine de votre namespace, sinon laissez comme ça)
- ouvrez un terminal dans votre projet et faites `composer update`
- Dupliquez config.init.dist en config.ini puis renseignez vos informations de connexion à la BDD.
- Rendez vous sur votre projet sur localhost, vous arrivez sur une 404 qui vous indique quoi faire :)
  

## Architecture générale


Tous vos projets MVC auront souvent la même base, alors autant être familier avec qui est commun avec tous vos projets !

Pour rappel: 

- un dossier ne contient que des classes ? Le nom commence par une majuscule ex: `Controllers`
- un dossier ne contient pas de classe ou mix de classe et de fichiers normaux ? PAS de majuscule. ex : `views`.

D'un coup d'oeil on devine ce qu'il y a dans nos dossiers comme ça.

#### La structure de base d'un projet

- app
  - Controllers <- *Contiendra tous vos controllers*
    - CoreController.php <- *La classe abstraite qui regroupe plein de méthodes et propriétés  utiles pour tous nos controllers*
  - Models <- *Contiendra toutes vos routes*
    - CoreModel.php <- *La classe abstraite qui regroupe plein de méthodes et propriétés utiles pour tous nos models*
  - Utils
    - Database.php <- *Classe de connexion à la base de donnée, pas besoin de la connaitre par coeur, copiez donc ce fichier de projet en projet ! *
  - views <- *Un dossier contenant toutes les vues de notre projet*
    - error
      - err404.tpl.php
    - layout
      - footer.tpl.php
      - header.tpl.php
  - config.ini <- *Contient nos informations de connexion à la BDD, il n'est pas `gité`, c'est à dire qu'il ne sera pas pousser sur github par exemple lors de `git push`*
  - config.ini.dit <- *template de base pour créer le `config.ini`, qui lui est gité.*
- public <- LE dossier qui sera la racine de notre site une fois en ligne
  - .htaccess <- *permet notamment de rediriger toutes les urls sur le fichier `index.php`*
  - index.php <- *FrontController de notre projet, celui qui va re `require` l'autoload et gérer le `routing`*
  - assets *(Dossier contenant les css/javascript/images)*
- .gitignore
- composer.json
- vendor *Contient les dépendances de votre projet(à ne pas copier d'un projet à l'autre)*

#### Explications sur certains fichiers/dossiers et commandes associées

- Dossier `vendor` : ce sont les librairies installées via `composer`, qui se retrouveront dedans. Le dossier vendor, ne doit pas être copié d'un autre projet.
- composer.json : On référence dedans les librairies (dépendances) qui vont nous être utiles sur notre projet. Une fois fait, on fait `composer install` dans notre projet. Si les dépendances sont exactement les mêmes que pour un autre projet, alors il suffit de copier/coller le composer.json de l'autre projet et faire un `composer install`.
- `composer install` aura pour effet de télécharger et de placer toutes les librairies dans le dossier `vendor`. Ça génerera aussi le fameux fichier `autoload.php`.
- ATTENTION si jamais on change la partie `autoload` dans le composer.json, il faut absolument exécuter la commande `composer dump-autoload`
- Commande pour lancer un serveur `php` : 
- `php -S 0.0.0.0:8080 -t /public`

## Mise en route

- **PRIORISER** les tâches à faire ex: Commencer par le back puis l'intégration si il y a beaucoup de back à faire et que l'inté n'est pas le plus important
- **OPTIONNEL** : Utiliser ce template pour créer un nouveau projet. Le copier simplement en entier, et le renommer par le nom du projet. Ou alors copier son contenu dans le dossier de votre choix. Si vous récupérez le code du prof ou un classroom, vous avez déjà tout de prêt.
- Modifier les paramètres de connexion à la base de donnée
- Analyse quelle route vont être nécessaire : Combien de pages ? Les méthodes a utiliser (GET ou POST) ? Combien de controller à faire ? Peut se faire directement dans le index.php sous forme de commentaire, ou bien dans une note séparée. 
- À partir de la, répéter les actions suivantes pour autant de routes que nécessaire
  - Créer 1 route, lui associer un Controller et une méthode. 
  - Créer ce controller (qui doit étendre du CoreController) et la méthode de ce controller associée sans coder l'intérieur de la méthode, SAUF la fonction `show` qui redirige vers la bonne vue
  - Créer la vue correspondant à cette méthode en mettant un faux contenu dedans mais qui permet de l'identifier
  - TESTER la route pour permettre de s'assurer qu'elle fonctionne bien en partant de l'index.php jusqu'au fichier de la vue
  - (OPTIONNEL créer un lien avec cette route dans le header.tpl.php à l'aide de `$router->generate('nom-route')` pour nous permettre de naviger facilement)
  - Coder l'intérieur de la méthode (au possible) en faisant `semblant` d'utiliser des modèles, comme on le souhaiterais ex: `$products = Product::findAll()`;
  - Créer les fichiers models (si pas déjà créés), nécessaire à cette méthode. Par exemple le model `Product.php` qui contient la classe `Product` qui contient la méthode static `findAll()`
  - Recommencer pour autant de route qu'il y a.


## Révisions

#### Authentification et permission

Pour commencer, il nous faut déjà une table avec des utilisateurs pour pouvoir faire nos tests
- table `app_user` avec les colonnes
  - `email`
  - `password` (hashé/crypté)
  - `role` (ex: superadmin|admin|editor|redactor)


Ensuite il nous faut un formulaire pour pouvoir gérer l'authentification (login)

Quelque chose comme : 

```php
<form action="<?= $router->generate('login-check') ?>" method="POST" class="mt-5">
    <div class="form-group">
        <label for="name">Entrez votre email</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Email de l'utilisateur">
    </div>
    <div class="form-group">
        <label for="subtitle">Entrez votre mot de Passe</label>
        <input type="text" class="form-control" id="password" name="password" placeholder="Mot de Passe" aria-describedby="subtitleHelpBlock">
    </div>
    <button type="submit" class="btn btn-primary btn-block mt-5">Valider</button>
</form>
```

Ce formulaire aura pour action la route qui nous permettra de récupérer les données dans `$_POST` et donc de pouvoir connecter l'utilisateur si les données sont bonnes.

Cette route avec la méthode `POST` nous renvera par exemple sur la méthode `connexion` d'un controller `ConnexionController`.

```php
public function connexion() {
  // On récupère les valeurs des champs email et password
  $email = filter_input(INPUT_POST, 'email');
  $password = filter_input(INPUT_POST, 'password');

  // On va chercher un utilisateur en fonction de l'email reçu
  $user = AppUser::findByEmail($email);

  // Si le mot de passe correspond avec celui de l'utilisateur on créer une clé user dans notre session avec pour valeur l'objet de l'utilisateur
  if ($password == $user->getPassword()) {
      
      $_SESSION['user'] = $user;
      
  } else {
    // Code pour renvoyer sur une page d'erreur par exemple
    // Ou de nouveau sur la page de connexion
  }
}
```

Maintenant que notre fonctionnalité de connexion marche, on peut restreindre nos pages en fonctions du rôle de l'utilisateur.

Dans notre `CoreController` on crée cette méthode

```php
protected function checkAuthorization(array $authorizedRoles)
{
    // Y'a-t-il un user connecté ?
    if (isset($_SESSION['user'])) {

        // On le récupère
        $currentUser = $_SESSION['user'];
        // On récupère son rôle
        $userRole = $currentUser->getRole();
        // Vérifier si le rôle du User en session est dans la liste reçue
        // @see https://www.php.net/manual/fr/function.in-array
        if (in_array($userRole, $authorizedRoles)) {
            // Retourne true
            return true;
        }
        // User n'a pas la bonne permission
        $this->display403();
    }

    // Idéalement on devrait renvoyer une 401,
    // par souci d'érgonomie, on redirige vers le formulaire de connexion
    // @todo Créer une page 401 avec lien vers le form de login + status code

    // Si non connecté, redirection vers le formulaire de connexion
    $this->redirectToRoute('user-login');
}
```

Maintenant que cette méthode est créée, on peut l'utiliser sur les méthodes correspondant à nos routes pour restreindre leur accès.


```php

public function create() {
  // On lance la méthode qui permet de vérifier les autorisations de l'utilisateur courant. On indique en paramètre qu'il faut être admin ou redactor pour y avoir accès
  $this->checkAuthorization(['admin','redactor']);

  // Reste du code
  // [...]
}
```

Cette version est simple mais nous oblige à le faire sur chaque méthode à chaque fois. Il est possible de le faire de manière plus globale, mais ça ne sera pas détaillé ici, le code des profs est suffisant, sinon venez nous voir.


#### PDO::prepare();

Prépare est une méthode de la classe PDO nous permettant comme son nom l'indique de préparer les données avant de les envoyer en base de donnée. Les préparer pour quoi ? Pour s'assurer qu'il n'y aa pas des caractères innatendus de stocker dans le base de donnée, voir des tentatives de hack. PDO s'occupe pour nous de tout nettoyer afin d'éviter les problèmes de sécurité. Prepare est donc indispensable pour des requête de type `INSERT` ou `UPDATE`

**Exemple d'insert:**

En premier lieu on crée notre requète SQL en mettant des paramètres sous la forme `:name`, `:subtitle` etc ...au lieu de mettre directement les valeurs souhaitées.

```php
// Récupération de l'objet PDO représentant la connexion à la DB
pdo = Database::getPDO();

// Requète SQL
$sql = "
    INSERT INTO `category` (`name`, `subtitle`, `picture`)
    VALUES (:name, :subtitle, :picture)
";
```

Ensuite on utilise la méthode prepare, avant de commencer à remplacer ces paramètres par nos valeurs.

```php
$pdoStatement = $pdo->prepare($sql);
```

Une fois fait, on peut remplacer chacun de nos paramètres par nos valeurs grâve à `bindValue`; 

```php
$pdoStatement->bindValue(':name', $this->name, PDO::PARAM_STR);
```

- Le premier argument de bindValue est le paramètre qu'on a mit dans notre requête
- Le deuxième c'est notre **valeur** qu'on veut mettre à la place
- Le troisième c'est pour indiquer le **type** de valeur que c'est

Une fois nos `bindValue` fait, la requète est prête, on peut l'executer et stocker le résultat dans une variable pour savoir si l'insert a bien marché par exemple.

```php
$success = $pdoStatement->execute();
```


#### Static

L'intérêt et l'utilisation de `static` est plutôt simple. 

Le mot clé `static` nous permet d'appeler une méthode d'une classe par exemple, sans avoir besoin d'instancier la classe avant. 

Pour qu'une méthode soit static on doit la définir comme suivant : 

```php
public static function findAll() {
  // code ...
}
```

La ou sans méthode `static` on doit faire par exemple : 

```php
use App\Models\Category;

$categoryModel = new Category;
$categories = $categoryModel->findAll();

```

avec une méthode `static` on peut l'éxecuter directement comme ça : 

```php
use App\Models\Category;

$categories = new Category::findAll();

```

C'est plus court non ? Pourquoi s'en priver :)


### Abstract class

*Abstract class ? Houlala pour quoi faire ?* 
Pas d'inquiétude ! Si vous n'en faite pas, votre code ne sera pas tout cassé. Enfait il s'agit de donnée une information utile à vous et aux autres développeurs travaillant sur le projet. 

Une classe abstraite est une classe qui n'a pas vocation à être instancié, car elle est simplement la pour définir des méthodes et propriétés de base, qui serviront à des classes enfantes. 

Et je sais que vous en connaissez des classes qui ont exactement ce comportement ! `CoreModel` ou `CoreController` en sont des parfait exemples. Ces classes la ne sont jamais instanciées car elle sont juste la pour regrouper des méthodes utiles pour toutes les classes enfantes. 

Donc pour être sur qu'aucun autre développeur n'instancie ces classes dirctement, on ajoute le mot clé `abstract`

```php
abstract class CoreModel {
  // Code
}
```

Comme ça, d'un seul coup d'oeil un développeur sait qu'il doit étendre (`extends`) cette classe et non l'instancié directement. Et si il essai, ça déclenchera une erreur.

Encore une fois, si vous ne le faites pas pour le moment, ce n'est pas grave et le votre code marchera, c'est simplement une bonne pratique, surtout quand on travail à plusieurs. (et même pour nous quand on revient plus tard sur notre code ! )

