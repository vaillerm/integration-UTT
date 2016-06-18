# Intégration

Site réalisé pour le [BDE de l'UTT](http://bde.utt.fr), sous Laravel 5.2. Le but est de pouvoir importer, gérer les nouveaux étudiants, les assigner à une équipe, ...

## Avertissement

Le code a été écrit un peu à la hâte et ne respecte pas forcément *toutes* les bonnes pratiques. Il sera sûrement retouché au cours de l'année pour être plus cohérent et simple pour quelqu'un qui découvre Laravel.

## Configuration

Un exemple de configuration est disponible dans `.env.example` : il suffit de le modifier et de le renommer en : `.env`.
Il est présent dans le `.gitignore`, et ne sera ainsi pas ré-écrit ni commit.

**À noter que les migrations continnent des instructions spécifiques à MySQL / MariaDB.**

## Installation

Après avoir cloné le projet dans le repertoire que vous souhaitez.
Configurez le fichier `.env`, comme indiqué ci-dessus.
Créez la base de donnée et ajoutez les identifiants au fichier `.env`;

Ensuite executez les commandes suivantes :

```bash
composer install
php artisan key:generate
php artisan migrate
```

Si vous utilisez *Nginx* comme serveur web, vous pouvez vous inspirer du fichier
suivant pour la configuration :

```
server {
    listen 80;
    server_name integration.uttnetgroup.net;
    server_name integration.utt.fr;


    root /var/www/integration-UTT/public/;
    index index.php index.html index.htm;

    location ~ ^/deploy$ {
        root /var/www/deploy/;
        try_files /deploy.php =404;

        fastcgi_pass unix:/var/run/php-fpm/php-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }

    location / {
         try_files $uri $uri/ /index.php$is_args$args;
    }

    location ~ ^/index\.php$ {
        try_files /index.php =404;
        fastcgi_pass unix:/var/run/php-fpm/php-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```


En cas de problèmes de droits, veuillez executer les commandes suivantes, en remplacant `www-data` avec l'utilisateur qui execute *PHP*

```bash
chown -R www-data:www-data .
find . -type d -exec chmod 755 {} \;
find . -type d -exec chmod ug+s {} \;
find . -type f -exec chmod 644 {} \;
sudo chgrp -R www-data storage bootstrap/cache
sudo chmod -R ug+rw storage bootstrap/cache

# For Centos/Redhat (SELinux)
chcon -Rt httpd_sys_content_t .
chcon -Rt httpd_sys_rw_content_t storage bootstrap/cache .git .env
```

## Week-end d'intégration

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
