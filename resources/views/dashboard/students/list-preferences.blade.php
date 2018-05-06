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
        <h3 class="box-title">Liste des étudiants</h3>
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
                        <td>{{{ $student->mission }}}</td>
                        <td>
                            @foreach ($student->volunteer_preferences as $preference)
                                <span class="label {{{ empty($filter[$preference]) ? 'label-default' : 'label-success' }}}"
                                    title="{{{ $student::VOLUNTEER_PREFERENCES[$preference]['description'] }}}">
                                    {{{ $student::VOLUNTEER_PREFERENCES[$preference]['title'] }}}</span>
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
@endsection
