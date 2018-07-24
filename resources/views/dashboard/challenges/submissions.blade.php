@extends("layouts.dashboard")

@section("title")
	Les défis soumis
@endsection

@section("smalltitle")
	il faut valider ces défis envoyés par les équipes !	
@endsection

@section("content")
	<div class="box bow-default">
		<div class="box-header with-border">
			<h3 class="box-title">Liste des validations</h3>
		</div>
		<div class="box-body table-responsive no-padding">
			<table class="table table-stripped">
				<thead>
					<tr>
						<th>Nom de l'équipe</th>
						<th>Nom du défis</th>
						<th>Preuve</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@forelse($teams as $team)
						@if($team->hasPendingValidations())
							@foreach($team->getPendingValidations()->get() as $challenge)
								<tr>
									<td>{{ $team->name }}</td>
									<td>{{ $challenge->name }}</td>
									<td> <img src="{{ $challenge->pivot->pic_url }}" class="img-fluid rounded" alt="Image de validation du défis"> </td>
									<td>
										<form method="post" action={{ route("challenges.accept", ["challengeId" => $challenge->id, "teamId" => $team->id]) }}><input class="btn btn-primary" type="submit" value="Valider"></form>
										<form action=""><input class="btn btn-danger" type="submit" value="Refuser"> </form>
									</td>
								</tr>
							@endforeach
						@endif
					@empty
						<p>Aucune validation</p>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
@endsection
