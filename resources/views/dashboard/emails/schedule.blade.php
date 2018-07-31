@extends('layouts.dashboard')

@section('title')
Programmation de l'envoi d'un mail
@endsection

@section('smalltitle')
@endsection

@section('content')
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Envoi d'un email</h3>
    </div>
    <div class="box-body">
        <form class="form-horizontal" action="{{ route('dashboard.email.schedule.submit', $template->id) }}" method="post" enctype="multipart/form-data">

            <div class="form-group">
                <label for="student_id" class="col-lg-2 text-right">Modèle de mail</label>
                <div class="col-lg-10">
                    {{ $template->subject }} ({{ $template->id }})
                </div>
            </div>

            <div class="form-group">
                <label for="lists" class="col-lg-2 control-label">Destinataires<br/> (Ctrl+Click pour la selection multiple)</label>
                <div class="col-lg-10">
                    <select multiple name="lists[]" class="form-control" size="{{ count($lists) }}">
                        @foreach ($lists as $id => $description)
                                <option value="{{$id}}"
                                @if (in_array($id, old('lists') ?? [])) selected="selected" @endif >
                                    {{ $description }}
                                </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="sex" class="col-lg-2 control-label">Securité anti-doublon</label>
                <div class="col-lg-10">
                    <select name="unique_send" class="form-control">
                        <option value="1" @if (old('unique_send') === 1) selected="selected" @endif >Ne pas envoyer le mail à ceux qui ont déjà reçu ce modèle de mail</option>
                        <option value="0" @if (old('unique_send') === 0) selected="selected" @endif >Forcer l'envoi à tout le monde</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="subject" class="col-lg-2 control-label">Date d'envoi</label>
                <div class="col-lg-10">
                    <input type="date" name="send_date_date" value="{{ old('send_date_date') ?? '' }}" placeholder="AAAA-MM-DD" />
                </div>
            </div>

            <div class="form-group">
                <label for="subject" class="col-lg-2 control-label">Heure d'envoi</label>
                <div class="col-lg-10">
                    <input type="time" name="send_date_time" value="{{ old('send_date_time') ?? '12:00' }}" placeholder="hh:mm" />
                </div>
            </div>

            <input type="submit" class="btn btn-success form-control" value="Mettre à jour les informations" />
        </form>
    </div>
</div>
@endsection
