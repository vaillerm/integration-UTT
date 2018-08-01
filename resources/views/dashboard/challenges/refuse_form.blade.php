@extends("layouts.dashboard")

@section("title")
	Refuser un défis
@endsection

@section("smalltitle")
	L'équipe a été trop mauvaise ? Remet la à sa place...
@endsection


@section("content")
	<form action="{{ route("challenges.refuse", ["challengeId" => $challengeId, "teamId" => $teamId]) }}" method="post">
	<div class="form-group">
		<label for="1">Explication du refus</label>
		<input name="message" id="1" class="form-control" type="text" placeholder="Wallah la photo est nulle.">
	</div>
	<input class="form-control btn btn-danger" type="submit" value="valider">
</form>
@endsection
