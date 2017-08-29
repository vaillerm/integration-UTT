@extends('layouts.dashboard')

@section('title')
Mails
@endsection

@section('smalltitle')
Envoi de mails en maaasse
@endsection

@section('content')

<div class="callout callout-info">
    <h4>Informations</h4>
    <p>J'ai la flemme d'enlever le message, ducoup je le change !</p>
    <p>
        La fonction mail étant en développement, il faut modifier et programmer les envois directement depuis la base de données. Il n'y a pas (encore) d'interface pour le faire.
    </p>
</div>

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Révision de mail</h3>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            <tbody>
                <tr>
                    <th>Contenu</th>
                    <th>Template</th>
                    <th>Publicité</th>
                    <th>Action</th>
                </tr>
                @foreach ($mail_revisions as $email)
                    <tr>
                        <td>
                            <a href="#email{{$email->id}}" data-toggle="collapse">{{$email->subject}}</a>
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
                            <a class="btn btn-xs btn-info" href="{{ route('dashboard.emails.revisionpreview', ['id'=>$email->id])}}">Prévisualiser</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


@endsection
