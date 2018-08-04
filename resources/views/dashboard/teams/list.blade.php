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
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Commentaire</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
                @foreach ($teams as $team)
                    @if ($team->validated)
                        <tr class="success" id="{{ $team->id }}">
                    @else
                        <tr id="{{ $team->id }}">
                    @endif
                        <td>
                            @if($team->name != null)
                                <strong>{{{ $team->name }}}</strong>
                            @else
                                <strong>Équipe sans nom {{{ $team->id }}}</strong>
                            @endif
                            @if($team->safe_name != null)
                                <br/><em>{{{ $team->safe_name }}}</em>
                            @endif
                            @if($team->faction_id)
                                <br/>({{{ $team->faction->name }}})
                            @endif
                            <br/><a href="{{ route('dashboard.teams.members', ['id' => $team->id ]) }}" class="btn btn-sm btn-default">{{{ $team->newcomers()->count() }}} {{ $team->branch != null ? $team->branch : 'Branches' }}</a>
                        </td>
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
                                            <td class="text-center">
                                                <a href="{{ asset('/uploads/students-trombi/'.$student->student_id.'.jpg') }}"><img src="{{ asset('/uploads/students-trombi/'.$student->student_id.'.jpg') }}" alt="Photo"/></a><br/>
                                                {{{ $student->first_name . ' ' . $student->last_name }}}
                                            </td>
                                            <td></td>
                                            <td>
                                                {{{ $student->email }}}<br/>
                                                {{{ $student->phone }}}<br/>
                                                {{{ $student->branch }}}{{{ $student->level }}}
                                            </td>
                                            <td>
                                                @if ($student->id == $team->respo_id)
                                                    <span class="label label-primary" title="Responsable de l'équipe"><i class="fa fa-star" aria-hidden="true"></i></span>
                                                @endif
                                                @if ($student->team_accepted)
                                                    <span class="label label-success" title="A validé sa participation à l'équipe"><i class="fa fa-check-circle" aria-hidden="true"></i></span>
                                                @else
                                                    <span class="label label-warning" title="N'a pas encore validé sa participation à l'équipe"><i class="fa fa-question-circle" aria-hidden="true"></i></span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                        <td>{{{ $team->comment }}}</td>
                        @if ($team->img)
                            <td>
                                <a href="{{ asset('/uploads/teams-logo/'.$team->id.'.'.$team->img) }}">
                                    <img src="{{ asset('/uploads/teams-logo/'.$team->id.'.'.$team->img) }}" style="width:100px;height:100px;" alt="Logo de l'équipe"/>
                                </a>
                            </td>
                        @else
                            <td>
                                <em>Aucune</em>
                            </td>
                        @endif
                        <td>
                            @if ($team->facebook)
                                <a href="{{ $team->facebook }}" class="btn btn-xs btn-primary">
                                    <i class="fa fa-facebook" aria-hidden="true"></i>acebook
                                </a>
                            @else
                                <span class="btn btn-xs btn-primary disabled" title="Cette équipe n'a pas envoyé le lien de son groupe Facebook">
                                    <del><i class="fa fa-facebook" aria-hidden="true"></i>acebook</del>
                                </span>
                            @endif
                            @if (!$team->validated)
                                <a href="{{ route('dashboard.teams.validate', ['id' => $team->id ]) }}" class="btn btn-xs btn-success">Approuver</a>
                            @else
                                <a href="{{ route('dashboard.teams.unvalidate', ['id' => $team->id ]) }}" class="btn btn-xs btn-danger">Désapprouver</a>
                            @endif
                            <a href="{{ route('dashboard.teams.edit', ['id' => $team->id ]) }}" class="btn btn-xs btn-warning">Modifier</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
