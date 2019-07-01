@extends('layouts.dashboard')

@section('title')
    Creation d'un checkin pré-rempli
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/student_autocomplete.css') }}">
@endsection

@section('content')

    <div class="callout callout-info">
        <h4>Création d'un nouveau checkin</h4>
        <p>
            Pour un créer un checkin, renseignez un nom à celui et choisissez les étudiants qui feront partit de ce checkin.
        </p>
    </div>

    <div class="box box-default">
        <div class="box-body table-responsive">
            <form autocomplete="off" action="{{ url('dashboard/checkin') }}" method="post" id="form">
                <div class="form-group">
                    <label for="name">Nom du checkin</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                </div>

                <div class="form-group" id="student_autocomplete_container">
                    <label for="name">Ajouter par nom</label>
                    <input type="text" class="form-control" id="student_autocomplete">
                </div>
                <div id="student_autocomplete_matches_container">
                    <div id="student_autocomplete_matches"></div>
                </div>
                <ul class="list-group" id="student_autocomplete_selected_container"></ul>

                <button type="submit" id="formSubmit" class="btn btn-success">Créer le checkin</button>
            </form>
        </div>
    </div>

@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('js/student_autocomplete.js') }}"></script>
@endsection
