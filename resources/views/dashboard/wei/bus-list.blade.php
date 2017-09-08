@extends('layouts.dashboard')

@section('title')
    Liste des bus
@endsection

@section('smalltitle')
    Liste de tous les étudiants inscrits sur le site en tant que parrain, CE, orga assigné à un bus
@endsection

@section('content')
@foreach($buses as $bus_id=>$students)
    <div class="box box-default">
        <div class="box-header with-border">
            @if(empty($bus_id))
            <h3 class="box-title">Etudiants non affecté: {{ count($students) }} étudiants</h3>
            @else
                <h3 class="box-title">Bus n°{{ $bus_id }}: {{ count($students) }} étudiants</h3>
            @endif
        </div>
        <div class="box-body table-responsive no-padding">
            @if($students)
            <table class="table table-hover trombi">
                <tbody>
                <tr>
                    <th>Nom complet</th>
                    <th>Mail</th>
                    <th>Téléphone</th>
                    <th>Labels</th>
                    <th>Actions</th>
                </tr>
                @foreach ($students as $student)
                    <tr>
                        <td>{{{ $student->first_name . ' ' . $student->last_name }}}</td>
                        <td>{{{ $student->email }}}</td>
                        <td>{{{ $student->phone }}}</td>
                        <td>
                            @if ($student->isAdmin())
                                <span class="label label-danger">Admin</span>
                            @endif
                            @if ($student->is_newcomer)
                                <span class="label label-success">Nouveau</span>
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
                            <a class="btn btn-xs btn-warning" href="{{ route('dashboard.students.edit', [ 'id' => $student->id ])}}">Modifier</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
                @endif
        </div>
    </div>
    @endforeach
@endsection
