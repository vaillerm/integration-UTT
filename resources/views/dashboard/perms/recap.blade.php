@extends('layouts.dashboard')

@section('title')
    Récapitulatif
@endsection

@section('smalltitle')
    Liste de toutes les contributions des permanenciers.
@endsection

@section('content')

    <div class="callout callout-info">
        <h4>Liste de toutes les contributions des permanenciers.</h4>
        <p>
            Une permanence a TODO
        </p>
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
                        <th>Points</th>
                        <th>Absences</th>
                        <th>Nombre de perms</th>
                        <th>Actions</th>
                    </tr>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->last_name }}</td>
                            <td>{{ $user->first_name }}</td>
                            <td>{{ $user->points() }}</td>
                            <td>{{ $user->absences->count() }}</td>
                            <td>{{ $user->perms->count() }}</td>
                            <td><a class="btn btn-xs btn-info" href="{{ url('dashboard/user/'.$user->id.'/perms') }}">Liste des perms</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
