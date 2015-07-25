<html>
    <head>
        <title>Mon parrain est magique !</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" media="screen">
    <body>
        <div class="container">
            <div class="row">
                <div class="text-center">

                    <p>
                    <h1>Parrainage 2015</h1>

                    Comme chaque année, nous te proposons de remplir un formulaire
                    avec tes informations basiques afn de te permettre d'avoir un fillot !
                    </p>
                    <p>
                    Nous enverrons une petite fiche à tes fillots, alors tente de faire un message
                    le plus accrocheur possible !

                    </p>

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
                              <label for="city" class="col-lg-2 control-label">Ville</label>
                              <div class="col-lg-10">
                                <input class="form-control" name="city" id="city" placeholder="Ville" type="text" value="{{{ $referral->city }}}">
                              </div>
                            </div>

                            <div class="form-group">
                              <label for="postal_code" class="col-lg-2 control-label">Code postal</label>
                              <div class="col-lg-10">
                                <input class="form-control" name="postal_code" id="postal_code" placeholder="Code postal" type="text" value="{{{ $referral->postal_code }}}">
                              </div>
                            </div>

                            <div class="form-group">
                              <label for="country" class="col-lg-2 control-label">Pays</label>
                              <div class="col-lg-10">
                                <input class="form-control" name="country" id="country" placeholder="Pays" type="text" value="{{{ $referral->country }}}">
                              </div>
                            </div>

                            <div class="form-group">
                              <label for="max" class="col-lg-2 control-label">Nombre de fillots maximum</label>
                              <div class="col-lg-10">
                                <select id="max" name="max" class="form-control" class="">
                                  @foreach (range(1, 5) as $i)
                                    @if ($i == $referral->max) <option value="{{ $i }}" selected="selected">
                                    @else <option value="{{ $i }}">
                                    @endif
                                    {{ $i }}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>

                            <div class="form-group">
                              <label for="level" class="col-lg-2 control-label">Année en cours</label>
                              <div class="col-lg-10">
                                <input class="form-control" name="level" id="level" placeholder="TC02, SRT04, ..." type="text" value="{{{ $referral->level }}}">
                              </div>
                            </div>

                            <div class="form-group">
                              <label for="double_degree" class="col-lg-2 control-label">Double-diplôme ?</label>
                              <div class="col-lg-10">
                                <select id="double_degree" name="double_degree" class="form-control" class="">
                                    @foreach (['Aucun', 'IMEDD', 'SSI', 'IMSGA', 'SMILES', 'OSS', 'ONT', 'MERI', 'Autre'] as $value)
                                        @if ($value == 'Aucun' && $referral->double_degree == null) <option value="Aucun" selected="selected">
                                        @elseif ($value == $referral->double_degree) <option value="{{ $value }}" selected="selected">
                                        @else <option value="{{ $value }}">
                                        @endif
                                        {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                              </div>
                            </div>

                            <div class="form-group">
                              <label for="free_text" class="col-lg-2 control-label">Message pour ton fillot<br>(140 caractères minimum)</label>
                              <div class="col-lg-10">
                                <textarea class="form-control" rows="5" id="free_text" name="free_text">{{{ $referral->free_text }}}</textarea>
                                <span>Ce contenu sera soumis à modération.</span>
                              </div>
                            </div>

                            <div class="form-group">
                              <div class="text-center">
                                <button type="reset" class="btn btn-default">Réinitialisation</button>
                                <button type="submit" class="btn btn-success">Je valide !</button>
                              </div>
                              <div class="text-center"><br>
                                     <a href="{{ route('menu') }}">Retour au menu</a>
                              </div>
                            </div>
                          </fieldset>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <script type="text/javascript" src="{{ asset('/js/jquery-1.11.3.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('/js/jquery.noty.packaged.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('/js/referrals.js') }}"></script>
    </body>
</html>
