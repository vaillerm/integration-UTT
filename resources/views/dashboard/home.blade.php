@extends('layouts.dashboard')

@section('title')
Accueil
@endsection

@section('smalltitle')
Informations diverses pour toi !
@endsection

@section('content')
<div class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">Bonjour à toi bénévole !</h3>
        </div>
        <div class="box-body">
            <p>Si tu vois ce message, c'est que tu fais partie de la grande famille des bénévoles de l'intégration. <strong>Et nous t'en remercions !</strong></p>

            <p>Tu te trouves sur le panneau de gestion du site de l'intégration.
                Ta mission la plus importante sur ce site est de tenir à jour ton numéro de téléphone et ton email afin de pouvoir être contacté lorsque l'intégration a besoin de toi. Tu peux les modifier en allant dans <strong>Mon compte > Mon profil bénévole</strong>.
            </p>

            @if (EtuUTT::student()->isAdmin())
                <p>
                    Comme tu es administrateur, ce site te permettra de faire pleins d'autres truc, mais j'ai la flème de tous les décrire.
                    Du coup je te laisse faire joujoux avec ;) <br/> <em>Enfin.. evite de tout casser, hein !</em>
                </p>
            @endif
        </div>
</div>
@endsection
