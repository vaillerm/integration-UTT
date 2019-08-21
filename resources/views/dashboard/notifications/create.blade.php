@extends('layouts.dashboard')

@section('title')
    Creation d'une notification
@endsection


@section('content')

    <div class="callout callout-info">
        <h4>Création d'une notification</h4>
        <p>
            Pour un créer une notification, renseignez un titre, un message et choisissez les destinataires.
            Ensuite précisez la date d'envoie.
        </p>
    </div>

    <div class="box box-default">
        <div class="box-body table-responsive">
            <form action="{{ url('dashboard/notifications') }}" method="post" id="form">
                <div class="form-group">
                    <label for="title">Titre</label>
                    <input type="text" class="form-control" name="title" value="{{ old('title') }}">
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <input type="text" class="form-control" name="message" value="{{ old('message') }}">
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">À qui envoyer ?</label>
                    <div class="col-lg-10">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="targets[admin]" checked="checked"/>
                                <strong>Admin</strong>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="targets[orga]" checked="checked"/>
                                <strong>Orga</strong>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="targets[ce]" checked="checked"/>
                                <strong>CE</strong>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="targets[is_newcomer]" checked="checked"/>
                                <strong>Nouveau</strong>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Date d'envoi</label>
                    <input type="date" class="form-control" value="{{ date('Y-m-d')}}" name="send_date">
                    <input type="time" class="form-control" value="{{ date('H:i')}}" name="send_hour">
                </div>
                <button type="submit" id="formSubmit" class="btn btn-success">Créer la notification</button>
            </form>
        </div>
    </div>

@endsection
