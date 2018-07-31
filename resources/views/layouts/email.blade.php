<!DOCTYPE html>
<html lang="fr" style="padding: 0; margin: 0;">
<head>
	<meta charset="utf-8" />

	<title>@yield('title')</title>
</head>
<body style="padding: 0; margin: 0; font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif; background: #ecf0f5; color: #333;">
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
								@yield('title')
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
							@yield('content')
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
							Généré et envoyé par le site de l'intégration de l'Université de Technologie de Troyes.<br />
							@if(isset($mail) && isset($unsuscribe_link) && $mail->isPublicity)
								Pour ne plus recevoir de mails de notre part, cliquez <a href="{{ $unsuscribe_link }}">ici</a>.
							@endif
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	@if(isset($mail_id))
		<img src="{{ url()->route('emails.opening', ['mail_id' => $mail_id]) }}" height="1" width="1" class="">
	@endif
</body>
</html>
