@extends('layouts.dashboard')

@section('title')
Nouveaux
@endsection

@section('smalltitle')
Affichage des profils
@endsection

@section('content')



<div class="callout callout-info">
    <h4>Informations</h4>
    <p>
        Cliquez sur le <strong>+</strong> si vous voulez ajouter des nouveaux.
    </p>
    <p>
        Pour avoir plus de précision sur un champ, restez dessus avec votre souris quelques secondes.
    </p>
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
        <h4>Ajouter un nouveau</h4>
        <form class="" action="{{ route('dashboard.newcomers.create') }}" method="post">
            <input type="text" name="first_name" class="form-control" placeholder="Prénom" value="{{ old('first_name') }}">
            <input type="text" name="last_name" class="form-control" placeholder="Nom" value="{{ old('last_name') }}">
            <select id="sex" name="sex" class="form-control">
                <option value="0" @if (old('sex') == 0) selected="selected" @endif >Homme</option>
                <option value="1" @if (old('sex') == 1) selected="selected" @endif >Femme</option>
            </select>
            <input type="text" name="birth" class="form-control" placeholder="Date de naissance (JJ/MM/AAAA)" value="{{ old('birth') }}">
            <input type="text" name="registration_email" class="form-control" placeholder="Adresse mail" value="{{ old('registration_email') }}">
            <input type="text" name="registration_cellphone" class="form-control" placeholder="Téléphone portable" value="{{ old('registration_cellphone') }}">
            <input type="text" name="registration_phone" class="form-control" placeholder="Téléphone fixe" value="{{ old('registration_phone') }}">
            <input type="text" name="postal_code" class="form-control" placeholder="Code postal" value="{{ old('postal_code') }}">
            <input type="text" name="country" class="form-control" placeholder="Pays" value="{{ old('country') }}">
            <input type="text" name="branch" class="form-control" placeholder="Branche (TC, ISI, MM...)" value="{{ old('branch') }}">
            <input type="text" name="ine" class="form-control" placeholder="INE" value="{{ old('ine') }}">
            <input type="submit" class="btn btn-success form-control" value="Créer le nouveau">
        </form>

        <h4>Ajouter des nouveaux</h4>
        <form class="" action="{{ route('dashboard.newcomers.createcsv') }}" method="post">
            <textarea name="csv" class="form-control" placeholder="&quot;Prénom&quot;;&quot;Nom&quot;;&quot;Sexe(M/F)&quot;;&quot;Date de naissance (JJ/MM/AAAA)&quot;;&quot;Branche&quot;;&quot;Mail&quot;;&quot;Téléphone portable&quot;;&quot;Téléphone fixe&quot;;&quot;Code postal&quot;;&quot;Pays&quot;;&quot;INE&quot;">{{ old('csv') }}</textarea>
            <input type="submit" class="btn btn-success form-control" value="Créer les nouveaux">
        </form>
    </div>
</div>

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
                            @if($newcomer->registration_cellphone)
                                <del title="Téléphone portable donné lors de l'inscription à l'UTT. A n'utiliser qu'en cas de necessité.">{{{ $newcomer->registration_cellphone }}}</del><br/>
                            @endif
                        </td>
                        <td>
                            <span title="Identifiant">{{ $newcomer->login }}</span><br/>
                            <em title="Mot de passe">{{{ Crypt::decrypt($newcomer->password) }}}</em>
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
                            <a href="{{ route('dashboard.newcomers.letter', ['id' => $newcomer->id ]) }}" class="btn btn-success btn-xs">Lettre</a>
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
        <a href="{{{ route('dashboard.teams.match') }}}" class="btn btn-danger">Répartir les nouveaux qui n'ont pas d'équipes dans les équipes</a>
        <a href="{{{ route('dashboard.referrals.prematch') }}}" class="btn btn-danger">Donner des parrains aux nouveaux qui n'en n'ont pas</a>
        <a href="{{{ route('dashboard.newcomers.letters', ['id' => 0, 'limit' => 10000]) }}}" class="btn btn-success">Imprimer toutes les lettres</a>
        <div class="btn-group">
            <a href="#" class="btn btn-primary">Impression par branche</a>
            <a href="#" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span></a>
            <ul class="dropdown-menu">
                @foreach($branches as $branch)
                    <li><a href="{{{ route('dashboard.newcomers.filtered_letters', ['id' => 0, 'limit' => 10000, 'category' => strtoupper($branch->branch)]) }}}">{{ strtoupper($branch->branch) }}</a></li>
                @endforeach
            </ul>
        </div>
        <p>Pour annuler des répartitions, ça se passe en base de données.</p>
    </div>
</div>
@endsection
