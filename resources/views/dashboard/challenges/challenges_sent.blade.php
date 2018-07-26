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
					@if($challenge->pivot->validated == 0 )
						<td>Le défis n'a pas encore été traité</td>
						{{ $challenge->pivot->validated  }}
					@elseif($challenge->pivot->validated == -1)
						<td>le défis a été refusé</td>
					@else
						<td>le défis a été accepté</td>
					@endif
				</tr>
			@endforeach
		</tbody>
	</table>
@endsection
