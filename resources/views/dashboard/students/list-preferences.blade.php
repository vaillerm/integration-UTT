@extends('layouts.dashboard')

@section('title')
Bénévoles
@endsection

@section('smalltitle')
Liste de tous les bénévoles classés par préférences
@endsection

@section('content')

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Liste des bénévoles</h3>
        <div class="pull-right">
            @foreach ($filterMenu as $key => $value)
                    <a class="btn btn-xs {{ $value['class'] }}"
                        title="{{ $value['description'] }}"
                        href="{{ route('dashboard.students.list.preferences', [ 'filter' => $value['newfilter'] ]) }}">
                        {{{ $value['title'] }}}</a>
            @endforeach
        </div>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover trombi">
            <tbody>
                <tr>
                    <th>Photo</th>
                    <th>Nom complet</th>
                    <th>Téléphone</th>
                    <th>Mission</th>
                    <th>Préférences</th>
                    <th class="hidden-print">Actions</th>
                </tr>
                @foreach ($students as $student)
                    <tr>
                        <td><a href="{{ asset('/uploads/students-trombi/'.$student->student_id.'.jpg') }}"><img src="{{ asset('/uploads/students-trombi/'.$student->student_id.'.jpg') }}" alt="Photo"/></a></td>
                        <td>{{{ $student->first_name . ' ' . $student->last_name }}}</td>
                        <td>{{{ $student->phone }}}</td>
                        <td>
                            {{{ $student->mission }}}
                            @if ($student->team_accepted && $student->ce && $student->team_id)
                                <span class="label label-primary">CE</span>
                            @endif
                        </td>
                        <td>
                            @foreach ($student->volunteer_preferences as $preference)
                                <span class="label {{{ empty($filter[$preference]) ? 'label-default' : 'label-success' }}}"
                                    title="{{{ \Auth::User()::VOLUNTEER_PREFERENCES[$preference]['description'] }}}">
                                    {{{ \Auth::User()::VOLUNTEER_PREFERENCES[$preference]['title'] }}}</span>
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
