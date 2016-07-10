@extends('layouts.dashboard')

@section('title')
Mon équipe
@endsection

@section('smalltitle')
Gestion de mon équipe
@endsection

@section('content')

@if (!EtuUTT::student()->team_accepted)
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Votre participation</h3>
        </div>
        <div class="box-body">
            <div class="box-body">
                <p>
                    <strong>{{{ $team->respo->first_name }}} {{{ $team->respo->last_name }}}</strong> a proposé de vous ajouter à l'équipe. Souhaites-vous la rejoindre ?
                </p>
                <a href="{{{ route('dashboard.ce.join') }}}" class="btn btn-success form-control">Rejoindre l'équipe <strong>{{{ $team->name }}}</strong></a>
                <a href="{{{ route('dashboard.ce.unjoin') }}}" class="btn btn-danger form-control">Ne pas rejoindre l'équipe</a>
            </div>
        </div>
    </div>
@endif

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Informations générales</h3>
    </div>
    <div class="box-body">
        <div class="box-body">
            <form class="form-horizontal" action="{{ route('dashboard.students.profil') }}" method="post">
                <div class="form-group">
                    <label for="name" class="col-lg-2 control-label">Nom de l'équipe</label>
                    <div class="col-lg-10">
                        <input class="form-control" type="text" id="name" name="name" value="{{{ $team->name }}}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="col-lg-2 control-label">Description</label>
                    <div class="col-lg-10">
                        <textarea class="form-control" name="description" id="description">{{{ $team->description }}}</textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="sex" class="col-lg-2 control-label">Logo</label>
                    <div class="col-lg-10">
                        <select id="sex" name="sex" class="form-control" class="">()
                            <option value="0" @if ($student->sex == 0) selected="selected" @endif >Homme</option>
                            <option value="1" @if ($student->sex == 1) selected="selected" @endif >Femme</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="col-lg-2 control-label">Responsable</label>
                    <div class="col-lg-10 text-field">
                        {{{ $team->respo->first_name }}}
                        {{{ $team->respo->last_name }}}
                    </div>
                </div>
                <input type="submit" class="btn btn-success form-control" value="Mettre à jour les informations" />
            </form>
        </div>
    </div>
</div>

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Membres de l'équipe</h3>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover trombi">
            <tbody>
                <tr>
                    <th>Photo</th>
                    <th>Nom complet</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Labels</th>
                    <th>Actions</th>
                </tr>
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
        @if (EtuUTT::student()->student_id == $team->respo_id && $team->ce->count() < 5)
            <div class="box-body">
                    <a href="{{{ route('dashboard.ce.add') }}}" class="btn btn-success form-control">Ajouter un membre à l'équipe</a>
            </div>
        @endif
    </div>
</div>
@endsection
