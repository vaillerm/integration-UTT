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
        Cliquez sur le <strong>+</strong> si vous voullez ajouter des nouveaux.
    </p>
    <p>
        Les <strong>champs barrés</strong> dans la liste de nouveaux ne sont à utiliser <strong>qu'en cas d'urgence</strong>. Ils ont été donnés lors de l'inscription à l'UTT et nous ne sommes pas forcément censé les avoir.
    </p>
    <p>
        Pour avoir plus de précision sur un champ restez dessus avec votre souris quelques secondes
    </p>
</div>

@if(Request::old())
    <div class="box box-default">
@else
    <div class="box box-default collapsed-box">
@endif
    <div class="box-header with-border">
        <h3 class="box-title">Creation de compte nouveau</h3>
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
            <input type="text" name="birth" class="form-control" placeholder="Date de naissance (AAAA-MM-JJ)" value="{{ old('birth') }}">
            <input type="text" name="registration_email" class="form-control" placeholder="Adresse email" value="{{ old('registration_email') }}">
            <input type="text" name="registration_cellphone" class="form-control" placeholder="Téléphone portable" value="{{ old('registration_cellphone') }}">
            <input type="text" name="registration_phone" class="form-control" placeholder="Téléphone Fixe" value="{{ old('registration_phone') }}">
            <textarea name="registration_address" class="form-control" placeholder="Adresse postale">{{ old('registration_address') }}</textarea>
            <input type="text" name="branch" class="form-control" placeholder="Branche (TC, ISI, MM, etc)" value="{{ old('branch') }}">
            <input type="text" name="ine" class="form-control" placeholder="INE" value="{{ old('ine') }}">
            <input type="submit" class="btn btn-success form-control" value="Créer le nouveau">
        </form>

        <h4>Ajouter des nouveaux</h4>
        <form class="" action="{{ route('dashboard.newcomers.createcsv') }}" method="post">
            <textarea name="csv" class="form-control" placeholder="&quot;Prénom&quot;;&quot;nom&quot;;&quot;sexe(M/F)&quot;;&quot;naissance(AAAA-MM-JJ)&quot;;&quot;branche&quot;;&quot;email&quot;;&quot;telephone portable&quot;;&quot;telephone fixe&quot;;&quot;adresse postale&quot;;&quot;INE&quot;">{{ old('csv') }}</textarea>
            <input type="submit" class="btn btn-success form-control" value="Créer les nouveaux">
        </form>
    </div>
</div>

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Liste des nouveaux</h3>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            <tbody>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Téléphones</th>
                    <th>Identifiants</th>
                    <th>Parrain</th>
                    <th>Équipe</th>
                    <th>Action</th>
                </tr>
                @foreach ($newcomers as $newcomer)
                    <tr>
                        <td>{{{ ucfirst($newcomer->first_name) . ' ' . strtoupper($newcomer->last_name) }}}</td>
                        <td>
                            <span title="Email donné volontairement sur ce site">{{{ $newcomer->email }}}</span><br/>
                            <del title="Email donné lors de l'inscription à l'UTT. A n'utiliser qu'en cas de necessité.">{{{ $newcomer->registration_email }}}</del>
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
                        @if ($newcomer->referral)
                            <td>{{{ $newcomer->referral->first_name . ' ' . strtoupper($newcomer->referral->last_name) }}}</td>
                        @else
                            <td>Aucun !</td>
                        @endif
                        @if ($newcomer->team)
                            <td>{{{ $newcomer->team->name }}}</td>
                        @else
                            <td>Aucune !</td>
                        @endif
                        <td>
                            {{-- <a href="{{ route('dashboard.newcomers.list', ['id' => $newcomer->id ]) }}" class="btn btn-success btn-xs">Afficher le profil</a> --}}
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
        <p>Pour annuler des répartition, demandez à la personne chargé du développement du site.</p>
    </div>
</div>
@endsection
