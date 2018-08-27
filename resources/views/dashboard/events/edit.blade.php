@extends('layouts.dashboard')

@section('title')
    Modification d'un évènement
@endsection

@section('smalltitle')
    {{ $event->name }}
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-body table-responsive">
            <form action="{{ url('dashboard/event/'.$event->id) }}" method="post">
                {{ method_field('PUT') }}
                <div class="form-group">
                    <label for="name">Nom</label>
                    <input type="text" class="form-control" name="name" value="{{ $event->name }}">
                </div>
                <div class="form-group">
                    <label for="name">Description</label>
                    <textarea name="description" class="form-control">{{ $event->description }}</textarea>
                </div>
                <div class="form-group">
                    <label for="place">Lieu</label>
                    <input type="text" class="form-control" name="place" value="{{ $event->place }}">
                </div>
                <div class="form-group">
                    <label>Début (date et heure)</label>
                    <input type="date" class="form-control" value="{{ date('Y-m-d', $event->start_at)  }}" name="start_at_date">
                    <input type="time" class="form-control" value="{{ date('H:i', $event->start_at) }}" name="start_at_hour">
                </div>
                <div class="form-group">
                    <label>Fin (date et heure)</label>
                    <input type="date" class="form-control" value="{{ date('Y-m-d', $event->end_at) }}" name="end_at_date">
                    <input type="time" class="form-control" value="{{ date('H:i', $event->end_at) }}" name="end_at_hour">
                </div>

                <?php $event->categories = json_decode($event->categories) ?>
                <div class="checkbox">
                    <label><input type="checkbox" name="categories[]" value="admin" @if(in_array('admin', $event->categories)) checked @endif>Admin</label>
                </div>
                <div class="checkbox">
                  <label><input type="checkbox" name="categories[]" value="newcomerTC" @if(in_array('newcomerTC', $event->categories)) checked @endif>Nouveau TC</label>
                </div>
                <div class="checkbox">
                  <label><input type="checkbox" name="categories[]" value="newcomerBranch" @if(in_array('newcomerBranch', $event->categories)) checked @endif>Nouveau Branche</label>
                </div>
                <div class="checkbox">
                    <label><input type="checkbox" name="categories[]" value="volunteer" @if(in_array('volunteer', $event->categories)) checked @endif>Volontaire</label>
                </div>
                <div class="checkbox">
                    <label><input type="checkbox" name="categories[]" value="orga" @if(in_array('orga', $event->categories)) checked @endif>Orga</label>
                </div>
                <div class="checkbox">
                    <label><input type="checkbox" name="categories[]" value="ce" @if(in_array('ce', $event->categories)) checked @endif>Chef d'équipe</label>
                </div>
                <div class="checkbox">
                    <label><input type="checkbox" name="categories[]" value="referral" @if(in_array('referral', $event->categories)) checked @endif>Parrain</label>
                </div>

                <button type="submit" class="btn btn-success">Modifier l'évènement</button>
            </form>
        </div>
    </div>

@endsection
