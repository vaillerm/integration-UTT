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
            @if(Authorization::can('ce','edit'))
                <div class="box-countdown">Fermeture dans {{ @countdown(Authorization::countdown('ce','edit')) }}</div>
            @else
                <div class="box-countdown">Les modifications d'équipe ne sont plus autorisés.</div>
            @endif
            <h3 class="box-title">Votre participation</h3>
        </div>
        <div class="box-body">
            <div class="box-body">
                <p>
                    <strong></strong> a proposé de vous ajouter à l'équipe. Souhaites-vous la rejoindre ?
                </p>
                <a href="{{{ route('dashboard.ce.join') }}}" class="btn btn-success form-control">Rejoindre l'équipe
                    @if($team->name != null)
                    <strong>{{{ $team->name }}}</strong></a>
                    @else
                    <strong>Équipe sans nom {{{ $team->id }}}</strong></a>
                    @endif
                <a href="{{{ route('dashboard.ce.unjoin') }}}" class="btn btn-danger form-control">Ne pas rejoindre l'équipe</a>
            </div>
        </div>
    </div>
@endif

@if (EtuUTT::student()->team->validated)
    <div class="callout callout-success">
        <h4>Vous avez été approuvé</h4>
        <p>
            Un administrateur a validé votre équipe.
            @if (Authorization::can('ce','edit'))
                Mais vous pouvez toujours modifier votre équipe si vous le souhaitez.
            @endif
        </p>
    </div>
@endif

