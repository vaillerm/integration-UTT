@extends('layouts.dashboard')

@section('title')
    Permanences de {{ $user->first_name.' '.$user->last_name }}
@endsection

@section('smalltitle')
    Liste de toutes permanences de {{ $user->first_name.' '.$user->last_name }}
@endsection

@section('content')

    <div class="callout callout-info">
        <h4>Liste de toutes les permanences de {{ $user->first_name.' '.$user->last_name }}</h4>
    </div>

    <div class="box box-default">
        <div class="box-header with-border">
        <h3 class="box-title">Liste des permanences</h3>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <tbody>
                    <tr>
                        <th>Nom</th>
                        <th>Lieu</th>
                        <th>Date</th>
                        <th>Points reçus</th>
                        <th>Présence</th>
                        <th>Raison de l'absence</th>
                        <th>Commentaire</th>
                    </tr>
                    @foreach ($user->perms as $perm)
                        <tr>
                            <td>{{ $perm->type->name }}</td>
                            <td>{{ $perm->place }}</td>
                            <td>Le {{ date('d/m', $perm->start).' de '.date('H:i', $perm->start).' à '.date('H:i', $perm->end) }}</td>
                            <td>{{ $perm->type->points - $perm->pivot->pointsPenalty }}</td>
                            <td>{{ $perm->pivot->presence }}</td>
                            <td>{{ $perm->pivot->absence_reason }}</td>
                            <td>{{ $perm->pivot->commentary }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
