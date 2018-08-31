@extends("layouts.newcomer")

@section("content")
    @foreach($factions as $faction) 
        {{ $faction->name }} : {{ $faction->score() }}
    @endforeach
@endsection
