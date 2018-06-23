@extends('layouts.dashboard')

@section('title')
	Défis
@endsection

@section('smalltitle')
	Formulaire pour ajouter un défis que les différentes équipes pourront réaliser
@endsection

@section('content')

<div class="box box-default">
<div class="box-header with-border">
	<h3>Ajouter un défis</h3>
</div>
<form action={{ route("challenges.add") }} method="post">
	<div class="form-group">
		<label for="name">Nom du défis</label>
		<input id="name" class="form-control" type="text" name="name" required> 
	</div>

	<div class="form-group">
		<label for="desc">Description</label>
		<input id="desc" class="form-control" type="textarea" name="description">
	</div>

	<div class="form-group">
		<label for="points">Nombre de points accordés</label>
		<input class="form-control" type="number" id="pts" name="points"><br>
	</div>

	<div class="form-group">
		<label for="deadline">Date limite pour valider le défis</label>
		<input id="deadline" class="form-control" type="date" name="deadline">
	</div>

	<input type="submit" class="btn btn-primary form-control" value="Ajouter un défis !">
</form>
</div>
@endsection
