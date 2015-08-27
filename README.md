# Intégration

Site réalisé pour le [BDE de l'UTT](http://bde.utt.fr), sous Laravel 4.2. Le but est de pouvoir importer, gérer les nouveaux étudiants, les assigner à une équipe, ...

## Avertissement

Le code a été écrit un peu à la hâte et ne respecte pas forcément *toutes* les bonnes pratiques. Il sera sûrement retouché au cours de l'année pour être plus cohérent et simple pour quelqu'un qui découvre Laravel.

## Configuration

Un exemple de configuration est disponible dans `env.sample` : il suffit de le modifier et de le renommer de la façon suivante : `.env.production.php` ou `.env.local.php` en fonction des besoins. Il est présent dans le `.gitignore`, et ne sera ainsi pas ré-écrit ni commit.

**À noter que les migrations continnent des instructions spécifiques à MySQL / MariaDB.**

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

#### Prix
* `WEI_PRICES_REGISTRATION` : Prix de l'inscription.
* `WEI_PRICES_DEPOSIT` : Montant de la caution.

#### Dates
* `WEI_DATES_START` : Date de départ vers le WEI (utilisé uniquement pour savoir
si la personne a besoin de l'autorisation parentale).
