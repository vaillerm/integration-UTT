@extends('layouts.dashboard')

@section('title')
Recherche de participant au WEI
@endsection

@section('smalltitle')
@endsection

@section('content')

<div class="callout callout-info">
    <h4>Information !</h4>
    <p>
        Vous pouvez rechercher les anciens et les nouveaux par
    </p>
    <ul>
        <li>Numéro etu (ancien uniquement)</li>
        <li>Nom</li>
        <li>Prénom</li>
        <li>Surnom (Ancien uniquement)</li>
        <li>Email</li>
        <li>Identifiant (Nouveau uniquement)</li>
    </ul>
    <p>Si vous ne trouvez pas un nouveau, contactez <em>Alabate</em> : 06.62.12.74.36</p>
    <p>Si vous ne trouvez pas un ancien (CE, Bénévole, Orga, ...), demandez lui de se connecter sur le site de l'intégration et de "devenir bénévole" sur le site.</p>
    <p>Pour pouvoir venir au WEI, un ancien doit d'abord demander à devenir bénévole, lui même, sur le site.</p>
</div>

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Recherche</h3>
    </div>
    <div class="box-body">
        <form action="{{ route('dashboard.wei.search.submit') }}" method="post">
            <input name="search" class="form-control text-center" value="{{{ old('search') ?? $search ?? '' }}}" min="2" max="60" required placeholder="Nom, Prénom, ...">
            <input type="submit" class="btn btn-success form-control" value="Rechercher">
        </form>
    </div>
</div>

@if(isset($users))
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Liste des nouveaux et anciens</h3>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <tbody>
                    <tr>
                        <th>ID</th>
                        <th>Nom complet</th>
                        <th>Type</th>
                        <th>État</th>
                        <th>Actions</th>
                    </tr>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{{ $user->first_name . ' ' . $user->last_name . ' (' . $user->surname . ')' }}}</td>
                            <td>
                                @if ($user->student)
                                    @if ($user->ce)
                                        <span class="label label-primary">CE</span>
                                    @endif
                                    @if ($user->volunteer)
                                        <span class="label label-info">Bénévole</span>
                                    @endif
                                    @if ($user->orga)
                                        <span class="label label-warning">Orga</span>
                                    @endif
                                @else
                                    <span class="label label-success">Nouveaux</span>
                                @endif
                            </td>
                            <td>
                                @if (!$user->wei)
                                    <span class="label label-danger">Pas inscrit</span
                                @elseif ($user->wei_validated)
                                    <span class="label label-success">Validé</span>
                                @elseif ($user->wei_payment || $user->sandwich_payment || $user->guarantee_payment)
                                    <span class="label label-warning">Commencé</span>
                                @else
                                    <span class="label label-info">En attente de validation</span>
                                @endif
                            </td>
                            <td>
                                <a class="btn btn-xs btn-warning" href="{{ route('dashboard.wei.student.edit', [ 'id' => $user->id ])}}">Modifier</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection
