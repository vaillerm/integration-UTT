# Intégration

Site réalisé pour le [BDE de l'UTT](http://bde.utt.fr), sous Laravel 4.2. Le but est de pouvoir importer, gérer les nouveaux étudiants, les assigner à une équipe, ...

## Avertissement

Le code a été écrit un peu à la hâte et ne respecte pas forcément *toutes* les bonnes pratiques. Il sera sûrement retouché au cours de l'année pour être plus cohérent et simple pour quelqu'un qui découvre Laravel.

## Configuration

Un exemple de configuration est disponible dans `env.sample` : il suffit de le modifier et de le renommer de la façon suivante : `.env.production.php` ou `.env.local.php` en fonction des besoins. Il est présent dans le `.gitignore`, et ne sera ainsi pas ré-écrit ni commit.

**À noter que les migrations continnent des instructions spécifiques à MySQL / MariaDB.**
