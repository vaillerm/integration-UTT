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
        <h3 class="box-title">Liste des membres de {{{ $team->name }}} ({{ $newcomers->count() }} membres)</h3>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            <tbody>
                <tr>
                    <th>Nom complet</th>
                    <th>Email</th>
                    <th>Niveau</th>
                </tr>
                @foreach ($newcomers as $newcomer)
                    <tr>
                        <td>
                            {{ $newcomer->fullName() }}
                        </td>
                        <td>
                            {{ $newcomer->email }}
                        </td>
                        <td>
                            {{ $newcomer->level }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
