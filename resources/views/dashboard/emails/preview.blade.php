@extends('layouts.dashboard')

@section('title')
Emails
@endsection

@section('smalltitle')
Envoi d'emails en maaasse
@endsection

@section('content')

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Object : {{ $email->subject }}</h3>
		<div class="pull-right">
			{{-- @if(!$email->started) --}}
				{{-- <a class="btn btn-xs btn-warning" href="{{ route('dashboard.emails.index', ['id' => $email->id ])}}">Modifier l'email</a> --}}
			{{-- @endif --}}
			<a class="btn btn-xs btn-primary" href="{{ route('dashboard.emails.index')}}">Retour à la liste</a>
		</div>
    </div>
    <div class="box-body">
		<table style="width: 100%; border-collapse: collapse;">
			<tr>
				<td style="vertical-align: center; background: #3c8dbc; color: #fff; border-collapse: collapse; height: 50px; line-height: 30px;">
					<table style="max-width: 600px; width:100%; margin: 0 auto; border-collapse: collapse; font-size:18px;">
						<tr>
							<td>
								<a style="color: #fff;text-decoration:none;" href="{{ route('index') }}"><b>Intégration</b> UTT</a>
							</td>
						</tr>
					</table>
				</td>
			</tr>

			<tr>
				<td style="vertical-align: top; max-width: 600px; margin:auto; border-collapse: collapse; font-family: 'Source Sans Pro',sans-serif;">
					<table style="max-width: 600px; width:100%; margin: 0 auto; border-collapse: collapse;margin-top: 15px;margin-bottom:10px">
						<tr>
							<td>
								<h1 style="margin: 0;font-size: 24px; font-weight: normal;">
									{{ $email->subject }}
								</h1>
							</td>
						</tr>
					</table>
				</td>
			</tr>

			<tr>
				<td style="vertical-align: top; text-align: justify; border-collapse: collapse; font-size: 14px; ">
					<table style="max-width: 600px; width:100%; margin: 0 auto; border-collapse: collapse; border-top: 3px solid #d2d6de; background-color: #fff; box-shadow: 0 1px 1px rgba(0,0,0,0,1);border-radius: 3px;">
						<tr>
							<td style="padding: 10px; ">
								{!! $view !!}
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style="vertical-align: top; padding: 20px 0 20px 0; text-align: center; color: #999999; border-collapse: collapse; font-size: 11px;">
					<table style="max-width: 600px; width:100%; margin: 0 auto; border-collapse: collapse;">
						<tr>
							<td>
								Généré et envoyé par le site de l'intégration de l'Université de Technologie de Troyes<br />
								Pour ne plus recevoir d'emails de notre part, contactez <a href="mailto:integration@utt.fr">integration@utt.fr</a>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
    </div>
</div>
@endsection
