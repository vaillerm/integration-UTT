@extends('layouts.dashboard')

@section('title')
Export
@endsection

@section('smalltitle')

@endsection

@section('content')

@include('display-errors')

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Liste des parrains</h3>
    </div>
    <div class="box-body text-center">
        <a href="{{ route('dashboard.exports.referrals') }}" class="btn btn-lg btn-success">Exporter au format CSV</a>
    </div>
</div>

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Liste des fillots</h3>
    </div>
    <div class="box-body text-center">
        <a href="{{ route('dashboard.exports.newcomers') }}" class="btn btn-lg btn-success">Exporter au format CSV</a>
    </div>
</div>
@endsection
