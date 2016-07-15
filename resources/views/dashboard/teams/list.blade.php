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
        <h3 class="box-title">Liste des équipes ({{ $teams->count() }})</h3>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            <tbody>
                <tr>
                    <th>Nombre de nouveaux</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Image</th>
                    {{-- <th>Action</th> --}}
                </tr>
                @foreach ($teams as $team)
                    <tr>
                        <td>{{{ $team->newcomers()->count() }}}</td>
                        <td>{{{ $team->name }}}</td>
                        <td>
                            @if ($team->description)
                                <p>
                                    {{{ $team->description }}}
                                </p>
                            @else
                                <p>
                                    <em>Pas encore de description.</em>
                                </p>
                            @endif
                            <table class="table trombi table-hover">
                                <tbody>
                                    @foreach ($team->ce as $student)
                                        <tr>
                                            <td><a href="{{ @asset('/uploads/students-trombi/'.$student->student_id.'.jpg') }}"><img src="{{ @asset('/uploads/students-trombi/'.$student->student_id.'.jpg') }}" alt="Photo"/></a></td>
                                            <td>{{{ $student->first_name . ' ' . $student->last_name }}}</td>
                                            <td>{{{ $student->email }}}</td>
                                            <td>{{{ $student->phone }}}</td>
                                            <td>
                                                @if ($student->student_id == $team->respo_id)
                                                    <span class="label label-primary" title="Responsable de l'équipe"><i class="fa fa-star" aria-hidden="true"></i> Respo</span>
                                                @endif
                                                @if ($student->team_accepted)
                                                    <span class="label label-success" title="A validé sa participation à l'équipe"><i class="fa fa-check-circle" aria-hidden="true"></i> Accepté</span>
                                                @else
                                                    <span class="label label-warning" title="N'a pas encore validé sa participation à l'équipe"><i class="fa fa-question-circle" aria-hidden="true"></i> En attente</span>
                                                @endif
                                            </td>
                                            <td>
                                                <!-- <a class="btn btn-xs btn-danger" href="{{ route('dashboard.students.list')}}">Supprimer</a> -->
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                        @if ($team->img)
                            <td>
                                <a href="{{ @asset('/uploads/teams-logo/'.$team->id.'.'.$team->img) }}">
                                    <img src="{{ @asset('/uploads/teams-logo/'.$team->id.'.'.$team->img) }}" style="width:100px;height:100px;" alt="Logo de l'équipe"/>
                                </a>
                            </td>
                        @else
                            <td>
                                <em>Aucune</em>
                            </td>
                        @endif
                        {{-- <td> --}}
                            <!-- <a href="{{ route('dashboard.teams.list', ['id' => $team->id ]) }}" class="btn btn-xs btn-success">Membres</a>
                            <a href="{{ route('dashboard.teams.list', ['id' => $team->id ]) }}" class="btn btn-xs btn-warning">Modifier</a> -->
                        {{-- </td> --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
