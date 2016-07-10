@extends('layouts.dashboard')

@section('title')
Ajouter un membre
@endsection

@section('smalltitle')
Parce que être tout seul c'est triste
@endsection

@section('content')

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Recherche d'un étudiant</h3>
    </div>
    <div class="box-body text-center">
        <form action="{{ route('dashboard.ce.add') }}" method="get">
            <input type="text" name="search" class="form-control text-center" placeholder="Nom, Prénom, Numéro étudiant" required>
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
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Labels</th>
                    <th>Actions</th>
                </tr>
                @foreach ($team->ce as $student)
                    <tr>
                        <td><a href="{{ @asset('/uploads/students-trombi/'.$student->student_id.'.jpg') }}"><img src="{{ @asset('/uploads/students-trombi/'.$student->student_id.'.jpg') }}" alt="Photo"/></a></td>
                        <td>{{{ $student->first_name . ' ' . $student->last_name }}}</td>
                        <td>{{{ $student->email }}}</td>
                        <td>{{{ $student->phone }}}</td>
                        <td>
                            @if ($student->student_id == $team->respo_id)
                                <span class="label label-primary" title="Responsable de l'équipe"><i class="fa fa-star" aria-hidden="true"></i> Respo</span>
                            @endif
                            @if ($student->team_accepted)
                                <span class="label label-success" title="A validé sa participation à l'équipe"><i class="fa fa-check-circle" aria-hidden="true"></i> Accepté</span>
                            @else
                                <span class="label label-warning" title="N'a pas encore validé sa participation à l'équipe"><i class="fa fa-question-circle" aria-hidden="true"></i> En attente</span>
                            @endif
                        </td>
                        <td>
                            <a class="btn btn-xs btn-warning" href="{{ route('dashboard.students.list')}}">Modifier</a>
                            <a class="btn btn-xs btn-danger" href="{{ route('dashboard.students.list')}}">Supprimer</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if ($student->student_id == $team->respo_id && $team->ce->count() < 5)
            <div class="box-body">
                    <a href="" class="btn btn-success form-control">Ajouter un membre à l'équipe</a>
            </div>
        @endif
    </div>
</div>
@endsection
