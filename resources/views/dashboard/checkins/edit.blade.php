@extends('layouts.dashboard')

@section('title')
    Modification d'un checkin
@endsection

@section('smalltitle')
    {{ $checkin->name }}
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/student_autocomplete.css') }}">
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-body table-responsive">
            <form autocomplete="off" action="{{ url('dashboard/checkin/'.$checkin->id) }}" id="checkinForm" method="post">
                {{ method_field('PUT') }}
                <div class="form-group">
                    <label for="name">Nom du checkin</label>
                    <input type="text" class="form-control" name="name" value="{{ $checkin->name }}">
                </div>

                <div class="form-group" id="student_autocomplete_container">
                    <label for="name">Ajouter par nom</label>
                    <input type="text" class="form-control" id="student_autocomplete">
                </div>
                <div id="student_autocomplete_matches_container">
                    <div id="student_autocomplete_matches"></div>
                </div>
                <ul class="list-group" id="student_autocomplete_selected_container">
                    @foreach($checkin->students as $student)
                        <li class="list-group-item" data-id="{{ $student->id }}">
                            {{ $student->first_name }} {{ $student->last_name }}
                            <span class="autocomplete_remove">x</span>
                        </li>
                    @endforeach
                </ul>

                <button type="submit" id="checkinFormSubmit" class="btn btn-success">Modifier le checkin</button>
            </form>
        </div>
    </div>

@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('js/student_autocomplete.js') }}"></script>
@endsection
