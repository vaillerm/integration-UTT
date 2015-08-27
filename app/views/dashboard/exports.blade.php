@extends('layouts.dashboard')

@section('title')
<h1>
    Export
    <small></small>
</h1>
@endsection

@section('content')

@include('display-errors')

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Liste des parrains et leurs fillots</h3>
    </div>
    <div class="box-body text-center">
        <a href="{{ route('dashboard.exports.referrals') }}" class="btn btn-lg btn-success">Exporter au format CSV</a>
    </div>
</div>
@endsection
