@extends('layouts.dashboard')

@section('title')
    Ajout de permanenciers à la perm {{ $perm->type->name }}
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/student_autocomplete.css') }}">
@endsection

@section('content')

    <div class="callout callout-info">
    <h4>Ajout de permanenciers à la perm {{ $perm->type->name }}</h4>
        <p>
            Sélectionnez des étudiants à ajouter à la perm 
        </p>
    </div>

    <div class="box box-default">
        <div class="box-body table-responsive">
            <form autocomplete="off" action="{{ url('dashboard/perm/'.$perm->id.'/users') }}" method="post" id="form">
                
                <div class="form-group" id="student_autocomplete_container">
                    <label for="name">Ajouter des respos par nom/prénom</label>
                    <input type="text" class="form-control" id="student_autocomplete">
                </div>
                <div id="student_autocomplete_matches_container">
                    <div id="student_autocomplete_matches"></div>
                </div>
                <ul class="list-group" id="student_autocomplete_selected_container"></ul>

                <button type="submit" class="btn btn-success" id="formSubmit">Créer la permanence</button>
            </form>
        </div>
    </div>

@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('js/student_autocomplete.js') }}"></script>
@endsection