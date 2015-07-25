@extends('layouts.dashboard')

@section('title')
<h1>
    Administrateurs
    <small>Personnes ayant accès à l'interface</small>
</h1>
@endsection

@section('content')

@include('display-errors')

<div class="callout callout-info">
    <h4>Numéros étudiants</h4>
    <p>
        Pour le moment, je ne fais que stocker les numéros étudiant des administrateurs,
        c'est la seule chose unique sur laquelle je peux me baser pour leur donner accès ou non.
        <br>
        <b>Pour trouver celui d'une personne, direction <a href="https://etu.utt.fr">
        le site étu</a></b> ;-)
    </p>
</div>

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Ajouter un administrateur</h3>
    </div>
    <div class="box-body text-center">
        <form class="" action="{{ route('dashboard.administrators') }}" method="post">
            <input type="hidden" name="action" value="add">
            <input type="text" name="student-id" value="" class="form-control text-center" placeholder="NUMÉRO ÉTUDIANT" required>
            <input type="submit" class="btn btn-success form-control" value="Ajouter">
        </form>
    </div>
</div>

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Liste des administrateurs</h3>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            <tbody>
                <tr>
                    <th>Numéro étudiant</th>
                    <th>Actions</th>
                </tr>
                @foreach ($administrators as $administrator)
                    <tr>
                        <td>{{ $administrator->student_id }}</td>
                        <td>
                            <form action="{{ route('dashboard.administrators') }}" method="post">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="student-id" value="{{ $administrator->student_id }}">
                                <input type="submit" class="btn btn-xs btn-danger" value="Supprimer">
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
