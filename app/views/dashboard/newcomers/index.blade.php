@extends('layouts.dashboard')

@section('title')
<h1>
    Nouveaux
    <small>Affichage des profils</small>
</h1>
@endsection

@section('content')

@include('display-errors')

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
