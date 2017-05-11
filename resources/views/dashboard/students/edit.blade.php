@extends('layouts.dashboard')

@section('title')
Modification de profil
@endsection

@section('smalltitle')
@endsection

@section('content')

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Modification de l'étudiant <strong>{{{ $student->first_name . ' ' . $student->last_name }}}</strong></h3>
    </div>
    <div class="box-body">
        <form class="form-horizontal" action="{{ route('dashboard.students.edit.submit', $student->student_id) }}" method="post" enctype="multipart/form-data">

        <fieldset>
            <legend>Informations générales</legend>
            <div class="form-group">
                <label for="student_id" class="col-lg-2 text-right">Numéro étu</label>
                <div class="col-lg-10">
                    {{ $student->student_id }}
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-lg-2 control-label">Nom complet</label>
                <div class="col-lg-10">
                    <input class="form-control" type="text" id="name" name="name" disabled value="{{{ $student->first_name . ' ' . $student->last_name }}}">
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-lg-2 control-label">Photo</label>
                <div class="col-lg-10 text-center">
                    <img src="{{ asset('/uploads/students-trombi/'.$student->student_id.'.jpg') }}" alt="Photo"/>
                </div>
            </div>

            <div class="form-group">
                <label for="surname" class="col-lg-2 control-label">Surnom</label>
                <div class="col-lg-10">
                    <input class="form-control" type="text" name="surname" id="surname" value="{{{ old('surname') ?? $student->surname }}}">
                </div>
            </div>

            <div class="form-group">
                <label for="sex" class="col-lg-2 control-label">Sexe</label>
                <div class="col-lg-10">
                    <select id="sex" name="sex" class="form-control" class="">()
                        <option value="0" @if ((old('sex') ?? $student->sex) == 0) selected="selected" @endif >Homme</option>
                        <option value="1" @if ((old('sex') ?? $student->sex) == 1) selected="selected" @endif >Femme</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="email" class="col-lg-2 control-label">Email</label>
                <div class="col-lg-10">
                    <input class="form-control" name="email" id="email" placeholder="Adresse email" type="text" value="{{{ old('email') ?? $student->email }}}">
                </div>
            </div>

            <div class="form-group">
                <label for="phone" class="col-lg-2 control-label">Portable</label>
                <div class="col-lg-10">
                    <input class="form-control" name="phone" id="phone" placeholder="Portable" type="text" value="{{{ old('phone') ?? $student->phone }}}">
                </div>
            </div>

            <div class="form-group">
                <label for="branch" class="col-lg-2 control-label">Branche</label>
                <div class="col-lg-10">
                    <input class="form-control" name="branch" id="branch" type="text" value="{{{ old('branch') ?? $student->branch }}}">
                </div>
            </div>

            <div class="form-group">
                <label for="level" class="col-lg-2 control-label">Niveau de branche</label>
                <div class="col-lg-10">
                    <input class="form-control" name="level" id="level" type="text" value="{{{ old('level') ?? $student->level }}}">
                </div>
            </div>

            <div class="form-group">
                <label for="volunteer" class="col-lg-2 text-right">A signé la charte</label>
                <div class="col-lg-10">
                    <input type="checkbox" id="volunteer" name="volunteer" @if ($student->volunteer == 1) checked="checked" @endif disabled/>
                </div>
            </div>


        </fieldset>
        <fieldset>
            <legend id="parrainage">Parrainage</legend>

                <div class="form-group">
                    <label for="referral" class="col-lg-2 text-right">Parrain/Marraine</label>
                    <div class="col-lg-10">
                        <input type="checkbox" id="referral" name="referral" @if (old('referral') ?? ($student->referral == 1)) checked="checked" @endif/>
                    </div>
                </div>

                <div class="form-group">
                    <label for="facebook" class="col-lg-2 control-label">Profil facebook</label>
                    <div class="col-lg-10">
                        <input class="form-control" type="text" name="facebook" id="facebook" placeholder="https://facebook.com/ton.profil" value="{{{ old('facebook') ?? $student->facebook }}}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="city" class="col-lg-2 control-label">Ville d'origine</label>
                    <div class="col-lg-10">
                        <input class="form-control" name="city" id="city" placeholder="Ville d'origine" type="text" value="{{{ old('city') ?? $student->city }}}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="postal_code" class="col-lg-2 control-label">Code postal de ta ville d'origine (met 0 si tu viens de l'étranger)</label>
                    <div class="col-lg-10">
                        <input class="form-control" name="postal_code" id="postal_code" placeholder="Code postal" type="text" value="{{{ old('postal_code') ?? $student->postal_code }}}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="country" class="col-lg-2 control-label">Pays d'origine</label>
                    <div class="col-lg-10">
                        <input class="form-control" name="country" id="country" placeholder="Pays d'origine" type="text" value="{{{ old('country') ?? $student->country }}}">
                    </div>
                </div>

                    <div class="form-group">
                        <label for="referral_max" class="col-lg-2 control-label">Nombre de fillots maximum</label>
                        <div class="col-lg-10">
                            <select id="referral_max" name="referral_max" class="form-control" class="">
                                @foreach (range(1, 5) as $i)
                                    @if ($i == (old('referral_max') ?? $student->referral_max)) <option value="{{ $i }}" selected="selected">
                                    @else <option value="{{ $i }}">
                                    @endif
                                    {{ $i }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="referral_text" class="col-lg-2 control-label">Message pour ton fillot<br>(140 caractères minimum)</label>
                        <div class="col-lg-10">
                            <textarea class="form-control" rows="5" id="referral_text" name="referral_text">{{{ old('referral_text') ?? $student->referral_text }}}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="referral_validated" class="col-lg-2 text-right">Profil parrain validé</label>
                        <div class="col-lg-10">
                            <input type="checkbox" id="referral_validated" name="referral_validated" @if (old('referral_validated') ?? ($student->referral_validated == 1)) checked="checked" @endif/>
                        </div>
                    </div>

            </fieldset>
            <fieldset>
                <legend>Chef d'équipe</legend>
                <div class="form-group">
                    <label for="ce" class="col-lg-2 text-right">Est chef d'équipe</label>
                    <div class="col-lg-10">
                        <input type="checkbox" id="ce" name="ce" @if (old('ce') ?? ($student->ce == 1)) checked="checked" @endif disabled/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="team_accepted" class="col-lg-2 text-right">A accepté de rejoindre l'équipe</label>
                    <div class="col-lg-10">
                        <input type="checkbox" id="team_accepted" name="team_accepted" @if (old('team_accepted') ?? ($student->team_accepted == 1)) checked="checked" @endif disabled/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="ce" class="col-lg-2 text-right">Équipe</label>
                    <div class="col-lg-10">
                        @if ($student->team)
                            {{ $student->team->name }}<br/>
                            <a href="{{ route('dashboard.teams.list') . '#' . $student->team_id }}" class="btn btn-xs btn-success">Liste</a>
                            <a href="{{ route('dashboard.teams.edit', ['id' => $student->team_id ]) }}" class="btn btn-xs btn-warning">Modifier</a>
                        @else
                            <em>Aucune</em>
                        @endif
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <legend>Droits</legend>
                <div class="form-group">
                    <label for="admin" class="col-lg-2 text-right">Admin</label>
                    <div class="col-lg-10">
                        <input type="checkbox" id="admin" name="admin" @if (old('admin') ?? ($student->admin == 100)) checked="checked" @endif/><br/>
                        <small class="text-muted">Il est conseillé de donner le droit `organisateur` aux administrateur. Ils sont complémentaires.</small>
                    </div>
                </div>
                <div class="form-group">
                    <label for="orga" class="col-lg-2 text-right">Organisateur</label>
                    <div class="col-lg-10">
                        <input type="checkbox" id="orga" name="orga" @if (old('orga') ?? ($student->orga == 1)) checked="checked" @endif/><br/>
                        <small class="text-muted">Une fois dans cette catégorie, l'utilisateur est ajouté à <em>integration-liste</em>.<br/>
                            Et il peut donner des commentaires sur les bénévoles (bonne ou mauvaise action) pour encourager ou décourager un éventuel départ au WEI.
                            </small>
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <legend>Dates</legend>
                <div class="form-group">
                    <label for="admin" class="col-lg-2 text-right">Inscription</label>
                    <div class="col-lg-10">
                        {{ $student->last_login }}
                    </div>
                </div>
                <div class="form-group">
                    <label for="admin" class="col-lg-2 text-right">Dernière modification</label>
                    <div class="col-lg-10">
                        {{ $student->updated_at }}
                    </div>
                </div>
                <div class="form-group">
                    <label for="admin" class="col-lg-2 text-right">Dernière connexion</label>
                    <div class="col-lg-10">
                        {{ $student->created_at }}
                    </div>
                </div>
            </fieldset>
            <input type="submit" class="btn btn-success form-control" value="Mettre à jour les informations" />
        </form>
    </div>
</div>
@endsection
