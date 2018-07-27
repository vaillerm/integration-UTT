@extends("layouts.dashboard")

@section("title")
	Défis envoyés
@endsection

@section("smalltitle")
	La liste des défis réalisé par l'équipe
@endsection

@section("content")
	<h1>Score : {{ $score }}</h1>
	<table class="table">
		<thead>
			<tr >
				<td scope="col">Nom</td>
				<td scope="col">Statut</td>
			</tr>
		</thead>
		<tbody>
			@foreach($validations as $validation)
				<tr scope="row">
					<td>{{ $validation->challenges()->first()->name }}</td>
					<td class="{{ $validation->prettyStatus()["css"] }}">{{ $validation->prettyStatus()["content"] }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@endsection