{{-- @if(count($newcomers)>0)
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Nouveaux assignés a l'équipe</h3>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover trombi">
                <tbody>
                <tr>
                    <th>Prénom</th>
                    <th>Nom</th>
                </tr>
                @foreach ($newcomers as $newcomer)
                    <tr>
                        <td>{{{ $newcomer->first_name }}}</td>
                        <td>{{{ $newcomer->last_name }}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
--}}

<div class="box box-default">
    <div class="box-header with-border">
        @if(Authorization::can('ce','edit'))
            <div class="box-countdown">Fermeture dans {{ @countdown(Authorization::countdown('ce','edit')) }}</div>
        @else
            <div class="box-countdown">Les modifications d'équipe ne sont plus autorisées.</div>
        @endif
        <h3 class="box-title">Membres de l'équipe</h3>
    </div>
    <div class="box-body table-responsive no-padding">
        <div class="box-body text-center">
            Seul le responsable de l'équipe peut ajouter des membres à l'équipe.<br/>
            Les membres ajoutés doivent ensuite se connecter au site de l'intégration pour valider leurs participation.<br/>
        </div>
        <table class="table table-hover trombi">
            <tbody>
                <tr>
                    <th>Photo</th>
                    <th>Nom complet</th>
                    <th>Mail</th>
                    <th>Téléphone</th>
                    <th>Labels</th>
                </tr>
                @foreach ($team->ce as $student)
                    <tr>
                        <td><a href="{{ asset('/uploads/students-trombi/'.$student->student_id.'.jpg') }}"><img src="{{ asset('/uploads/students-trombi/'.$student->student_id.'.jpg') }}" alt="Photo"/></a></td>
                        <td>{{{ $student->first_name . ' ' . $student->last_name }}}</td>
                        <td>{{{ $student->email }}}</td>
                        <td>{{{ $student->phone }}}</td>
                        <td>
                            @if ($student->id == $team->respo_id)
                                <span class="label label-primary" title="Responsable de l'équipe"><i class="fa fa-star" aria-hidden="true"></i> Respo</span>
                            @endif
                            @if ($student->team_accepted)
                                <span class="label label-success" title="A validé sa participation à l'équipe"><i class="fa fa-check-circle" aria-hidden="true"></i> Accepté</span>
                            @else
                                <span class="label label-warning" title="N'a pas encore validé sa participation à l'équipe"><i class="fa fa-question-circle" aria-hidden="true"></i> En attente</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if (Auth::user()->id == $team->respo_id && $team->ce->count() < 5)
            <div class="box-body">
                    <a href="{{{ route('dashboard.ce.add') }}}" class="btn btn-success form-control">Ajouter un membre à l'équipe</a>
            </div>
        @endif
    </div>
</div>

<div class="box box-default">
    <div class="box-header with-border">
        @if(Authorization::can('ce','editName'))
            <div class="box-countdown">Fermeture dans {{ @countdown(Authorization::countdown('ce','editName')) }}</div>
        @elseif (Authorization::countdown('ce','editName'))
            <div class="box-countdown">Vous pourrez modifier les informations d'équipe dans {{ @countdown(Authorization::countdown('ce','editName')) }}</div>
        @else
            <div class="box-countdown">Les modifications d'équipe ne sont plus autorisées.</div>
        @endif

        <h3 class="box-title">Informations générales</h3>
    </div>
    <div class="box-body">
        <div class="box-body">
            <form class="form-horizontal" action="{{ route('dashboard.ce.myteam') }}" method="post" enctype="multipart/form-data">
                @if($team->respo_id != Auth::user()->id)
                    <p class="text-center">
                        Seul le responsable de l'équipe peut modifier les informations de l'équipe.
                    </p>
                @endif

                @if (!empty(Config::get('services.theme')) || ($team->faction && !empty($team->faction->name)))
                    <p class="text-center">
                        @if (!empty(Config::get('services.theme')))
                            Cette année le thème de l'Intégration c'est <strong>{{ Config::get('services.theme') }}</strong>.<br/>
                        @endif
                        @if ($team->faction && !empty($team->faction->name))
                            Votre équipe est dans la faction <strong>{{ $team->faction->name }}</strong>.<br/>
                        @endif
                        Choisissez votre nom d'équipe en conséquence !
                    </p>
                @endif
                <div class="form-group">
                    <label for="name" class="col-lg-2 control-label">Nom de l'équipe</label>
                    <div class="col-lg-10">
                        <input class="form-control" type="text" id="name" name="name" @if(!Authorization::can('ce','editName')) disabled @endif value="{{{ old('name') ?? $team->name }}}"
                        @if(!Authorization::can('ce','editName'))
                        placeholder="Vous pourrez changer le nom de votre équipe une fois que le thème de l'inté sera établit"
                        @endif>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="col-lg-2 control-label">Mot de votre équipe</label>
                    <div class="col-lg-10">
                        <textarea class="form-control" name="description" id="description" @if(!Authorization::can('ce','editName')) disabled @endif placeholder="Bienvenue dans notre équipe..."  @if(!Authorization::can('ce','edit')) disabled @endif>{{{ old('description') ?? $team->description }}}</textarea>
                        <small class="text-muted">Utilisez ce mot pour souhaiter la bienvenue aux nouveaux dans votre équipe et donner des idées de déguisements.
                            <br/>Ecrivez minimum 250 caractères.
                            Ce message est soumis à validation d'un modérateur.</small>
                    </div>
                </div>


                <fieldset class="form-group">
                    <label for="img" class="col-lg-2 control-label">Logo de votre équipe</label>
                    <div class="col-lg-10">
                        <input type="file" class="form-control-file" id="img" name="img" @if(!Authorization::can('ce','editName')) disabled @endif  @if(!Authorization::can('ce','edit')) disabled @endif>
                        <small class="text-muted">Image de 200x200 pixels représentant le thème de votre équipe.</small>
                        @if ($team->img)
                            <div class="text-center">
                                <img src="{{ asset('/uploads/teams-logo/'.$team->id.'.'.$team->img) }}" style="width:200px;height:200px;" alt="Logo de l'équipe"/>
                            </div>
                        @endif
                    </div>
                </fieldset>

                <div class="form-group">
                    <label for="facebook" class="col-lg-2 control-label">Lien vers le groupe Facebook</label>
                    <div class="col-lg-10">
                        <input class="form-control" type="text" id="facebook" name="facebook" @if(!Authorization::can('ce','editName')) disabled @endif placeholder="https://facebook.com/.." @if(!Authorization::can('ce','edit')) disabled @endif value="{{{ old('facebook') ?? $team->facebook }}}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="col-lg-2 control-label">Responsable</label>
                    <div class="col-lg-10 text-field">
                        @if ($team->respo)
                            {{{ $team->respo->first_name }}}
                            {{{ $team->respo->last_name }}}
                        @endif
                    </div>
                </div>
                @if(Authorization::can('ce','edit'))
                    <input type="submit" class="btn btn-success form-control" value="Mettre à jour les informations" @if(!Authorization::can('ce','editName')) disabled @endif />
                @endif
            </form>
        </div>
    </div>
</div>
@endsection
