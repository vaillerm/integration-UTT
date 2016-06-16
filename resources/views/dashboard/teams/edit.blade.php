@extends('layouts.dashboard')

@section('title')
<h1>
    Équipes
    <small>Gestion des équipes</small>
</h1>
@endsection

@section('content')

@include('display-errors')

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Modification d'une équipe : {{{ $team->name }}}</h3>
    </div>
    <div class="box-body text-center">
        <form class="" action="{{ route('dashboard.teams.update', ['id' => $team->id ]) }}" method="post">
            <input type="text" name="name" value="{{{ $team->name }}}" class="form-control text-center" placeholder="Nom de l'équipe" required>
            <input type="text" name="img_url" value="{{{ $team->img_url }}}" class="form-control text-center" placeholder="Lien DIRECT vers l'image (http://imgur.com/______.png)">
            <textarea name="description" class="form-control"  placeholder="Message de l'équipe (max 500 caractères)." cols="30" rows="10">{{{ $team->description }}}</textarea>
            <input type="submit" class="btn btn-success form-control" value="Modifier">
        </form>
    </div>
</div>
@endsection
