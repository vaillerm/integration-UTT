@extends('layouts.dashboard')

@section('title')
    Évènements
@endsection

@section('smalltitle')
    Liste de tous les évènements de la semaine d'intégration.
@endsection

@section('content')

    <div class="callout callout-info">
        <h4>Liste des événements</h4>
        <p>
            Un évènement a une date de début, une date de fin, et concerne une ou plusieurs catégories d'étudiants (nouveaux, coord, ...).
        </p>
    </div>

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Liste des évènements</h3>
            <div class="pull-right">
                Filtres :
                <a class="btn btn-xs btn-danger{{ ($filter=='admin')?' active':'' }}" href="{{ route('dashboard.students.list', [ 'filter' => 'admin' ])}}">Admin</a>
                <a class="btn btn-xs btn-dark{{ ($filter=='newcomer')?' active':'' }}" href="{{ route('dashboard.students.list', [ 'filter' => 'newcomer' ])}}">Nouveau</a>
                <a class="btn btn-xs btn-warning{{ ($filter=='orga')?' active':'' }}" href="{{ route('dashboard.students.list', [ 'filter' => 'orga' ])}}">Orga</a>
                <a class="btn btn-xs btn-success{{ ($filter=='referral')?' active':'' }}" href="{{ route('dashboard.students.list', [ 'filter' => 'referral' ])}}">Parrain</a>
                <a class="btn btn-xs btn-primary{{ ($filter=='ce')?' active':'' }}" href="{{ route('dashboard.students.list', [ 'filter' => 'ce' ])}}">CE</a>
                <a class="btn btn-xs btn-info{{ ($filter=='volunteer')?' active':'' }}" href="{{ route('dashboard.students.list', [ 'filter' => 'volunteer' ])}}">Bénévole</a>
                <a class="btn btn-xs btn-default{{ ($filter=='')?' active':'' }}" href="{{ route('dashboard.students.list')}}">Aucun</a>
            </div>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <tbody>
                    <tr>
                        <th>Nom</th>
                        <th>Début</th>
                        <th>Fin</th>
                        <th>Catégories</th>
                        <th>Actions</th>
                    </tr>
                    @foreach ($events as $event)
                        <tr>
                            <td>{{ $event->name }}</td>
                            <td>{{ $event->start_at }}</td>
                            <td>{{{ $student->end_at }}}</td>
                            <td>
                                @if (in_array('admin', $event->categories))
                                    <span class="label label-danger">Admin</span>
                                @endif
                                @if (in_array('newcomer', $event->categories))
                                    <span class="label label-dark">Nouveau</span>
                                @endif
                                @if (in_array('referral', $event->categories))
                                    <span class="label label-success">Parrain</span>
                                @endif
                                @if (in_array('ce', $event->categories))
                                    <span class="label label-primary">CE</span>
                                @endif
                                @if (in_array('volunteer', $event->categories))
                                    <span class="label label-info">Bénévole</span>
                                @endif
                                @if (in_array('orga', $event->categories))
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
