@extends("layouts.dashboard")

@section("title")
	Défis envoyés
@endsection

@section("smalltitle")
	La liste des défis réalisé par l'équipe
@endsection

@section("content")
	<table class="table">
		<thead>
			<tr >
				<td scope="col">Nom</td>
				<td scope="col">Statut</td>
			</tr>
		</thead>
		<tbody>
			@foreach($challenges as $challenge)
			<tr scope="row">
				<td>{{ $challenge->name }}</td>
				@if($challenge->pivot->validated == null )
				<td>Le défis n'a pas encore été traité</td>
			@elseif(!$challenge->pivot->validated)
				<p>Le défis a été refusé</p>
				@else
					<p>Le défis a été validé !</p>
				@endif
			</tr>
			@endforeach
		</tbody>
	</table>
@endsection
