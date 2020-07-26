# Intégration

Site réalisé pour le [BDE de l'UTT](http://bde.utt.fr), sous Laravel 5.2. Le but est de pouvoir importer, gérer les nouveaux étudiants, les assigner à une équipe, ...

## Getting started

Pour développer le site d'inté, vous aurez besoin d'avoir les logiciels suivant installé sur votre ordinateur

* git
* PHP 7.1+ (Avec les extensions: gd, mysql)
* Composer
* MariaDB (les migrations contiennent des instructions spécifiques à MySQL / MariaDB)

Exemple d'installation de dépendance pour Ubuntu 20.04, ajustez cette commande pour votre système :

```
sudo apt install git php7.4 php7.4-gd php7.4-mysql composer mariadb-server mariadb-client
```

Commencez par cloner le repo git où vous le souhaitez

```
git@github.com:ungdev/integration-UTT.git
```

Entrez ensuite dans ce dossier et installez toutes les dépendances composer:

```
cd integration-UTT
composer install
```

### Configuration et initialisation de la base de donnée

Une fois les dépendances installées, nous pouvons configurer la base de donnée. Coté MariaDB, il suffit de créer la base de donnée vide et un utilisateur associé:

```
sudo mysql
create database integration;
CREATE USER 'integration'@'localhost' IDENTIFIED BY 'TheDbPassword';
GRANT ALL PRIVILEGES ON integration.* TO 'integration'@'localhost';
FLUSH PRIVILEGES;
```

Il faut ensuite indiquer à laravel comment se connecter à la base de donnée. Pour cela, on crée le fichier de config `.env`.

```
cp .env.example .env
```

Puis dans le `.env` modifiez les éléments suivants

```
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=integration
DB_USERNAME=integration
DB_PASSWORD=TheDbPassword
```

Maintenant que la base de donnée est configuré, nous pouvons lancer les migrations. Cela permettra de créer la structure de la base de donnée.

```
php artisan migrate
```

### Génération des clés de chiffrement

Juste avant de pouvoir lancer le site, vous devez lancer les commandes suivantes

```
php artisan key:generate
php artisan passport:install
```

### Lancement du site

```
php artisan serve
```

Vous devriez maintenant pouvoir accéder au site depuis http://127.0.0.1:8000. Vous ne pouvez par contre pas encore vous connecter..


### Configuration de la connexion via EtuUTT

L'une des façons de vous connecter au site, est de le relier à EtuUTT. Pour cela, rendez vous sur [la section developpeur d'EtuUTT](https://etu.utt.fr/api/panel) et créez une application

Concernant l'URL de redirection, mettez `http://localhost/oauth/callback`. Si nécéssaire remplacez `localhost` avec le nom de domaine ou l'ip et le port sur lequel vous accédez à votre version de développement du site.

Le site de l'intégration n'a besoin d'avoir accès que aux `Données publiques` puisqu'il se sert du site étudiant uniquement pour l'authentification.

Une fois l'application créée sur EtuUTT, récupérez le `client id` et `client secret` pour les mettre dans le fichier `.env` sous les clées `ETUUTT_CLIENT_ID` et `ETUUTT_CLIENT_SECRET`.

Une fois fait, vous devriez pouvoir vous connecter via le formulaire de connexion dédié aux étudiants.

### Ajout d'un administrateur

Vous pouvez vous connecter, mais vous ne serez qu'un simple bénévole. Pour devenir admin, il vous suffit de lancer la commande suivante

```
php artisan integration:user:set-admin
```

### Remplissage de la base de donnée

Si vous ne disposez pas de données réelles, vous voudrez sans-doute remplir la base de donnée avec des utilisateurs et équipes aléatoires. Pour cela, lancez la commande suivante

```
php artisan db:seed
```

### Envoi des emails

Le `MAIL_DRIVER` par défaut est `log`. Cela veut dire que tous les emails envoyés finiront dans les logs et que vous pouvez jouer avec le site sans craindre d'envoyer des emails en masses à tous les nouveaux. Vous retrouverez le contenu des emails envoyés dans `storage/logs/laravel-*.log`

Cependant, comme l'envoi d'email se fait en asynchrone via le mécanisme de queue de laravel, l'envoi d'email ne fonctionnera pas jusqu'à ce que vous executiez la commande suivante

```
php artisan queue:listen
```


### Production
Pour la mise en production, vous pouvez suivre une bonne partie des sections précédentes. Cependant, assurez vous aussi de lancer le processus de queue en tache de fond :

```
php artisan queue:work --queue=high,low --sleep=3 --tries=3 --daemon
```

Ainsi que les cron suivantes

```
# Import régulier des nouveaux utilisateurs depuis l'utt
*/5 * * * * php artisan integration:newcomers:import
# Divers taches dont l'envoi d'email
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## Documentation générale
### Import des photos de profile des étudiants

Assurez vous que le dossier `public/uploads/students-trombi` existe, puis executez la commande suivante :
```
php artisan integration:students:importPictures
```

### Week-end d'intégration

Les inscriptions au week-end d'intégration sont gérées depuis l'interface d'administration :
les personnes n'ont pas à renseigner elles-même leurs informations.

Une inscription est considérée comme complète uniquement si les critères suivants sont réunis :
* Le chèque de paiement et de caution sont bien donnés.
* Si la personne est mineure lors du jour de départ du WEI, l'autorisation
parentale a été donnée.
* Toutes les informations nécessaires au contact de la personne (email, téléphone)
sont présentes.
* Le montant de tous les chèques donnés lors de l'inscription correspondent au
prix du WEI et de la caution.

Les chèques sont gérés dans une table différente des inscriptions au WEI, dans
le but de pouvoir faire la distinction entre chèque de caution et de paiement.
Les informations liées à chaque chèque (numéro, banque, émetteur) sont enregistrées
dans la table `checks`.

### Configuration

#### EtuUTT

Les parrains, CE et admins sont identifiés via le site etudiant EtuUTT. Il faut
donc créer une application sur le site avec comme URL de redirection `https://integration.utt.fr/oauth/callback`.
Vous devez aussi cocher le scope `Données privées`

Ensuite il faut configurer les champs suivants dans le fichier `.env` :

* `ETUUTT_CLIENT_ID` : Application Id donné par EtuUTT
* `ETUUTT_CLIENT_SECRET` : Application Secret donné par EtuUTT

#### Weekend
* `WEI_PRICES_REGISTRATION` : Prix de l'inscription.
* `WEI_PRICES_DEPOSIT` : Montant de la caution.

#### Dates
* `WEI_DATES_START` : Date de départ vers le WEI (utilisé uniquement pour savoir
si la personne a besoin de l'autorisation parentale).

#### Application mobile

Pour que l'application mobile puisse accéder au site etu, ses credentials doivent être configurés sur le site d'intégration.

#### CE

Pour configurer le shotgun CE, les variables suivantes doivent être configurées :
```
services.ce.maxTeamTc - Nombre maximum d'équipes TC
services.ce.maxTeamBranch - Nombre maximum d'équipes de branche
services.ce.opening - Date d'ouverture du shotgun ()
services.ce.deadline - Date de fin de d'édition des équipes, pour l'envoi aux nouveaux
services.ce.fakeDeadline - Date publique de fin d'édition des équipes
services.ce.teamNameOpening - Date d'ouverture d'attribution des noms d'équipes
```

Pour pouvoir modifier les noms d'équipe, le thème (`services.theme`) doit avoir été configuré au préalable.

#### Parrains

Pour configurer le shotgun CE, les variables suivantes doivent être configurées :
```
services.referral.opening - Date d'ouverture des inscriptions des parrains
services.referral.deadline - Date de fermeture des inscriptions parrains
services.referral.fakeDeadline
```
