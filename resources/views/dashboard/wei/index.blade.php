@extends('layouts.dashboard')

@section('title')
Week-end d'intégration
@endsection

@section('smalltitle')
Inscription et gestion des participants
@endsection

@section('content')

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Créer une nouvelle inscription</h3>
    </div>
    <div class="box-body text-center">
        <form action="{{ route('dashboard.wei.create') }}" method="post">
            <input type="text" name="first_name" value="" class="form-control text-center" placeholder="Prénom" required>
            <input type="text" name="last_name" value="" class="form-control text-center" placeholder="Nom" required>
            <input type="submit" class="btn btn-success form-control" value="Ajouter">
        </form>
    </div>
</div>

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Liste des inscriptions (<b>{{ $validated->count() }} validées</b>) et <b>{{ $validatedOrgaCount }} CE ou orgas</b></h3>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            <tbody>
                <tr>
                    <th>Nom</th>
                    <th>État</th>
                    <th>Action</th>
                </tr>
                @foreach($registrations as $registration)
                    <tr>
                        <td>{{ $registration->first_name.' '.$registration->last_name }}</td>
                        <td>
                        @if ($registration->complete)
                            <span class="label label-success">Dossier complet</span>
                        @else
                            <span class="label label-danger">Incomplet</span>
                        @endif
                        </td>
                        <td>
                            <a href="{{ route('dashboard.wei.edit', [$registration->id])}}" class="btn btn-xs btn-success">Modifier</a>
                            @if (Session::get('student_id') != 0)
                            <a href="{{ route('dashboard.wei.destroy', [$registration->id]) }}" class="btn btn-xs btn-danger">Supprimer</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


@endsection
