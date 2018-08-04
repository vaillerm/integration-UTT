@extends('layouts.dashboard')

@section('title')
Mails
@endsection

@section('smalltitle')
Envoi de mails en maaasse
@endsection

@section('js')
<script type="text/javascript">
    function updateForm() {
        if(document.getElementById('form_template').value == 'default') {
            document.getElementById('form_content').disabled = false;
        }
        else {
            document.getElementById('form_content').disabled = true;
        }
    }
    document.getElementById('form_template').addEventListener('change', updateForm);
    updateForm();
</script>
@endsection


@section('content')

@if(Request::old())
    <div class="box box-default">
@else
    <div class="box box-default collapsed-box">
@endif
    <div class="box-header with-border">
        <h3 class="box-title">Nouvelle modèle de mail</h3>
        <button class="btn btn-box-tool" data-widget="collapse">
            @if(Request::old())
                <i class="fa fa-minus"></i>
            @else
                <i class="fa fa-plus"></i>
            @endif
        </button>
    </div>
    <div class="box-body">
        <form class="" action="{{ route('dashboard.email.create') }}" method="post">
            <input type="text" name="subject" class="form-control" placeholder="Sujet" value="{{ old('subject') }}">
            <select id="form_template" name="template" class="form-control">
                <option value="default" @if (old('template') == 'default') selected="selected" @endif >Personnalisé HTML</option>
                @foreach ($file_templates as $template)
                    @if (substr($template->getFilename(), -10) == '.blade.php' && $template->getFilename() != 'default.blade.php')
                        <option value="{{$template->getBasename('.blade.php')}}"
                            @if (old('template') == $template->getBasename('.blade.php')) selected="selected" @endif >
                            {{ $template->getBasename('.blade.php') }}
                        </option>
                    @endif
                @endforeach
            </select>
            <textarea id="form_content" name="content" class="form-control" placeholder="Contenu personnalisé html">{{ old('content') }}</textarea>
            <select name="isPublicity" class="form-control">
                <option value="0" @if (old('isPublicity') == 0) selected="selected" @endif >Email d'information</option>
                <option value="1" @if (old('isPublicity') == 1) selected="selected" @endif >Email publicitaire</option>
            </select>
            <input type="submit" class="btn btn-success form-control" value="Créer le modèle">
        </form>
    </div>
</div>

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Modèles de mail</h3>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            <tbody>
                <tr>
                    <th>id</th>
                    <th>Sujet</th>
                    <th>Template</th>
                    <th>Publicité</th>
                    <th>Action</th>
                </tr>
                @foreach ($mail_templates as $email)
                    <tr>
                        <td>
                            {{$email->id}}
                        </td>
                        <td>
                            {{$email->subject}}
                        </td>
                        <td>{{$email->template}}</td>
                        <td>
                            @if($email->isPublicity)
                                <span class="label label-danger">Oui</span>
                            @else
                                <span class="label label-success">Non</span>
                            @endif
                        </td>
                        <td>
                            <a class="btn btn-xs btn-info" href="{{ route('dashboard.emails.templatepreview', ['id'=>$email->id]) }}">Prévisualiser</a>
                            <a class="btn btn-xs btn-warning" href="{{ route('dashboard.email.edit', ['id' => $email->id]) }}">Modifier</a>
                            <a class="btn btn-xs btn-success" href="{{ route('dashboard.email.schedule', $email->id) }}">Programmer l'envoi</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Status des mails programmés</h3>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            <tbody>
                <tr>
                    <th>Modèle</th>
                    <th>Destinataires</th>
                    <th>Date d'envoi</th>
                    <th>Anti doublon</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                @foreach ($mail_crons as $cron)
                    <tr>
                        <td>
                            {{$cron->name}}<br/>
                            <strong>Modèle</strong> : <em>{{$cron->mail_template->subject}}</em> ({{ $cron->mail_template->id }})<br/>
                        </td>
                        <td>
                            @foreach (explode(',', $cron->lists) as $i => $list)
                                - {{$listToFrench[$list]}}<br/>
                            @endforeach
                        </td>
                        <td>{{$cron->send_date}}</td>
                        <td>
                            @if($cron->unique_send)
                                <span class="label label-success">Oui</span>
                            @else
                                <span class="label label-danger">Non</span>
                            @endif
                        </td>
                        <td>
                            @if(!$cron->is_sent)
                                <span class="label label-info">En attente</span><br/>
                                <span title="{{ implode("\n", array_keys($updatedRecipients[$cron->id] ?? []))}}">
                                    <strong>Destinaires</strong> : {{ count($updatedRecipients[$cron->id] ?? []) }}
                                </span>
                            @elseif($cron->count_sent != $cron->lists_size || $cron->count_pending != 0 || $cron->count_sending != 0 || $cron->count_error != 0)
                                @if ($cron->count_error != 0)
                                    <span class="label label-danger">Erreur</span><br/>
                                @else
                                    <span class="label label-warning">En cours</span><br/>
                                @endif
                                <strong>Envoyés</strong> : {{ $cron->count_sent }} / {{ $cron->lists_size }}<br/>
                                <strong>En attente</strong> : {{ $cron->count_pending }}<br/>
                                <strong>En cours</strong> : {{ $cron->count_sending }}<br/>
                                <strong>Erreurs</strong> : {{ $cron->count_error }}<br/>
                                <strong>Lu</strong> : {{ $cron->count_opened }} / {{ $cron->count_sent }}<br/>
                                <strong title="Personnes qui aurraient reçu cet email s'il avait été envoyé plus tard:{{ "\n".implode("\n", array_keys($updatedRecipients[$cron->id] ?? []))}}">
                                    Non prévu</strong> : {{ count($updatedRecipients[$cron->id] ?? []) }}
                            @else
                                <span class="label label-success">Envoyé</span><br/>
                                <strong>Lu</strong> : {{ $cron->count_opened }} / {{ $cron->count_sent }}<br/>
                                <strong title="Personnes qui aurraient reçu cet email s'il avait été envoyé plus tard:{{ "\n".implode("\n", array_keys($updatedRecipients[$cron->id] ?? []))}}">
                                    Non prévu</strong> : {{ count($updatedRecipients[$cron->id] ?? []) }}
                            @endif
                        </td>
                        <td>
                            @if(!$cron->is_sent)
                                <a class="btn btn-xs btn-danger" href="{{ route('dashboard.email.cancel', $cron->id) }}">Annuler</a>
                            @elseif(count($updatedRecipients[$cron->id] ?? []) > 0)
                                <a class="btn btn-xs btn-success" href="{{ route('dashboard.email.schedule', [$cron->mail_template->id, $cron->id]) }}">Compléter l'envoi</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
