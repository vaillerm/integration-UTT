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
            <form class="form-horizontal" action="{{ route('dashboard.ce.myteam') }}" method="post" enctype="multipart/form-data">
                @if($team->respo_id != EtuUTT::student()->student_id)
                    <p class="text-center">
                        Seul le responsable de l'équipe peut modifier les informations de l'équipe.
                    </p>
                @endif
                <div class="form-group">
                    <label for="name" class="col-lg-2 control-label">Nom de l'équipe</label>
                    <div class="col-lg-10">
                        <input class="form-control" type="text" id="name" name="name" @if($team->respo_id != EtuUTT::student()->student_id) disabled @endif value="{{{ $team->name }}}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="col-lg-2 control-label">Mot de votre équipe</label>
                    <div class="col-lg-10">
                        <textarea class="form-control" name="description" id="description" placeholder="Bienvenue dans notre équipe..." @if($team->respo_id != EtuUTT::student()->student_id) disabled @endif>{{{ $team->description }}}</textarea>
                        <small class="text-muted">Utilisez ce mot pour souhaiter la bienvenue aux nouveaux dans votre équipe et donner des idées de déguisements.
                            <br/>Ecrivez entre 100 et 200 caractères.
                            Ce message est soumis à validation d'un modérateur.</small>
                    </div>
                </div>


                <fieldset class="form-group">
                    <label for="img" class="col-lg-2 control-label">Logo de votre équipe</label>
                    <div class="col-lg-10">
                        <input type="file" class="form-control-file" id="img" name="img" @if($team->respo_id != EtuUTT::student()->student_id) disabled @endif>
                        <small class="text-muted">Image de 200x200 pixels représentant le thème de votre équipe.</small>
                        @if ($team->img)
                            <div class="text-center">
                                <img src="{{ @asset('/uploads/teams-logo/'.$team->id.'.'.$team->img) }}" style="width:200px;height:200px;" alt="Logo de l'équipe"/>
                            </div>
                        @endif
                    </div>
                </fieldset>

                <div class="form-group">
                    <label for="email" class="col-lg-2 control-label">Responsable</label>
                    <div class="col-lg-10 text-field">
                        @if ($team->respo)
                            {{{ $team->respo->first_name }}}
                            {{{ $team->respo->last_name }}}
                        @endif
                    </div>
                </div>
                @if($team->respo_id == EtuUTT::student()->student_id)
                    <input type="submit" class="btn btn-success form-control" value="Mettre à jour les informations" />
                @endif
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