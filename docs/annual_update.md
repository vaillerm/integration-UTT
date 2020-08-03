# Mise à jour annuelle

Chaque année, certaines informations doivent être mises à jour, par exemple les dates de rentrée. Les variables à mettre à jour sont modifiables en tant qu'`admin` dans le menu `Configuration --> Paramétrages`.

## Date de rentré

* `services.reentry.tc.date` : Date de rentrée des TCs
* `services.reentry.tc.time` : Heure de rentrée des TCs
* `services.reentry.branches.date`
* `services.reentry.branches.time`
* `services.reentry.masters.date`
* `services.reentry.masters.time`

## Date du WEI

* `services.wei.start` : Date de départ vers le WEI (utilisé uniquement pour savoir si la personne a besoin de l'autorisation parentale).
* `services.wei.registrationStart` : Date de début des inscriptions au WEI
* `services.wei.registrationEnd` : Date de fin des inscriptions au WEI

## CE

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

## Parrains

Pour configurer le shotgun CE, les variables suivantes doivent être configurées :
```
services.referral.opening - Date d'ouverture des inscriptions des parrains
services.referral.deadline - Date de fermeture des inscriptions parrains
services.referral.fakeDeadline
```
