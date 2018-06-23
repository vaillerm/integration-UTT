@extends('layouts.dashboard')

@section('title')
    Liste des bus
@endsection

@section('smalltitle')
    Liste de tous les étudiants inscrits sur le site en tant que parrain, CE, orga assigné à un bus
@endsection

@section('content')
    <div class="callout callout-info">
        <h4>Listes des bus</h4>

        <p>
            Trop de blabla, pas assez d'action !
        </p>
        <a href="{{ url()->route('dashboard.wei.bus.generate.checklist') }}" class="btn btn-success">Générer les checkin</a>
    </div>

@foreach($buses as $bus_id => $users)
    <div class="box box-default">
        <div class="box-header with-border">
            @if(empty($bus_id))
            <h3 class="box-title">Etudiants non affecté: {{ count($users) }} étudiants</h3>
            @else
                <h3 class="box-title">Bus n°{{ $bus_id }}: {{ count($users) }} étudiants</h3>
            @endif
        </div>
        <div class="box-body table-responsive no-padding">
            @if($users)
            <table class="table table-hover trombi">
                <tbody>
                <tr>
                    <th>Nom complet</th>
                    <th>Mail</th>
                    <th>Téléphone</th>
                    <th>Labels</th>
                    <th>Actions</th>
                </tr>
                @foreach ($users as $user)
                    <tr>
                        <td>{{{ $user->first_name . ' ' . $user->last_name }}}</td>
                        <td>{{{ $user->email }}}</td>
                        <td>{{{ $user->phone }}}</td>
                        <td>
                            @if ($user->isAdmin())
                                <span class="label label-danger">Admin</span>
                            @endif
                            @if ($user->is_newcomer)
                                <span class="label label-success">Nouveau</span>
                            @endif
                            @if ($user->ce)
                                <span class="label label-primary">CE</span>
                            @endif
                            @if ($user->volunteer)
                                <span class="label label-info">Bénévole</span>
                            @endif
                            @if ($user->orga)
                                <span class="label label-warning">Orga</span>
                            @endif
                        </td>
                        <td>
                            <a class="btn btn-xs btn-warning" href="{{ route('dashboard.students.edit', [ 'id' => $user->id ])}}">Modifier</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
                @endif
        </div>
    </div>
    @endforeach
@endsection
