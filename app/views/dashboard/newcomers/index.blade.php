@extends('layouts.dashboard')

@section('title')
<h1>
    Nouveaux
    <small>Affichage des profils</small>
</h1>
@endsection

@section('content')

@include('display-errors')

<div class="box box-default collapsed-box">
    <div class="box-header with-border">
        <h3 class="box-title">Ajouter un noveau</h3>
        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
    </div>
    <div class="box-body text-center">
        <form class="" action="{{ route('dashboard.newcomers.create') }}" method="post">
            <input type="text" name="first_name" class="form-control" value="" placeholder="Prénom">
            <input type="text" name="last_name" class="form-control" value="" placeholder="Nom">
            <input type="text" name="email" class="form-control" value="" placeholder="Adresse email">
            <input type="text" name="level" class="form-control" value="" placeholder="Niveau (ex. TC, ISI...)">
            <input type="submit" class="btn btn-success form-control" value="Créer le nouveau">
        </form>
    </div>
</div>

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Liste des nouveaux</h3>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            <tbody>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Mot de passe</th>
                    <th>Niveau</th>
                    <th>Parrain</th>
                    <th>Équipe</th>
                    <th>Action</th>
                </tr>
                @foreach ($newcomers as $newcomer)
                    <tr>
                        <td>{{{ ucfirst($newcomer->first_name) . ' ' . strtoupper($newcomer->last_name) }}}</td>
                        <td>{{{ $newcomer->email }}}</td>
                        <td>{{{ $newcomer->password }}}</td>
                        <td>{{{ $newcomer->level }}}</td>
                        @if ($newcomer->referral)
                            <td>{{{ $newcomer->referral->first_name . ' ' . strtoupper($newcomer->referral->last_name) }}}</td>
                        @else
                            <td>Aucun !</td>
                        @endif
                        @if ($newcomer->team)
                            <td>{{{ $newcomer->team->name }}}</td>
                        @else
                            <td>Aucune !</td>
                        @endif
                        <td>
                            <a href="{{ route('dashboard.newcomers.profile', ['id' => $newcomer->id ]) }}" class="btn btn-success btn-xs">Afficher le profil</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
