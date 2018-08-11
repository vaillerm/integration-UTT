@extends('layouts.dashboard')

@section('title')
Nouveaux
@endsection

@section('smalltitle')
Affichage des profils
@endsection

@section('content')

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Liste des nouveaux par progression ({{ $newcomers->count() }})</h3>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            <tbody>
                <tr>
                    <th>Nom</th>
                    <th>Branche</th>
                    <th>Progression</th>
                    <th>Inscrit UTT</th>
                    <th>Emails lus</th>
                    <th>Dernière connexion</th>
                    <th>Action</th>
                </tr>
                @foreach ($newcomers as $newcomer)
                    <tr>
                        <td>{{{ ucfirst($newcomer->first_name) . ' ' . strtoupper($newcomer->last_name) }}}</td>
                        <td>
                            {{ $newcomer->branch }}
                        </td>
                        <td>
                            @if ($newcomer->getChecklistPercent() <= 0)
                                <span class="label label-danger" title="Prochaine action {{ "\n".$newcomer->getNextCheck()['action'] }}">{{ $newcomer->getChecklistPercent() }} %</span>
                            @elseif ($newcomer->getChecklistPercent() < 100)
                                <span class="label label-warning" title="Prochaine action {{ "\n".$newcomer->getNextCheck()['action'] }}">{{ $newcomer->getChecklistPercent() }} %</span>
                            @else
                                <span class="label label-success" title="Prochaine action {{ "\n".$newcomer->getNextCheck()['action'] }}">{{ $newcomer->getChecklistPercent() }} %</span>
                            @endif
                        </td>
                        <td>
                            @if ($newcomer->student_id)
                                <span class="label label-info" title="Numéro etu: {{ $newcomer->student_id }}">Oui</span>
                            @else
                                <span class="label label-warning" title="Numéro etu: {{ $newcomer->student_id }}">Non</span>
                            @endif
                        </td>
                        <td>
                            {{ $newcomer->mailHistories->filter(function ($value, $key) {return $value->open_at != null;})->count() }}
                            / {{ $newcomer->mailHistories->count() }}
                        </td>
                        <td>
                            {{ $newcomer->last_login}}
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
