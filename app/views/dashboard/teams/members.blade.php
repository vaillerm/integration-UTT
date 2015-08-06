@extends('layouts.dashboard')

@section('title')
<h1>
    Membres d'équipe
    <small></small>
</h1>
@endsection

@section('content')

@include('display-errors')

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
