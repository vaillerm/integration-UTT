@extends('layouts.dashboard')

@section('title')
    Creation d'un type de permanence
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/student_autocomplete.css') }}">
@endsection

@section('content')

    <div class="callout callout-info">
        <h4>Création d'un nouveau type de permanence</h4>
        <p>
            Pour créér un type de permanence, renseignez son nom, par exemple "Perm bouffe", "Perm Sécu",...
            Une description qui servira de description par défaut lors de la création de la perm (#gaindetemps),
            Les points de la perm, qui correspond aux nombres de point que donne une perm. Plus une perm est chiante,
            plus elle donne de points. Plus une personne fait des perms chiante, plus elle accumule de point et plus elle a de chances
            d'aller au WEI. ATTENTION : ce chiffre ne doit pas être connu publiquement, cela ne regarde que les orgas.
        </p>
    </div>

    <div class="box box-default">
        <div class="box-body table-responsive">
            <form autocomplete="off" action="{{ url('dashboard/permType') }}" method="post" id="form">
                <div class="form-group">
                    <label for="name">Nom</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                </div>
                <div class="form-group">
                    <label for="name">Description</label>
                    <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="name">Points</label>
                    <input type="number" name="points" class="form-control" value="{{ old('points') }}">
                </div>

                <div class="form-group" id="student_autocomplete_container">
                    <label for="name">Ajouter des respos par nom/prénom</label>
                    <input type="text" class="form-control" id="student_autocomplete">
                </div>
                <div id="student_autocomplete_matches_container">
                    <div id="student_autocomplete_matches"></div>
                </div>
                <ul class="list-group" id="student_autocomplete_selected_container"></ul>

                <button type="submit" class="btn btn-success" id="formSubmit">Créer le type de permanence</button>
            </form>
        </div>
    </div>

@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('js/student_autocomplete.js') }}"></script>
@endsection