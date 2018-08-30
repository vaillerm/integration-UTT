@extends('layouts.dashboard')

@section('title')
    Évènements
@endsection

@section('smalltitle')
    Liste de tous les évènements de la semaine d'intégration.
@endsection

@section('content')

    <div class="callout callout-info">
        <h4>Liste des événements</h4>
        <p>
            Un évènement a une date de début, une date de fin, et concerne une ou plusieurs catégories d'étudiants (nouveaux, coord, ...).
        </p>
    </div>

    <div class="box-header with-border">
        <h3 class="box-title">Création d'un nouvel évènement</h3>
        <a href="{{ url('dashboard/event/create') }}" class="btn btn-box-tool">
            <i class="fa fa-plus"></i>
        </a>
    </div>

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Liste des évènements</h3>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <tbody>
                    <tr>
                        <th>Nom</th>
                        <th>Début</th>
                        <th>Fin</th>
                        <th>Catégories</th>
                        <th>Actions</th>
                    </tr>
                    @foreach ($events as $event)
                        <tr>
                            <td>{{ $event->name }}</td>
                            <td>{{ date('d/m H:i', $event->start_at) }}</td>
                            <td>{{ date('d/m H:i', $event->end_at) }}</td>
                            <td>
                                <?php $event->categories = json_decode($event->categories) ?>
                                @if (in_array('admin', $event->categories))
                                    <span class="label label-danger">Admin</span>
                                @endif
                                @if (in_array('newcomerTC', $event->categories))
                                    <span class="label label-dark">Nouveau TC</span>
                                @endif
                                @if (in_array('newcomerBranch', $event->categories))
                                    <span class="label label-dark">Nouveau branch</span>
                                @endif
                                @if (in_array('referral', $event->categories))
                                    <span class="label label-success">Parrain</span>
                                @endif
                                @if (in_array('ce', $event->categories))
                                    <span class="label label-primary">CE</span>
                                @endif
                                @if (in_array('volunteer', $event->categories))
                                    <span class="label label-info">Bénévole</span>
                                @endif
                                @if (in_array('orga', $event->categories))
                                    <span class="label label-warning">Orga</span>
                                @endif
                            </td>
                            <td>
                                <a class="btn btn-xs btn-warning" href="{{ url('dashboard/event/edit/'.$event->id) }}">Modifier</a>
                                <form action="{{ url('dashboard/event/'.$event->id) }}" method="post">
                                    {{ method_field('DELETE') }}
                                    <button class="btn btn-xs btn-danger" type="submit">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
