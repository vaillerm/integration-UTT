@extends('emails.master_layout')

@section('title')
Bienvenue à l'UTT
@endsection

@section('content')

<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">
  <tbody class="mcnTextBlockOuter">
    <tr>
      <td valign="top" class="mcnTextBlockInner" style="padding-top:9px;">
        <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%; min-width:100%;" width="100%" class="mcnTextContentContainer">
          <tbody>
            <tr>
              <td valign="top" class="mcnTextContent" style="padding: 0px 18px 9px;color: #222222;font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, Verdana, sans-serif;font-size: 14px;line-height: 150%;text-align: justify;">
                <h1 style="text-align: center;">
                  <span style="color:#E74360">
                    <span style="font-family:merriweather sans,helvetica neue,helvetica,arial,sans-serif">
                      <strong>
                        <span style="font-size:20px">Salut {{ $user->first_name }}</span>
                      </strong>
                    </span>
                  </span>
                </h1>
              </td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
  </tbody>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">
  <tbody class="mcnTextBlockOuter">
    <tr>
      <td valign="top" class="mcnTextBlockInner" style="padding-top:9px;">
        <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%; min-width:100%;" width="100%" class="mcnTextContentContainer">
          <tbody>
            <tr>
              <td valign="top" class="mcnTextContent" style="padding: 0px 18px 9px;color: #222222;font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, Verdana, sans-serif;font-size: 14px;line-height: 150%;text-align: justify;">
                <div style="text-align: justify;">
                  <span style="font-family:helvetica neue,helvetica,arial,verdana,sans-serif">
                    <span style="color:#696969">
                      <span style="font-size:14px">Bravo pour ton acceptation à l'UTT !</span>
                    </span>
                  </span>
                </div>
                <div style="text-align: justify;">
                  <br>
                  <span style="font-size:14px">
                    <span style="color:#696969">
                      Nous sommes l'équipe d'intégration, des étudiants bénévoles qui préparent minutieusement ton arrivée pour que celle-ci reste inoubliable.<br>
                      &nbsp;<br>
                      Un tas d'événements incroyables, tous basés sur la base du volontariat, t'attendent dès le {{ Config::get('services.reentry.tc.date') }} si tu arrives en première année, dès le {{ Config::get('services.reentry.branches.date') }} si tu arrives en 3ème année et dès le {{ Config::get('services.reentry.masters.date') }} si tu arrives en master. Tout est fait pour que tu t'éclates et que rencontrent les personnes qui feront de ton passage à l'UTT un moment inoubliables.
                    </span><br>
                    &nbsp;<br>
                    <span style="color:#696969">Mais avant toutes choses il faut te préparer. Assure toi de réaliser les tâches suivantes avant ton arrivée :</span>
                  </span>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
  </tbody>
</table>

@include('emails.parts.connect')
@include('emails.parts.godfather')

@endsection
