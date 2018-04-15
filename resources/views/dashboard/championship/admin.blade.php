@extends('layouts.dashboard')

@section('title')
Factions
@endsection

@section('smalltitle')
Gestion des points
@endsection

@section('content')

<form action="{{ route('dashboard.championship.edit') }}" method="post">
@foreach ($factions as $faction)
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">{{ $faction->name }}</h3>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <tr>
                    <th>Équipe</th>
                    <th>Points</th>
                </tr>
                @foreach($faction->teams as $team)
                    <tr>
                        @if($team->name != null)
                        <td style="width:50%">{{{ $team->name }}}</td>
                        @else
                        <td style="width:50%">Équipe sans nom {{{ $team->id }}}</td>
                        @endif
                        <td style="width:50%">
                            <input type="number" min="0" name="team-{{ $team->id }}" value="{{ $team->points }}">
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endforeach
<input type="submit" class="btn form-control btn-success" value="Modifier les points">
</form>

@endsection
