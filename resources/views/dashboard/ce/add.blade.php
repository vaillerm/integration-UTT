@extends('layouts.dashboard')

@section('title')
Ajouter un membre
@endsection

@section('smalltitle')
Parce qu'être tout seul c'est triste
@endsection

@section('content')

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Recherche d'un étudiant</h3>
    </div>
    <div class="box-body text-center">
        <form action="{{ route('dashboard.ce.add') }}" method="get">
            <input type="text" name="search" class="form-control text-center" placeholder="Nom de famille" required value="{{{ $search }}}">
            <input type="submit" class="btn btn-success form-control" value="Rechercher">
        </form>
    </div>
</div>

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Résultats de la recherche</h3>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover trombi">
            <tbody>
                <tr>
                    <th>Photo</th>
                    <th>Nom complet</th>
                    <th>Actions</th>
                </tr>
                @foreach ($students as $student)
                    <tr>
                        <td><a href="https://etu.utt.fr/user/{{{ $student['login'] }}}"><img src="https://etu.utt.fr{{{ $student['links']['user.image'] }}}" alt="Photo"/></a></td>
                        <td>{{{ $student['fullName'] }}}</td>
                        <td>
                            <a class="btn btn-xs btn-primary" href="{{ route('dashboard.ce.addsubmit', ['login' => $student['login'] ])}}">Ajouter à mon équipe</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
