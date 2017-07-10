@extends('layouts.dashboard')

@section('title')
Validation des messages
@endsection

@section('smalltitle')
Pour les yeux chastes des parents ou innocents des TC...
@endsection

@section('content')

<div class="callout callout-info">
    <h4>Rappel</h4>
    <p>
        Il n'est possible de valider les messages que des personnes qui ont déjà indiqué à
        la fois leur <b>téléphone</b>, <b>adresse mail</b> et <b>texte de parrain</b>.<br>
        <b>Les conflits d'édition ne sont pas encore gérés : si vous modifiez quelqu'un qui vient de l'être, vous aurez juste un message d'erreur.</b>
    </p>
</div>

@if ($referral)
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Message de {{ $referral->first_name . ' ' . $referral->last_name }} ({{ $referral->student_id }})</h3>
    </div>
    <div class="box-body">
        <form action="{{ route('dashboard.referrals.validation') }}" method="post">
            <input type="hidden" name="student-id" value="{{ $referral->student_id }}">
            <textarea name="referral_text" class="form-control" cols="30" rows="10">{{{ $referral->referral_text }}}</textarea>
            <input type="submit" class="btn btn-success form-control" value="Valider !">
        </form>
    </div>
</div>
@else
<div class="box box-default">
    <div class="box-body text-center">
        <b>Tout a déjà été validé. :-) Regarde du côté des personnes aux informations incomplètes !</b>
    </div>
</div>
@endif
@endsection
