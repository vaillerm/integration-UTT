@extends('layouts.dashboard')

@section('title')
Nouveaux
@endsection

@section('smalltitle')
Affichage des profils
@endsection

@section('content')

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Liste des nouveaux ({{ $newcomers->count() }})</h3>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            <tbody>
                <tr>
                    <th>Nom</th>
                    <th>Branche</th>
                    <th>Mail</th>
                    <th>Téléphones</th>
                    <th>Identifiants</th>
                    <th>Parrain</th>
                    <th>Équipe</th>
                    <th>Paiement WEI</th>
                    <th>Sandwich</th>
                    <th>Caution</th>
                    <th>Action</th>
                </tr>
                @foreach ($newcomers as $newcomer)
                    <tr>
                        <td>{{{ ucfirst($newcomer->first_name) . ' ' . strtoupper($newcomer->last_name) }}}</td>
                        <td>
                            {{ $newcomer->branch }}
                        </td>
                        <td>
                            <span title="Mail donné volontairement sur ce site">{{{ $newcomer->email }}}</span><br/>
                            <del title="Mail donné lors de l'inscription à l'UTT. A n'utiliser qu'en cas de necessité.">{{{ $newcomer->registration_email }}}</del>
                        </td>
                        <td>
                            @if($newcomer->phone)
                                <span title="Téléphone donné volontairement sur ce site">{{{ $newcomer->phone }}}</span><br/>
                            @endif
                            @if($newcomer->registration_phone)
                                <del title="Téléphone fixe donné lors de l'inscription à l'UTT. A n'utiliser qu'en cas de necessité.">{{{ $newcomer->registration_phone }}}</del><br/>
                            @endif
                        </td>
                        <td>
                            <span title="Identifiant">{{ $newcomer->login }}</span><br/>
                        </td>
                        @if ($newcomer->godFather)
                            <td>{{{ $newcomer->godFather->first_name . ' ' . strtoupper($newcomer->godFather->last_name) }}}</td>
                        @else
                            <td>Aucun !</td>
                        @endif
                        @if ($newcomer->team)
                            @if($newcomer->team->name != null)
                                <td>{{{ $newcomer->team->name }}}</td>
                            @else
                                <td>Équipe sans nom {{{ $newcomer->team->id }}}</td>
                            @endif
                        @else
                            <td>Aucune !</td>
                        @endif
                        <td>{{ ((isset($newcomer->weiPayment) && $newcomer->weiPayment->state == 'paid')?'Oui':'Non') }}</td>
                        <td>{{ ((isset($newcomer->sandwichPayment) && $newcomer->sandwichPayment->state == 'paid')?'Oui':'Non') }}</td>
                        <td>{{ ((isset($newcomer->guaranteePayment) && $newcomer->guaranteePayment->state == 'paid')?'Oui':'Non') }}</td>
                        <td>
                            <a class="btn btn-xs btn-warning" href="{{ route('dashboard.students.edit', [ 'id' => $newcomer->id ])}}">Modifier</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">BIG RED BUTTON OF SCIENCE</h3>
    </div>
    <div class="box-body">
        <p>Merci de n'appuyer sur ces boutons qu'une fois tous les nouveaux ajoutés à la liste.</p>
        <a href="{{{ route('dashboard.newcomers.sync') }}}" class="btn btn-success">Raffraichir la liste avec les serveurs UTT (asynchrone)</a>
        <a href="{{{ route('dashboard.teams.match') }}}" class="btn btn-danger">Répartir les nouveaux qui n'ont pas d'équipes dans les équipes</a>
        <a href="{{{ route('dashboard.referrals.prematch') }}}" class="btn btn-danger">Donner des parrains aux nouveaux qui n'en n'ont pas</a>
        <p>Pour annuler des répartitions, ça se passe en base de données.</p>
    </div>
</div>

@if(Request::old())
    <div class="box box-default">
@else
    <div class="box box-default collapsed-box">
@endif
    <div class="box-header with-border">
        <h3 class="box-title">Création de compte nouveau</h3>
        <button class="btn btn-box-tool" data-widget="collapse">
            @if(Request::old())
                <i class="fa fa-minus"></i>
            @else
                <i class="fa fa-plus"></i>
            @endif
        </button>
    </div>
    <div class="box-body">
        <div class="callout callout-danger">
            <h4>DANGER !</h4>
            <p>
                Ce formulaire ne doit être utilisé que par des utilisateurs averti. Les nouveaux sont automatiquement importés, vous n'avez donc pas besoin d'utiliser ce formulaire.
            </p>
        </div>

        <h4>Ajouter un nouveau</h4>
        <form class="" action="{{ route('dashboard.newcomers.create') }}" method="post">
            <input type="text" name="first_name" class="form-control" placeholder="Prénom" value="{{ old('first_name') }}">
            <input type="text" name="last_name" class="form-control" placeholder="Nom" value="{{ old('last_name') }}">
            <select id="sex" name="wei_majority" class="form-control">
                <option value="0" @if (old('wei_majority') == 0) selected="selected" @endif >Mineur</option>
                <option value="1" @if (old('wei_majority') == 1) selected="selected" @endif >Majeur</option>
            </select>
            <input type="text" name="registration_email" class="form-control" placeholder="Adresse mail" value="{{ old('registration_email') }}">
            <input type="text" name="registration_phone" class="form-control" placeholder="Téléphone" value="{{ old('registration_phone') }}">
            <input type="password" name="password" class="form-control" placeholder="Mot de passe" value="{{ old('password') }}">
            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirmation de mot de passe" value="{{ old('password_confirmation') }}">
            <input type="text" name="postal_code" class="form-control" placeholder="Code postal" value="{{ old('postal_code') }}">
            <input type="text" name="country" class="form-control" placeholder="Pays" value="{{ old('country') }}">
            <input type="text" name="branch" class="form-control" placeholder="Branche (TC, ISI, MM...)" value="{{ old('branch') }}">
            <input type="submit" class="btn btn-success form-control" value="Créer le nouveau">
        </form>
    </div>
</div>
@endsection
