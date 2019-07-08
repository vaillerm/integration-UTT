@extends('layouts.dashboard')

@section('title')
    Permanenciers de la perm {{ $perm->type->name }}
@endsection

@section('smalltitle')
    Permanenciers de la perm {{ $perm->type->name }}
@endsection

@section('content')

    <div class="callout callout-info">
        <h4>Permanenciers de la perm {{ $perm->type->name }}</h4>
        <p>
            Voici la liste des permanences de la perm {{ $perm->type->name }} le 
            {{ date('d/m', $perm->start).' de '.date('H:i', $perm->start).' à '.date('H:i', $perm->end) }}
        </p>
    </div>

    <div class="box-header with-border">
        <h3 class="box-title">Ajouter un permanencier</h3>
        <a href="{{ url('dashboard/perm/'.$perm->id.'/users/new') }}" class="btn btn-box-tool">
            <i class="fa fa-plus"></i>
        </a>
    </div>

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Liste des permanenciers</h3>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <tbody>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Présence</th>
                        <th>Raison de l'absence</th>
                        <th>Commentaire</th>
                        <th>Points</th>
                        <th>Actions</th>
                    </tr>
                    @foreach ($perm->permanenciers as $user)
                        <tr>
                            <td>{{ $user->last_name }}</td>
                            <td>{{ $user->first_name }}</td>
                            <td>{{ $user->pivot->presence }}</td>
                            <td>{{ $user->pivot->absence_reason }}</td>
                            <td>{{ $user->pivot->commentary }}</td>
                            <td>{{ $perm->type->points - $user->pivot->pointsPenalty }}</td>
                            <td>
                                <a class="btn btn-xs btn-success" href="{{ url('dashboard/perm/'.$perm->id.'/users/'.$user->id.'/present') }}">Présent</a>
                                <a class="btn btn-xs btn-danger" href="{{ url('dashboard/perm/'.$perm->id.'/users/'.$user->id.'/absent') }}">Absent</a>
                                <form action="{{ url('dashboard/perm/'.$perm->id.'/users/'.$user->id) }}" method="post">
                                    {{ method_field('DELETE') }}
                                    <button class="btn btn-xs btn-danger" type="submit">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
