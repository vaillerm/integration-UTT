@extends("layouts.auto")

@section("title")
    Modifier un défi
@endsection

@section("smalltitle")
    Tu as fais une erreur en ajoutant un nouveau défi ? pas de problème, corrige donc :)
@endsection

@section("content")
    @component("dashboard.challenges.form", ["challenge" => $challenge])
        {{ route("challenges.modify", ["challengeId"=> $challenge->id]) }}
    @endcomponent
@endsection
