# Projet Symfony 6.4

## Prérequis

Avant de commencer, assurez-vous d'avoir les éléments suivants installés sur votre machine :

- [PHP 8.1 ou supérieur]
- [Composer]
- [Symfony CLI]
- [MySQL]

## Installation

Suivez ces étapes pour installer le projet localement.

1. Clonez le dépôt :

   git clone https://github.com/kchaoukchaou/projectGoogleCloud.git
   
   cd votre-projet

2. Installez les dépendances PHP via Composer :
    
    composer install

3. Copiez le fichier .env et configurez votre environnement :

   cp .env .env.local

4. Créez la base de données :

   symfony console doctrine:database:create

5. Appliquez les migrations pour créer les tables nécessaires :

   symfony console doctrine:migrations:migrate

6. Chargez les données de fixtures:
   (ça va creer un user de test login: admin@gmail.com password: admin1234)

   symfony console doctrine:fixtures:load

7. Pour démarrer le serveur de développement Symfony, exécutez :

   symfony server:start

8. les etapes pour tester
- vous allez trouver dans le racine de projet ce fichier
  "test api upload.postman_collection.json" c'est une collection à importer dans postman
  pour pouvoir tester l'upload des images, faudrait juste dans le body=>form-data
  dans Value importer un ou plusieurs images et lancer l'api après rendez-vous sur http://127.0.0.1:8000/login
  
- login: admin@gmail.com password: admin1234

 vous allez être redirigé vers une page qui affiche les images importées 

NB: j'ai rencontré un problème de mémoire au moment d'import donc j'ai augmenté le memory_limit dans php.ini

