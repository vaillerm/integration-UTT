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
        <h3 class="box-title">Modification de l'équipe
            @if($team->name != null)
            <strong>{{{ $team->name }}}</strong></a>
            @else
            <strong>Équipe sans nom {{{ $team->id }}}</strong></a>
            @endif
        </h3>
    </div>
    <div class="box-body text-center">
        <form class="form-horizontal" action="{{ route('dashboard.teams.edit.submit', $team->id) }}" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name" class="col-lg-2 control-label">Nom de l'équipe</label>
                <div class="col-lg-10">
                    <input class="form-control" type="text" id="name" name="name" value="{{{ old('name') ?? $team->name }}}">
                </div>
            </div>

            <div class="form-group">
                <label for="safe_name" class="col-lg-2 control-label">Nom gentil</label>
                <div class="col-lg-10">
                    <input class="form-control" type="text" id="safe_name" name="safe_name" value="{{{ old('safe_name') ?? $team->safe_name }}}">
                    <small class="text-muted">On ne peut pas envoyer un nom d'équipe trop sale aux parents, on envoie donc une version moins choquante. Vous pouvez aussi profiter de ce nom pour enlever les jeux de mots qui le rendrait difficile à comprendre.</small>
                </div>
            </div>

            <div class="form-group">
                <label for="description" class="col-lg-2 control-label">Mot de votre équipe</label>
                <div class="col-lg-10">
                    <textarea class="form-control" name="description" id="description" placeholder="Bienvenue dans notre équipe...">{{{ old('description') ?? $team->description }}}</textarea>
                    <small class="text-muted">Utilisez ce mot pour souhaiter la bienvenue aux nouveaux dans votre équipe et donner des idées de déguisements.
                        <br/>Ecrivez entre 100 et 200 caractères.
                        Ce message est soumis à validation d'un modérateur.</small>
                </div>
            </div>


            <fieldset class="form-group">
                <label for="img" class="col-lg-2 control-label">Logo de votre équipe</label>
                <div class="col-lg-10">
                    <input type="file" class="form-control-file" id="img" name="img">
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
                    <input class="form-control" type="text" id="facebook" name="facebook" placeholder="https://facebook.com/.." value="{{{ old('facebook') ?? $team->facebook }}}">
                </div>
            </div>

            <div class="form-group">
                <label for="branch" class="col-lg-2 control-label">Branche</label>
                <div class="col-lg-10">
                    <input class="form-control" type="text" id="branch" name="branch" placeholder="TC,SM,MM,.." value="{{{ old('branch') ?? $team->branch }}}">
                    <small class="text-muted">Si une branche est precisée, tous les nouveaux de cette branche seront dans cette équipe. Laissez vide pour laisser l'aléatoire faire son travail.</small>
                </div>
            </div>


            <div class="form-group">
                <label for="faction" class="col-lg-2 control-label">Faction</label>
                <div class="col-lg-10">
                  <select class="form-control" id="faction" name="faction" value="{{{ old('faction') ?? $team->faction }}}">
                    @foreach ($factions as $faction)
                      <option value="{{{ $faction->id }}}">{{{ $faction->name }}}</option>
                    @endforeach
                  </select>
                </div>
            </div>

            <div class="form-group">
                <label for="comment" class="col-lg-2 control-label">Commentaire administrateur</label>
                <div class="col-lg-10">
                    <textarea class="form-control" name="comment" id="comment" placeholder="Qui a répondu quand les CE ont été appelés ? Eventuelles conneries ou trucs bien qu\'ils ont fait, etc.">{{{ old('comment') ?? $team->comment }}}</textarea>
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

            <div class="form-group">
                <label for="faction" class="col-lg-2 control-label">Faction</label>
                <div class="col-lg-10">
                    <select id="faction" name="faction" class="form-control">
                        @foreach($factions as $faction)
                            <option value="{{{$faction->id}}}" @if ($team->faction == $faction) selected="selected" @endif >{{{$faction->name}}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <input type="submit" class="btn btn-success form-control" value="Mettre à jour les informations" />
        </form>
    </div>
</div>
@endsection
