@extends('layouts.dashboard')

@section('title')
Mon profil santé
@endsection

@section('content')
<div class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">Modification de mon profil santé</h3>
        </div>
        <div class="box-body">
        <form class="form-horizontal" action="{{ route('dashboard.wei.health') }}" method="post">
            <p class="text-center">
                Pendant l'intégration, tu seras amené à manger des repas que nous t'aurons préparés et à faire des activités sportives.<br/>
                Ces informations seront utilisés uniquement pour réagir rapidement en cas de problème.<br/>
                Elles ne seront accessibles que par les coordinateurs de l'intégration, les secouristes présents la semaine (association SecUTT) et l'infirmière de l'UTT.
            </p>

            <div class="form-group {{{ Auth::user()->isChecked('profil_parent_name')?'form-group-success':'' }}}">
                <label for="parent_name" class="col-lg-2 control-label">Personne à contacter en cas d'urgence</label>
                <div class="col-lg-10">
                    <input class="form-control" name="parent_name" id="parent_name" placeholder="Prénom Nom" type="text" value="{{{ old('parent_name') ?? Auth::user()->parent_name }}}">
                </div>
            </div>

            <div class="form-group {{{ Auth::user()->isChecked('profil_parent_phone')?'form-group-success':'' }}}">
                <label for="parent_phone" class="col-lg-2 control-label">Numéro de téléphone de cette personne</label>
                <div class="col-lg-10">
                    <input class="form-control" name="parent_phone" id="parent_phone" placeholder="06.12.34.56.78" type="text" value="{{{ old('parent_phone') ?? Auth::user()->parent_phone }}}">
                    <small class="text-muted">Note : numéro de téléphone étranger accepté. N'oublie pas l'indicatif pour un numéro étranger (+33...).</small>
                </div>
            </div>

            <div class="form-group {{{ Auth::user()->isChecked('profil_parent_phone')?'form-group-success':'' }}}">
                <label for="medical_allergies" class="col-lg-2 control-label">Allergies</label>
                <div class="col-lg-10">
                    <textarea class="form-control" name="medical_allergies" id="medical_allergies">{{{ old('medical_allergies') ?? Auth::user()->medical_allergies }}}</textarea>
                </div>
            </div>

            <div class="form-group {{{ Auth::user()->isChecked('profil_parent_phone')?'form-group-success':'' }}}">
                <label for="medical_treatment" class="col-lg-2 control-label">Traitement ou régime particulier pendant la semaine d'intégration</label>
                <div class="col-lg-10">
                    <textarea class="form-control" name="medical_treatment" id="medical_treatment">{{{ old('medical_treatment') ?? Auth::user()->medical_treatment }}}</textarea>
                </div>
            </div>

            <div class="form-group {{{ Auth::user()->isChecked('profil_parent_phone')?'form-group-success':'' }}}">
                <label for="medical_note" class="col-lg-2 control-label">Remarques</label>
                <div class="col-lg-10">
                    <textarea class="form-control" name="medical_note" id="medical_note">{{{ old('medical_note') ?? Auth::user()->medical_note }}}</textarea>
                </div>
            </div>

            <input type="submit" class="btn btn-success form-control" value="Valider !">
        </form>
        </div>
</div>
@endsection
