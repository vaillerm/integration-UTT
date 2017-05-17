@extends('layouts.master')

@section('title')
Parrainage
@endsection

@section('css')
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/skin-blue.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('/js/jquery.noty.packaged.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/referrals.js') }}"></script>
@endsection

@section('bodycontent')
    <div class="container">
        <div class="row">
            <div class="text-center">

                <h1>Parrainage</h1>
                <p>
                Comme chaque année, nous te proposons de remplir un formulaire
                avec tes informations basiques afn de te permettre d'avoir un fillot !
                </p>
                <p>
                Nous enverrons une petite fiche à tes fillots, alors tente de faire un message
                le plus accrocheur possible ! Tous les champs sauf <em>Surnom</em> et <em>Facebook</em> sont obligatoires.
                </p>
                <p>
                Attention ! En devenant parrain/marraine, vous vous engagez à être <strong>présent le lundi (TC) ou mardi (branches) de la semaine d'intégration</strong> pour accueillir vos fillots.

                <div class="well bs-component">
                    <form class="form-horizontal" action="{{ route('referrals.update') }}">
                      <fieldset>
                        <legend>Tes informations</legend>

                        <div class="form-group">
                          <label for="name" class="col-lg-2 control-label">Nom complet</label>
                          <div class="col-lg-10">
                            <input class="form-control" type="text" id="name" name="name" disabled value="{{{ $referral->first_name . ' ' . $referral->last_name }}}">
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="surname" class="col-lg-2 control-label">Surnom</label>
                          <div class="col-lg-10">
                            <input class="form-control" type="text" name="surname" id="surname" value="{{{ $referral->surname }}}">
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="sex" class="col-lg-2 control-label">Sexe</label>
                          <div class="col-lg-10">
                            <select id="sex" name="sex" class="form-control" class="">
                                <option value="0" @if ($referral->sex == 0) selected="selected" @endif >Parrain</option>
                                <option value="1" @if ($referral->sex == 1) selected="selected" @endif >Marraine</option>
                            </select>
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="email" class="col-lg-2 control-label">Email</label>
                          <div class="col-lg-10">
                            <input class="form-control" name="email" id="email" placeholder="Adresse email" type="text" value="{{{ $referral->email }}}">
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="phone" class="col-lg-2 control-label">Portable</label>
                          <div class="col-lg-10">
                            <input class="form-control" name="phone" id="phone" placeholder="Portable" type="text" value="{{{ $referral->phone }}}">
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="facebook" class="col-lg-2 control-label">Profil facebook</label>
                          <div class="col-lg-10">
                            <input class="form-control" type="text" name="facebook" id="facebook" placeholder="https://facebook.com/ton.profil"value="{{{ $referral->facebook }}}">
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="city" class="col-lg-2 control-label">Ville d'origine</label>
                          <div class="col-lg-10">
                            <input class="form-control" name="city" id="city" placeholder="Ville d'origine" type="text" value="{{{ $referral->city }}}">
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="postal_code" class="col-lg-2 control-label">Code postal de ta ville d'origine (met 0 si tu viens de l'étranger)</label>
                          <div class="col-lg-10">
                            <input class="form-control" name="postal_code" id="postal_code" placeholder="Code postal" type="text" value="{{{ $referral->postal_code }}}">
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="country" class="col-lg-2 control-label">Pays d'origine</label>
                          <div class="col-lg-10">
                            <input class="form-control" name="country" id="country" placeholder="Pays d'origine" type="text" value="{{{ $referral->country }}}">
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="referral_max" class="col-lg-2 control-label">Nombre de fillots maximum</label>
                          <div class="col-lg-10">
                            <select id="referral_max" name="referral_max" class="form-control" class="">
                              @foreach (range(1, 5) as $i)
                                @if ($i == $referral->referral_max) <option value="{{ $i }}" selected="selected">
                                @else <option value="{{ $i }}">
                                @endif
                                {{ $i }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="semester" class="col-lg-2 control-label">Semestre en cours</label>
                          <div class="col-lg-10">
                            <input class="form-control" name="semester" id="semester" type="text" value="{{{ $referral->branch . $referral->level }}}" disabled>
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="referral_text" class="col-lg-2 control-label">Message pour ton fillot<br>(140 caractères minimum)</label>
                          <div class="col-lg-10">
                            <textarea class="form-control" rows="5" id="referral_text" name="referral_text">{{{ $referral->referral_text }}}</textarea>
                            <span>Ce contenu sera soumis à modération.</span>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="text-center">
                            <button type="submit" class="btn btn-primary">Sauvegarder</button>
                            <button type="submit" class="btn btn-success">Je valide !</button>
                          </div>
                          <div class="text-center"><br>
                                 <a class="btn btn-default" href="{{ route('menu') }}">Retour au menu</a>
                          </div>
                        </div>
                      </fieldset>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
