@extends('layouts.dashboard')

@section('title')
    Modification d'une notification
@endsection

@section('smalltitle')
    {{ $notification_cron->title }}
@endsection


@section('content')

    <div class="box box-default">
        <div class="box-body table-responsive">
            <form action="{{ url('dashboard/notifications/'.$notification_cron->id) }}" id="form" method="post">
                {{ method_field('PUT') }}
                <div class="form-group">
                    <label for="title">Titre</label>
                    <input type="text" class="form-control" name="title" value="{{ $notification_cron->title }}">
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <input type="text" class="form-control" name="message" value="{{ $notification_cron->message }}">
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">À qui envoyer ?</label>
                    <div class="col-lg-10">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="targets[admin]" @if (( old('targets.admin') ?? in_array('admin', explode(', ', $notification_cron->targets) ?? []) )) checked="checked" @endif/>
                                <strong>Admin</strong>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="targets[orga]" @if (( old('targets.orga') ?? in_array('orga', explode(', ', $notification_cron->targets) ?? []) )) checked="checked" @endif/>
                                <strong>Orga</strong>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="targets[ce]" @if (( old('targets.ce') ?? in_array('ce', explode(', ', $notification_cron->targets) ?? []) )) checked="checked" @endif/>
                                <strong>CE</strong>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="targets[is_newcomer]" @if (( old('targets.is_newcomer') ?? in_array('is_newcomer', explode(', ', $notification_cron->targets) ?? []) )) checked="checked" @endif/>
                                <strong>Nouveau</strong>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Date d'envoi</label>
                    <input type="date" class="form-control" value="{{ date('Y-m-d', strtotime($notification_cron->send_date))  }}" name="send_date">
                    <input type="time" class="form-control" value="{{ date('H:i', strtotime($notification_cron->send_date)) }}" name="send_hour">
                </div>
                <button type="submit" class="btn btn-success">Modifier la notification</button>
            </form>
        </div>
    </div>

@endsection
