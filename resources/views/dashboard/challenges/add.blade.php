@extends('layouts.auto')

@section('title')
    Défis
@endsection

@section('smalltitle')
    Formulaire pour ajouter un défi que les différentes équipes pourront réaliser
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3>Ajouter un défi</h3>
        </div>
        @component("dashboard.challenges.form", ["challenge" => null])
            {{  route("challenges.add") }}
        @endcomponent
    </div>
@endsection
