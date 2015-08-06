@extends('layouts.dashboard')

@section('title')
<h1>
    Week-end d'intégration
    <small>Inscription et gestion des participants</small>
</h1>
@endsection

@section('content')

@include('display-errors')

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
