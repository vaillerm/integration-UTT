@extends('layouts.dashboard')

@section('title')
Membres d'équipe
@endsection

@section('smalltitle')

@endsection

@section('content')

@if ($alones->count())
<div class="box box-default collapsed-box">
    <div class="box-header with-border">
        <h3 class="box-title">Ajouter un membre</h3>
        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
    </div>
    <div class="box-body text-center">
        <form class="" action="{{ route('dashboard.teams.members', [$team->id]) }}" method="post">
            <select class="form-control" name="newcomer">
                @foreach ($alones as $alone)
                    <option value="{{ $alone->id }}">{{{ $alone->fullName() }}}</option>
                @endforeach
            </select>
            <br>
            <input type="submit" class="btn btn-success form-control" value="Ajouter à l'équipe">
        </form>
    </div>
</div>
@endif

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Liste des membres de
            @if($team->name != null)
                {{{ $team->name }}}
            @else
                Équipe sans nom {{{ $team->id }}}
            @endif
            ({{ $newcomers->count() }} membres)</h3>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            <tbody>
                <tr>
                    <th>Nom complet</th>
                    <th>Branche</th>
                    <th>Actions</th>
                </tr>
                @foreach ($newcomers as $newcomer)
                    <tr>
                        <td>
                            {{ $newcomer->fullName() }}
                        </td>
                        <td>
                            {{ $newcomer->branch }}
                        </td>
                        <td>
                            <a class="btn btn-xs btn-warning" href="{{ route('dashboard.students.edit', [ 'id' => $newcomer->id ])}}">Modifier</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
