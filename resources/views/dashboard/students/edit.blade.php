@extends('layouts.dashboard')

@section('title')
Modification de profil
@endsection

@section('smalltitle')
@endsection

@section('content')

<div class="box box-default">
    <div class="box-header with-border">
        @if ($student->is_newcomer)
            <h3 class="box-title">Modification du nouveau <strong>{{{ $student->first_name . ' ' . $student->last_name }}}</strong></h3>
        @else
            <h3 class="box-title">Modification de l'étudiant <strong>{{{ $student->first_name . ' ' . $student->last_name }}}</strong></h3>
        @endif
        @if ($student->admitted_id)
            <a href="{{ route('dashboard.newcomers.unsync', ['id' => $student->id ]) }}" class="btn btn-xs btn-danger pull-right"
                title="Si le nouveau ne vient finalement pas à l'utt, vous pouver désactiver le compte afin qu'il ne soit plus récupéré automatiquement depuis l'UTT">
                Désactiver définitivement
            </a>
        @endif
    </div>
    <div class="box-body">
        <form class="form-horizontal" action="{{ route('dashboard.students.edit.submit', $student->id) }}" method="post" enctype="multipart/form-data">

        <fieldset>
            <legend>Informations générales</legend>

            @if (!$student->is_newcomer)
                <div class="form-group">
                    <label for="is_newcomer" class="col-lg-2 text-right">Status</label>
                    <div class="col-lg-10">
                        Ancien
                    </div>
                </div>
                <div class="form-group">
                    <label for="student_id" class="col-lg-2 text-right">Numéro étu</label>
                    <div class="col-lg-10">
                        {{ $student->student_id }}
                    </div>
                </div>
                <div class="form-group">
                    <label for="password" class="col-lg-2 control-label">Mot de passe</label>
                    <div class="col-lg-10">
                        <input class="form-control" type="password" name="password" id="password" value="{{{ old('password') }}}" placeholder="{{ $student->password ? 'Mot de passe caché' : 'Aucun mot de passe'}}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="password_confirmation" class="col-lg-2 control-label">Confirmation</label>
                    <div class="col-lg-10">
                        <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" value="{{{ old('password_confirmation') }}}">
                    </div>
                </div>
            @else
                <div class="form-group">
                    <label for="is_newcomer" class="col-lg-2 text-right">Status</label>
                    <div class="col-lg-10">
                        Nouveau
                    </div>
                </div>
                <div class="form-group">
                    <label for="login" class="col-lg-2 text-right">Login</label>
                    <div class="col-lg-10">
                        {{ $student->login }}
                    </div>
                </div>
            @endif
            <div class="form-group">
                <label for="name" class="col-lg-2 control-label">Nom complet</label>
                <div class="col-lg-10">
                    {{ $student->first_name . ' ' . $student->last_name }}
                </div>
            </div>

            @if ($student->is_newcomer)
                <div class="form-group">
                    <label for="name" class="col-lg-2 control-label">Majorité au wei</label>
                    <div class="col-lg-10">
                        {{ $student->wei_majority ? 'Majeur' : 'Mineur' }}
                    </div>
                </div>
            @endif

            @if (!$student->is_newcomer)
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
            @endif

            <div class="form-group">
                <label for="email" class="col-lg-2 control-label">Mail</label>
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

            @if (!$student->is_newcomer)
                <div class="form-group">
                    <label for="level" class="col-lg-2 control-label">Niveau de branche</label>
                    <div class="col-lg-10">
                        <input class="form-control" name="level" id="level" type="text" value="{{{ old('level') ?? $student->level }}}">
                    </div>
                </div>
            @else

                <div class="form-group">
                    <label for="ce" class="col-lg-2 text-right">Numéro etu parrain</label>
                    <div class="col-lg-10">
                        {{ $student->referral_id }}
                    </div>
                </div>

                <div class="form-group">
                    <label for="parent_name" class="col-lg-2 text-right">Nom du parent</label>
                    <div class="col-lg-10">
                        <input class="form-control" name="parent_name" id="parent_name" type="text" value="{{{ old('parent_name') ?? $student->parent_name }}}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="parent_phone" class="col-lg-2 text-right">Numéro du parent</label>
                    <div class="col-lg-10">
                        <input class="form-control" name="parent_phone" id="parent_phone" type="text" value="{{{ old('parent_phone') ?? $student->parent_phone }}}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="allow_publicity" class="col-lg-2 text-right">Accepte de recevoir la publicité</label>
                    <div class="col-lg-10">
                        <input type="checkbox" id="allow_publicity" name="allow_publicity" @if (old('allow_publicity') ?? ($student->allow_publicity == 1)) checked="checked" @endif/>
                    </div>
                </div>

                <div class="form-group">
                    <label for="registration_email" class="col-lg-2 text-right">Email Post-Bac/3uT</label>
                    <div class="col-lg-10">
                        <input class="form-control" name="registration_email" id="registration_email" type="text" value="{{{ old('registration_email') ?? $student->registration_email }}}" disabled>
                    </div>
                </div>

                <div class="form-group">
                    <label for="registration_phone" class="col-lg-2 text-right">Téléphone fix Post-Bac/3uT</label>
                    <div class="col-lg-10">
                        <input class="form-control" name="registration_phone" id="registration_phone" type="text" value="{{{ old('registration_phone') ?? $student->registration_phone }}}" disabled>
                    </div>
                </div>

            @endif
        </fieldset>

        @if (!$student->is_newcomer)
        <fieldset>
            <legend>Bénévole</legend>
            <div class="form-group">
                <label for="volunteer" class="col-lg-2 text-right">A signé la charte</label>
                <div class="col-lg-10">
                    <input type="checkbox" id="volunteer" name="volunteer" @if ($student->volunteer == 1) checked="checked" @endif disabled/>
                </div>
            </div>

            <div class="form-group">
                <label for="mission" class="col-lg-2 control-label">Mission pour l'intégration</label>
                <div class="col-lg-10">
                    <input class="form-control" name="mission" id="mission" placeholder="Mission" type="text" value="{{ old('mission') ?? $student->mission }}">
                </div>
            </div>

            <div class="form-group">
                <label class="col-lg-2 control-label">Préférences</label>
                <div class="col-lg-10">
                    @foreach (\App\Models\User::VOLUNTEER_PREFERENCES as $key => $preference)
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="volunteer_preferences[{{{ $key }}}]" @if (( old('volunteer_preferences.'.$key) ?? in_array($key, $student->volunteer_preferences ?? []) )) checked="checked" @endif/>
                            <strong>{{{ $preference['title'] }}}</strong><span class="hidden-xs"> : {{{ $preference['description'] }}}</span>
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
        </fieldset>
        @endif

        @if (!$student->is_newcomer)
            <fieldset>
                <legend id="parrainage">Parrainage</legend>

                    <div class="form-group">
                        <label for="referral" class="col-lg-2 text-right">Parrain/Marraine</label>
                        <div class="col-lg-10">
                            <input type="checkbox" id="referral" name="referral" @if (old('referral') ?? ($student->referral == 1)) checked="checked" @endif/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="facebook" class="col-lg-2 control-label">Profil Facebook</label>
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
                        <label for="postal_code" class="col-lg-2 control-label">Code postal (met 0 si tu viens de l'étranger)</label>
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
                            <input class="form-control" name="referral_max" id="referral_max" type="number" value="{{{ old('referral_max') ?? $student->referral_max }}}">
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
            @else
                <fieldset>
                <legend id="parrainage">Origine</legend>
                    <div class="form-group">
                        <label for="city" class="col-lg-2 control-label">Ville d'origine</label>
                        <div class="col-lg-10">
                            <input class="form-control" name="city" id="city" placeholder="Ville d'origine" type="text" value="{{{ old('city') ?? $student->city }}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="postal_code" class="col-lg-2 control-label">Code postal (met 0 si tu viens de l'étranger)</label>
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
                </fieldset>
            @endif

            <fieldset>
            <legend id="parrainage">Medical</legend>
                <div class="form-group">
                    <label for="medical_allergies" class="col-lg-2 control-label">Allergies</label>
                    <div class="col-lg-10">
                        <textarea  class="form-control" name="medical_allergies" id="medical_allergies">{{{ old('medical_allergies') ?? $student->medical_allergies }}}</textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="medical_treatment" class="col-lg-2 control-label">Traitement medical</label>
                    <div class="col-lg-10">
                        <textarea  class="form-control" name="medical_treatment" id="medical_treatment">{{{ old('medical_treatment') ?? $student->medical_treatment }}}</textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="medical_note" class="col-lg-2 control-label">Note complémentaire</label>
                    <div class="col-lg-10">
                        <textarea  class="form-control" name="medical_note" id="medical_note">{{{ old('medical_note') ?? $student->medical_note }}}</textarea>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend>Équipe</legend>
                @if (!$student->is_newcomer)
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
                @endif
                <div class="form-group">
                    <label for="ce" class="col-lg-2 text-right">Équipe</label>
                    <div class="col-lg-10">
                        @if ($student->team)
                            @if($student->team->name != null)
                                {{ $student->team->name }}<br/>
                            @else
                                Équipe sans nom {{ $student->team->id }}<br/>
                            @endif
                            <a href="{{ route('dashboard.teams.list') . '#' . $student->team_id }}" class="btn btn-xs btn-success">Liste</a>
                            <a href="{{ route('dashboard.teams.edit', ['id' => $student->team_id ]) }}" class="btn btn-xs btn-warning">Modifier</a>
                        @else
                            <em>Aucune</em>
                        @endif
                    </div>
                </div>
            </fieldset>

            @if (!$student->is_newcomer)
                <fieldset>
                    <legend>Droits et groupes</legend>
                    <div class="form-group">
                        <label for="admin" class="col-lg-2 text-right">Admin</label>
                        <div class="col-lg-10">
                            <input type="checkbox" id="admin" name="admin" @if (old('admin') ?? ($student->admin == 100)) checked="checked" @endif/><br/>
                            <small class="text-muted">Il est conseillé de donner le droit <em>organisateur</em> aux administrateurs. Ils sont complémentaires.</small>
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
                    <div class="form-group">
                        <label for="secu" class="col-lg-2 text-right">Secu</label>
                        <div class="col-lg-10">
                            <input type="checkbox" id="secu" name="secu" @if (old('secu') ?? ($student->secu == 1)) checked="checked" @endif/><br/>
                        </div>
                    </div>
                </fieldset>
            @endif

            <fieldset>
                <legend>WEI</legend>
                    <div class="form-group">
                        <label for="wei" class="col-lg-2 text-right">wei</label>
                        <div class="col-lg-10">
                            <input type="checkbox" id="wei" name="wei" @if (old('wei') ?? ($student->wei == 1)) checked="checked" @endif disabled/>
                            <br/>
                            <small class="text-muted">A effectué au moins le paiement ou la caution du wei. Et compte donc déjà dans les inscrits au wei, même si son inscription est incomplète.</small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="wei_payment" class="col-lg-2 text-right">Paiement du wei</label>
                        <div class="col-lg-10">
                            @if ($student->weiPayment)
                                {{ $student->weiPayment->state }}
                            @else
                                <em>Non</em>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sandwich_payment" class="col-lg-2 text-right">Paiement du sandwich</label>
                        <div class="col-lg-10">
                            @if ($student->sandwichPayment)
                                {{ $student->sandwichPayment->state }}
                            @else
                                <em>Non</em>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="wei_validated" class="col-lg-2 text-right">Validé par un admin</label>
                        <div class="col-lg-10">
                            <input type="checkbox" id="wei_validated" name="wei_validated" @if (old('wei_validated') ?? ($student->wei_validated == 1)) checked="checked" @endif/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="parent_authorization" class="col-lg-2 text-right">Autorisation parentale donnée</label>
                        <div class="col-lg-10">
                            <input type="checkbox" id="parent_authorization" name="parent_authorization" @if (old('parent_authorization') ?? ($student->parent_authorization == 1)) checked="checked" @endif/>
                        </div>
                    </div>

                <div class="form-group">
                    <label for="bus_id" class="col-lg-2 control-label">Numéro du bus</label>
                    <div class="col-lg-10">
                        <input class="form-control" name="bus_id" id="bus_id" placeholder="Numéro du bus" type="text" value="{{ old('bus_id') ?? $student->bus_id }}">
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend>Position GPS</legend>

                <div class="form-group">
                    <label for="latitude" class="col-lg-2 text-right">Latitude</label>
                    <div class="col-lg-10">
                        {{ $student->latitude }}
                    </div>
                </div>
                <div class="form-group">
                    <label for="longitude" class="col-lg-2 text-right">Longitude</label>
                    <div class="col-lg-10">
                        {{ $student->longitude }}
                    </div>
                </div>

            </fieldset>


            <fieldset>
                <legend>Dates</legend>
                <div class="form-group">
                    <label for="admin" class="col-lg-2 text-right">Inscription</label>
                    <div class="col-lg-10">
                        {{ $student->created_at }}
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
                        {{ $student->last_login }}
                    </div>
                </div>
            </fieldset>
            <input type="submit" class="btn btn-success form-control" value="Mettre à jour les informations" />
        </form>
    </div>
</div>
@endsection
