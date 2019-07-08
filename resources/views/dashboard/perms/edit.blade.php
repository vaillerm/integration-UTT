@extends('layouts.dashboard')

@section('title')
    Modification d'une permanence de type {{ $perm->type->name }}
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/student_autocomplete.css') }}">
@endsection

@section('content')
    <div class="box box-default">
        <div class="box-body table-responsive">
            <form autocomplete="off" action="{{ url('dashboard/perm/'.$perm->id) }}" method="post" id="form">
                {{ method_field('PUT') }}
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" class="form-control">{{ $perm->description }}</textarea>
                </div>
                <div class="form-group">
                    <label for="place">Lieu</label>
                    <input type="text" name="place" class="form-control" value="{{ $perm->place }}">
                </div>
                <div class="form-group">
                    <label for="nbr_permanenciers">Nombre de permanenciers (sans compter les respos)</label>
                    <input type="number" name="nbr_permanenciers" class="form-control" value="{{ $perm->nbr_permanenciers }}">
                </div>
                <div class="form-group">
                    <label>Début (date et heure)</label>
                    <input type="date" class="form-control" value="{{ date('Y-m-d', $perm->start)  }}" name="start_date">
                    <input type="time" class="form-control" value="{{ date('H:i', $perm->start) }}" name="start_hour">
                </div>
                <div class="form-group">
                    <label>Fin (date et heure)</label>
                    <input type="date" class="form-control" value="{{ date('Y-m-d', $perm->end) }}" name="end_date">
                    <input type="time" class="form-control" value="{{ date('H:i', $perm->end) }}" name="end_hour">
                </div>

                <div class="form-group">
                    <label for="free_join">Inscription libre</label>
                    <input type="checkbox" id="free_join" name="free_join" @if ($perm->free_join) checked="checked" @endif/>
                </div>

                <div class="form-group" id="student_autocomplete_container">
                    <label for="name">Ajouter des respos par nom/prénom</label>
                    <input type="text" class="form-control" id="student_autocomplete">
                </div>
                <div id="student_autocomplete_matches_container">
                    <div id="student_autocomplete_matches"></div>
                </div>
                <ul class="list-group" id="student_autocomplete_selected_container">

                  @foreach ($perm->respos as $respo)
                      <li class="list-group-item" data-id="{{ $respo->id }}">
                          {{ $respo->first_name.' '.$respo->last_name }}
                          <span class="autocomplete_remove">x</span>
                      </li>
                  @endforeach

                </ul>

                <button type="submit" class="btn btn-success" id="formSubmit">Modifier la permanence</button>
            </form>
        </div>
    </div>

@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('js/student_autocomplete.js') }}"></script>
@endsection