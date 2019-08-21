@extends('layouts.dashboard')

@section('title')
    Notifications
@endsection

@section('smalltitle')
    Liste de toutes les notifications
@endsection

@section('content')

    <div class="callout callout-info">
        <h4>Liste des notifications</h4>
    </div>

    <div class="box-header with-border">
        <h3 class="box-title">Création d'une nouvelle notification</h3>
        <a href="{{ url('dashboard/notifications/create') }}" class="btn btn-box-tool">
            <i class="fa fa-plus"></i>
        </a>
    </div>

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Liste des notifications</h3>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <tbody>
                    <tr>
                        <th>Titre</th>
                        <th>Message</th>
                        <th>Envoyée</th>
                        <th>Date d'envoie</th>
                        <th>Envoyée par</th>
                        <th>Actions</th>
                    </tr>
                    @foreach ($notification_crons as $notification_cron)
                        <tr>
                            <td>{{ $notification_cron->title }}</td>
                            <td>{{ $notification_cron->message }}</td>
                            <td>{{ $notification_cron->is_sent ? 'Oui' : 'Non' }}</td>
                            <td>{{ $notification_cron->send_date }}</td>
                            <td>@if($notification_cron->createdBy != null){{ $notification_cron->createdBy->first_name }} {{ $notification_cron->createdBy->last_name }}@endif</td>
                            <td>
                                <a class="btn btn-xs btn-warning" href="{{ url('dashboard/notifications/edit/'.$notification_cron->id) }}">Modifier</a>
                                <form action="{{ url('dashboard/notifications/'.$notification_cron->id) }}" method="post">
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
