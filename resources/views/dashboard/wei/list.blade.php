@extends('layouts.dashboard')

@section('title')
Inscriptions au WEI
@endsection

@section('smalltitle')
Liste de toutes les personnes inscrits au WEI
@endsection

@section('content')

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Liste</h3>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover trombi">
            <tbody>
                <tr>
                    <th>ID</th>
                    <th>Nom complet</th>
                    <th>Téléphone</th>
                    <th>Type</th>
                    <th>WEI</th>
                    <th>Sandwich</th>
                    <th>Caution</th>
                    <th>Autorisation</th>
                    <th>CheckIn</th>
                    <th class=".hidden-print">Actions</th>
                </tr>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{{ $user->first_name . ' ' . $user->last_name }}}</td>
                        <td>{{{ $user->phone }}}</td>
                        <td>
                            @if ($user->orga)
                                <span class="label label-warning">Orga</span>
                            @elseif ($user->ce)
                                <span class="label label-primary">CE</span>
                            @elseif ($user->volunteer)
                                <span class="label label-info">Bénévole/Ancien</span>
                            @elseif (!$user->student)
                                <span class="label label-success">Nouveau</span>
                            @else
                                <span class="label label-danger">PAS BÉNÉVOLE !</span>
                            @endif
                        </td>
                        <td>
                            @if($user->weiPayment)
                                @if($user->weiPayment->state == 'paid')
                                    <span class="label label-success">Payé</span>
                                @else
                                    <span class="label label-danger">Non ({{$user->weiPayment->state}})</span>
                                @endif
                            @else
                                <span class="label label-danger">Non</span>
                            @endif
                        </td>
                        <td>
                            @if($user->sandwichPayment)
                                @if($user->sandwichPayment->state == 'paid')
                                    <span class="label label-success">Payé</span>
                                @else
                                    <span class="label label-danger">Non ({{$user->sandwichPayment->state}})</span>
                                @endif
                            @else
                                <span class="label label-default">Non</span>
                            @endif
                        </td>
                        <td>
                            @if($user->guaranteePayment)
                                @if($user->guaranteePayment->state == 'paid')
                                    <span class="label label-success">Payé</span>
                                @else
                                    <span class="label label-danger">Non ({{$user->guaranteePayment->state}})</span>
                                @endif
                            @else
                                <span class="label label-danger">Non</span>
                            @endif
                        </td>
                        <td>
                            @if($user->parent_authorization)
                                <span class="label label-success">OK</span>
                            @elseif($user->isUnderage())
                                <span class="label label-danger">Non</span>
                            @endif
                        </td>
                        <td>
                            @if($user->checkin)
                                <span class="label label-success">OK</span>
                            @else
                                <span class="label label-danger">Non</span>
                            @endif
                        </td>
                        <td class=".hidden-print">
                            <a class="btn btn-xs btn-info" href="{{ route('dashboard.students.edit', [ 'id' => $user->id ])}}">Utilisateur</a>
                            @if($user->isStudent())
                                <a class="btn btn-xs btn-warning" href="{{ route('dashboard.wei.student.edit', [ 'id' => $user->id ])}}">WEI</a>
                            @else
                                <a class="btn btn-xs btn-warning" href="{{ route('dashboard.wei.newcomer.edit', [ 'id' => $user->id ])}}">WEI</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
