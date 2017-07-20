@extends('layouts.dashboard')

@section('title')
Vérification des champs des parrains
@endsection

@section('smalltitle')
Parce que la Bretagne n'est pas un pays...
@endsection

@section('content')

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Verification des champs des parrains</h3>
    </div>
    <div class="box-body">
        <p>Cette page vous permet de vérifier, et modifier certains champs entrés par les parrains ou récupérés via les listes de nouveaux.<br/>
            Certains parrains oublient de rentrer le pays ou mettent "Bretagne" par exemple...
            Or avec ça comme pays, ils récupererons que des nouveaux étrangers car le pays n'est pas la France (potentiellement des Bretons é_è).
        </p>
        <p>
            Votre rôle est donc de faire en sorte que les pays et les branches correspondent au maximum entre nouveaux et parrains. La casse n'a pas d'importance, mais les accents oui, donc uniformisez aussi les accents.
        </p>
        <form action="{{ route('dashboard.referrals.prematch.submit') }}" method="post">

            <br/><br/>

            <h4>Pays des parrains</h4>
            @foreach($referralCountries as $value)
                <input type="text" name="referralCountries[{{ $value }}]" class="form-control" value="{{ $value }}">
            @endforeach

            <h4>Pays des fillots</h4>
            @foreach($newcomerCountries as $value)
                <input type="text" name="newcomerCountries[{{ $value }}]" class="form-control" value="{{ $value }}">
            @endforeach

            <br/><br/>

            <h4>Branche des parrains</h4>
            <p>Pour que l'algorithme fonctionne correctement, remplacez <em>PMOM</em> par <em>MM</em> (leur nouveau nom). Et les masters doivent avoir <em>MP</em> comme branche.
            @foreach($referralBranches as $value)
                <input type="text" name="referralBranches[{{ $value }}]" class="form-control" value="{{ $value }}">
            @endforeach

            <h4>Branche des fillots</h4>
            @foreach($newcomerBranches as $value)
                <input type="text" name="newcomerBranches[{{ $value }}]" class="form-control" value="{{ $value }}">
            @endforeach


            <br/><br/>

            <input type="submit" class="btn btn-success form-control" value="Valider !">
        </form>
    </div>
</div>
@endsection
