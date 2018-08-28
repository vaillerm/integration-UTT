@extends('layouts.dashboard')

@section('title')
Modification de modèle de mail
@endsection

@section('smalltitle')
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

<div class="callout callout-danger">
    <h4>DANGER !</h4>
    <p>
        Si ce modèle est utilisé dans un email en cours d'envoi. Les emails qui ne sont pas encore envoyés seront modifiés.
    </p>
</div>

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Modification</h3>
    </div>
    <div class="box-body">
        <form class="form-horizontal" action="{{ route('dashboard.email.edit.submit', $template->id) }}" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="subject" class="col-lg-2 control-label">Sujet</label>
                <div class="col-lg-10">
                    <input class="form-control" name="subject" id="subject" type="text" value="{{{ old('subject') ?? $template->subject }}}">
                </div>
            </div>

            <div class="form-group">
                <label for="sex" class="col-lg-2 control-label">Modèle</label>
                <div class="col-lg-10">
                    <select id="form_template" name="template" class="form-control">
                        <option value="default" @if (old('template') ?? $template->template == 'default') selected="selected" @endif >Personnalisé HTML</option>
                        @foreach ($file_templates as $file_template)
                            @if (substr($file_template->getFilename(), -10) == '.blade.php' && $file_template->getFilename() != 'default.blade.php')
                                <option value="{{$file_template->getBasename('.blade.php')}}"
                                    @if (old('template') ?? $template->template == $file_template->getBasename('.blade.php')) selected="selected" @endif >
                                    {{ $file_template->getBasename('.blade.php') }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="referral_text" class="col-lg-2 control-label">Contenu HTML</label>
                <div class="col-lg-10">
                    <textarea class="form-control" rows="16" id="form_content" name="content">{{{ old('content') ?? $template->content }}}</textarea>
                    <a href="#varlist" data-toggle="collapse">Liste des variables utilisateurs</a></h4>
                    <ul id="varlist" class="collapse">
                        {{--
                            @foreach ($varlist as $varname => $value)
                                <li>%{{ $varname }}% = {!! nl2br(e($value)) !!}</li>
                            @endforeach
                        --}}
                    </ul>
                </div>
            </div>

            <div class="form-group">
                <label for="sex" class="col-lg-2 control-label">Type</label>
                <div class="col-lg-10">
                    <select name="isPublicity" class="form-control" class="">()
                        <option value="0" @if (old('isPublicity') == 0) selected="selected" @endif >Email d'information</option>
                        <option value="1" @if (old('isPublicity') == 1) selected="selected" @endif >Email publicitaire</option>
                    </select>
                </div>
            </div>

            <input type="submit" class="btn btn-success form-control" value="Mettre à jour les informations" />
        </form>
    </div>
</div>
@endsection
