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
            <p>Ta mission la plus importante sur ce site est de tenir tes informations de contact à jour et de rejoindre le groupe Facebook suivant afin de pouvoir être contacté lorsque l'intégration a besoin de toi !</p>

            <p><a class="btn btn-success" href="https://www.facebook.com/groups/benevoleinte2018/" target="_blank">
                <i class="fa fa-facebook-official" aria-hidden="true"></i>
                Rejoindre le groupe facebook des bénévoles
            </a></p>


            @if (Auth::user()->isAdmin())
                <hr/>
                <p>
                    Comme tu es administrateur, ce site te permettra de faire pleins d'autres truc, mais j'ai la flemme de tous les décrire.
                    Du coup je te laisse faire joujou avec. ;)<br/> <em>Enfin.. évite de tout casser, hein !</em>
                </p>
            @endif
            @if (Auth::user()->ce)
                <hr/>
                <p>
                    En tant que <strong>chef d'équipe</strong>, ce site te permet de constituer et de mettre à jour les informations de ton équipe.</em>
                </p>
                @if (!Auth::user()->team)
                    <a class="btn btn-primary" href="{{ route('dashboard.ce.teamlist') }}">Créer une équipe</a>
                @else
                    <a class="btn btn-primary" href="{{ route('dashboard.ce.myteam') }}">Voir mon équipe</a></li>
                    <a class="btn btn-default" href="{{ route('dashboard.ce.teamlist') }}">Liste des équipes</a></li>
                @endif
            @endif
        </div>
</div>
@endsection
