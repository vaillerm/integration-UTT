@extends('layouts.dashboard')

@section('title')
Équipes
@endsection

@section('smalltitle')
Gestion des équipes
@endsection

@section('content')

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Liste des équipes</h3>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            <tbody>
                <tr>
                    <th>Nombre de nouveaux</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
                @foreach ($teams as $team)
                    <tr>
                        <td>{{{ $team->newcomers()->count() }}}</td>
                        <td>{{{ $team->name }}}</td>
                        <td>{{{ $team->description }}}</td>
                        @if ($team->img)
                            <td>
                                <a href="{{ @asset('/uploads/teams-logo/'.$team->id.'.'.$team->img) }}">
                                    <img src="{{ @asset('/uploads/teams-logo/'.$team->id.'.'.$team->img) }}" style="width:100px;height:100px;" alt="Logo de l'équipe"/>
                                </a>
                            </td>
                        @else
                            <td>Aucune</td>
                        @endif
                        <td>
                            <!-- <a href="{{ route('dashboard.teams.list', ['id' => $team->id ]) }}" class="btn btn-xs btn-success">Membres</a>
                            <a href="{{ route('dashboard.teams.list', ['id' => $team->id ]) }}" class="btn btn-xs btn-warning">Modifier</a> -->
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
