@extends('layouts.dashboard')

@section('title')
Bénévoles
@endsection

@section('smalltitle')
Liste de tous les bénévoles filtrée par rôle demandé
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/volunteer_filter.css') }}">
@endsection

@section('content')

<div class="box box-default volunteer-filter">
    <div class="box-header with-border">
        <h3 class="box-title">Filtres</h3>

        <div class="pull-right align-left" id="volunteer-filter-status"></div>

    </div>
    <div class="box-body">
        <input type="text" class="form-control" placeholder="Ajout d'un filtre" id="volunteer-filter-list-input">
        <ul class="list-group" id="volunteer-filter-list-container">
            @foreach ($filterMenu as $filter)
                <li class="list-group-item" data-name="{{ $filter['name'] }}" data-id="{{ $filter['id'] }}">
                    <button class="btn btn-xs btn-success btn-squared-sign volunteer-filter-add" data-type="whitelist" title="Whitelister">&plus;</button>
                    <button class="btn btn-xs btn-danger btn-squared-sign volunteer-filter-add" data-type="blacklist" title="Blacklister">&times;</button>
                    <span>{{ $filter['name'] }}</span> :
                    <small>{{ $filter['description'] }}</small>
                </li>
            @endforeach
        </ul>
    </div>
</div>
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Liste des bénévoles</h3>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover trombi">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Nom complet</th>
                    <th>Téléphone</th>
                    <th>CE/Orga</th>
                    <th>Rôles demandés (gris) et assignés (bleu)</th>
                    <th class="hidden-print">Actions</th>
                </tr>
            </thead>
            <tbody id="volunteer-list-body">
                @foreach ($students as $student)
                    @php
                        $filteringIds = $student->getAllRoles()->pluck('id')->toArray();
                        if ($student->team_accepted && $student->ce && $student->team_id) {
                            $filteringIds[] = 'ce';
                        }
                        if ($student->orga) {
                            $filteringIds[] = 'orga';
                        }
                    @endphp
                    <tr data-filtering-ids="{{ implode('|', $filteringIds) }}">
                        <td><a href="{{ asset('/uploads/students-trombi/'.$student->student_id.'.jpg') }}"><img src="{{ asset('/uploads/students-trombi/'.$student->student_id.'.jpg') }}" alt="Photo"/></a></td>
                        <td>{{{ $student->first_name . ' ' . $student->last_name }}}</td>
                        <td>{{{ $student->phone }}}</td>
                        <td>
                            @if ($student->orga)
                                <span class="label label-warning">Orga</span>
                            @endif
                            @if ($student->team_accepted && $student->ce && $student->team_id)
                                <span class="label label-primary">CE</span>
                            @endif
                        </td>
                        <td>
                            @foreach ($student->getAllRoles() as $role)
                                <span class="label {{{ $student->assignedRoles->contains('id', $role->id) ? 'label-primary' : 'label-default' }}}"
                                    title="{{{ $role->description }}}">
                                    {{{ $role->name  }}}</span>
                            @endforeach
                        </td>
                        <td class="hidden-print">
                            <a class="btn btn-xs btn-warning" href="{{ route('dashboard.students.edit', [ 'id' => $student->id ])}}">Modifier</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Listes des contacts filtrés</h3>
    </div>
    <div class="box-body">
        <h4>Emails</h4>
        <textarea class="form-control" readonly>@foreach ($students as $student){{ $student->email . "\n" }}@endforeach</textarea>

        <h4>Téléphones</h4>
        <textarea class="form-control" readonly>@foreach ($students as $student){{ $student->phone . "\n" }}@endforeach</textarea>
    </div>
</div>
@endsection


@section('js')
    <script type="text/javascript" src="{{ asset('js/volunteer_filter.js') }}"></script>
@endsection