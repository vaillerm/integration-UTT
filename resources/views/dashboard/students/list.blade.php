@extends('layouts.dashboard')

@section('title')
<h1>
    Etudiants
    <small>Liste de tous les étudiants inscrits sur le site en tant que parrain, CE, orga, ...</small>
</h1>
@endsection

@section('content')

@include('display-errors')

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
                    <th>Semestre</th>$
                    <th>Labels</th>
                    <th>Actions</th>
                </tr>
                @foreach ($students as $student)
                    <tr>
                        <td><a href="{{ @asset('/uploads/students-trombi/'.$student->student_id.'.jpg') }}"><img src="{{ @asset('/uploads/students-trombi/'.$student->student_id.'.jpg') }}" alt="Photo"/></a></td>
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
                            <!-- <a class="btn btn-xs btn-warning" href="{{ route('dashboard.students.list')}}">Modifier</a> -->
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
