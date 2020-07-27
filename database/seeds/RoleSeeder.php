<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Carbon;

class RoleSeeder extends Seeder
{
    /**
     * Insert example roles in the database can be use in prod or in tests
     *
     * @return void
     */
    public function run()
    {
        Role::insert([
            [
                'name' => 'Coordinateur',
                'description' => 'Faire une croix sur son été pour organiser la plus belle des inté !',
                'show_in_form' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Chef d\'équipe',
                'description' => 'Animer et accompagner une équipe de 25-30 nouveaux pendant toute l\'intégration.',
                'show_in_form' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Respo CEs',
                'description' => 'Gérer les CEs',
                'show_in_form' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Logistique',
                'description' => 'Jouer avec des transpalettes, rassembler et acheminer tout le matériel nécessaire pour chaque activité de l\'intégration.',
                'show_in_form' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Respo boisson',
                'description' => 'Gérer les boissons et les tireuses, tenir des bars',
                'show_in_form' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Communication',
                'description' => 'Animer nos différents réseaux sociaux pour teaser les nouveaux jusqu\'au bout.',
                'show_in_form' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Graphisme',
                'description' => 'Si tu aimes t\'amuser sur photoshop, l\'inté a besoin de toi pour de nombreux éléments : couverture du Gubu, t-shirt, supports de communication, etc.',
                'show_in_form' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Bouffe',
                'description' => 'Manger c\'est sacré ! Il nous faut des cuisto\' pour préparer les repas avec amour pour tous ces ventres affamés.',
                'show_in_form' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Déco',
                'description' => 'Créer une déco stylée pour habiller l\'UTT sur le thème de l\'intégration.',
                'show_in_form' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Animation',
                'description' => 'Pourquoi on est là ?!',
                'show_in_form' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Partenariat',
                'description' => 'Aller voir des entreprises pour récupérer des goodies, des réductions pour les étudiants ou de l\'argent pour l\'inté.',
                'show_in_form' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'GUBU',
                'description' => 'Mettre à jour le GUBU avec les nouveaux bails.',
                'show_in_form' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Securité',
                'description' => 'Jouer les durs avec un talkie walkie, décider où vont les barrières pour éviter les morts et gérer les agents de sécurité.',
                'show_in_form' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Son et lumière',
                'description' => 'Quelques kilowatt de son, plein de lumières dans tous les sens et tout ça pour la soirée d\'intégration, le WEI et le M500.',
                'show_in_form' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Développeur',
                'description' => 'Il y a toujours de nouvelles fonctionnalités à ajouter au site de l\'inté et à l\'application mobile. A quand un décompte en temps réel du nombre de tour de rond points !',
                'show_in_form' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'DJ',
                'description' => 'Parce qu\'à la soirée d\'inté comme au WEI, on a besoin de vrai set !',
                'show_in_form' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Traduction anglais',
                'description' => 'L\'UTT c\'est 24% d\'étrangers, et si on les intégrait aussi ?',
                'show_in_form' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Argentique/Média',
                'description' => 'Si tu aimes documenter la vie du jeune étudiant et mettre à profit toutes des compétences de photographie ou de création de vidéo cette com est pour toi ! L\'inté a besoin de toi pour les photos des soirées et durant toute la semaine, mais aussi pour les films de présentation et les Interview du wei !',
                'show_in_form' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Parrainages',
                'description' => 'Gérer les parrainages pendant l\'été, organiser la rencontre nouveaux / parrains.',
                'show_in_form' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Prévention',
                'description' => 'Incorporer plus d\'actions de prévention pendant l\'intégration.',
                'show_in_form' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Evènements
            [
                'name' => 'Défis TC',
                'description' => 'Préparer l\'aprèm où les nouveaux TC devront faire preuve d\'ingéniosité.',
                'show_in_form' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Animations en M104',
                'description' => 'Organiser, avec les CEs et les assos, des animations chill en M104 le Mardi et le Mercredi matin.',
                'show_in_form' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Visite des locaux',
                'description' => 'Organiser une visite des locaux.',
                'show_in_form' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Faux discours',
                'description' => 'Ecrire et réaliser un faux discours.',
                'show_in_form' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Olympiades Assos',
                'description' => 'Organiser les Olympiades associatives, avec l\'aide des associations.',
                'show_in_form' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'BBQ Asso',
                'description' => 'Organiser le BBQ Asso, l\'agencement de l\'évènement et les animations de la soirée.',
                'show_in_form' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Faux test',
                'description' => 'Organiser ce magnifique troll et faire un best-of des conneries qu\'ils auront écrits.',
                'show_in_form' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Course d\'Orientation à l\'UTT',
                'description' => 'Organiser la course d\'orientation de découverte de l\'UTT, où les nouveaux pourront visiter des parties inaccessible de l’UTT.',
                'show_in_form' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Soirée d’Intégration',
                'description' => 'Organiser une petite soirée de 1000 personnes dans l\'UTT.',
                'show_in_form' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Rallye',
                'description' => 'Organiser les activités du rallye du Jeudi après-midi, regroupant toutes les équipes de nouveaux.',
                'show_in_form' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'WEI',
                'description' => 'Organiser le WEI.',
                'show_in_form' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Bénévole',
                'description' => 'Pas dispos avant pour l\'organisation, t\inquiète il y aura l\occasion d\'aider pendant la semaine.',
                'show_in_form' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}
