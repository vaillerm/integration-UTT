@extends('layouts.dashboard')

@section('title')
Etudiants
@endsection

@section('smalltitle')
Liste de tous les étudiants inscrits sur le site en tant que parrain, CE, orga, ...
@endsection

@section('content')

<div class="callout callout-info">
    <h4>Liste des étudiants</h4>
    <p>
        Pour qu'une personne apparaisse dans cette liste, il suffit qu'elle se soit connecté au site de l'intégration via le site étudiant au moins une fois.
    </p>
    <p>
        Les personnes avec le label <span class="label label-success">Parrain</span> sont les personnes qui ont au moins vu une fois le formulaire de parrainnage. Ce ne sont donc pas forcément des parrains.<br/>
        Les personnes avec le label <span class="label label-info">Bénévole</span> sont les personnes qui ont accepté d'être contacté pour aider pendant l'intégration et qui ont donc fournis un téléphone et un email.<br/>
        Pour être <span class="label label-primary">CE</span>, <span class="label label-danger">Admin</span> et/ou <span class="label label-warning">Orga</span>, l'étudiant est obligé de devenir bénévole afin d'être sûr que l'intégration dispose de ses informations de contact.<br/>
        Pour qu'un étudiant soit <span class="label label-danger">Admin</span> et/ou <span class="label label-warning">Orga</span> un Administrateur doit modifier le profil de l'étudiant via cette page.<br/>
    </p>
</div>

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Liste des étudiants</h3>
        <div class="pull-right">
            Filtres :
            <a class="btn btn-xs btn-danger{{ ($filter=='admin')?' active':'' }}" href="{{ route('dashboard.students.list', [ 'filter' => 'admin' ])}}">Admin</a>
            <a class="btn btn-xs btn-warning{{ ($filter=='orga')?' active':'' }}" href="{{ route('dashboard.students.list', [ 'filter' => 'orga' ])}}">Orga</a>
            <a class="btn btn-xs btn-success{{ ($filter=='referral')?' active':'' }}" href="{{ route('dashboard.students.list', [ 'filter' => 'referral' ])}}">Parrain</a>
            <a class="btn btn-xs btn-primary{{ ($filter=='ce')?' active':'' }}" href="{{ route('dashboard.students.list', [ 'filter' => 'ce' ])}}">CE</a>
            <a class="btn btn-xs btn-info{{ ($filter=='volunteer')?' active':'' }}" href="{{ route('dashboard.students.list', [ 'filter' => 'volunteer' ])}}">Bénévole</a>
            <a class="btn btn-xs btn-default{{ ($filter=='')?' active':'' }}" href="{{ route('dashboard.students.list')}}">Aucun</a>
        </div>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover trombi">
            <tbody>
                <tr>
                    <th>Photo</th>
                    <th>N° étu</th>
                    <th>Nom complet</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Semestre</th>
                    <th>Labels</th>
                    <th>Actions</th>
                </tr>
                @foreach ($students as $student)
                    <tr>
                        <td><a href="{{ asset('/uploads/students-trombi/'.$student->student_id.'.jpg') }}"><img src="{{ asset('/uploads/students-trombi/'.$student->student_id.'.jpg') }}" alt="Photo"/></a></td>
                        <td>{{ $student->student_id }}</td>
                        <td>{{{ $student->first_name . ' ' . $student->last_name }}}</td>
                        <td>{{{ $student->email }}}</td>
                        <td>{{{ $student->phone }}}</td>
                        <td>{{{ $student->branch.$student->level }}}</td>
                        <td>
                            @if ($student->isAdmin())
                                <span class="label label-danger">Admin</span>
                            @endif
                            @if ($student->referral)
                                <span class="label label-success">Parrain</span>
                            @endif
                            @if ($student->ce)
                                <span class="label label-primary">CE</span>
                            @endif
                            @if ($student->volunteer)
                                <span class="label label-info">Bénévole</span>
                            @endif
                            @if ($student->orga)
                                <span class="label label-warning">Orga</span>
                            @endif
                        </td>
                        <td>
                            <a class="btn btn-xs btn-warning" href="{{ route('dashboard.students.edit', [ 'id' => $student->student_id ])}}">Modifier</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
