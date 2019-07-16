@extends('layouts.email')

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
                      Activités, soirées, concerts... Un tas d'événements incroyables t'attendent dès le 2&nbsp;septembre et ce, durant toute la semaine, jusqu'au Week-End d'Intégration.<br>
                      Et pas de bizutage ! Tout est fait pour que tu t'éclates et que tu fasses des rencontres.
                    </span><br>
                      &nbsp;<br>
                    <span style="color:#696969">Mais avant toutes choses il faut te préparer. Assure toi de réaliser les tâches suivantes avant ton arrivée :</span>
                  </span>
                  <br>
                  &nbsp;
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </td>

    </tr>

  </tbody>

</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnImageBlock" style="min-width:100%;">
  <tbody class="mcnImageBlockOuter">
    <tr>
      <td valign="top" style="padding:9px" class="mcnImageBlockInner">
        <table align="left" width="100%" border="0" cellpadding="0" cellspacing="0" class="mcnImageContentContainer" style="min-width:100%;">
          <tbody>
            <tr>
              <td class="mcnImageContent" valign="top" style="padding-right: 9px; padding-left: 9px; padding-top: 0; padding-bottom: 0; text-align:center;">
                <img align="center" alt="" src="https://gallery.mailchimp.com/864c7a10f1be9b6cea247370a/images/0fce71b6-78aa-48e3-9bd1-ac739c46799b.png" width="200" style="max-width:400px; padding-bottom: 0; display: inline !important; vertical-align: bottom;" class="mcnRetinaImage">
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
  
  <td valign="top" class="mcnTextContent" style="padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;">
  
      <br>
<span style="color:#E74360"><span style="font-family:merriweather sans,helvetica neue,helvetica,arial,sans-serif"><span style="font-size:16px"><strong>Connecte toi sur le site d'intégration</strong></span></span></span><br>
&nbsp;
<div style="text-align: justify;"><span style="color:#696969"><span style="font-size:14px">Nous t'invitons à te connecter sur le site de l'intégration pour accéder à toutes les informations dont tu auras besoin notamment pour contacter ton <strong>parrain /</strong> ta<strong> marraine</strong> et ton <strong>équipe</strong> d’intégration.<br>
&nbsp;<br>
Ton parrain et ton équipe t’ont préparés un petit mot pour se présenter alors connecte toi vite sur le site pour aller les lire !</span></span><br>
&nbsp;</div>

  </td>
</tr>
</tbody></table>
<!--[if mso]>
</td>
<![endif]-->

<!--[if mso]>
</tr>
</table>
<![endif]-->
</td>
</tr>
</tbody>
</table><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnBoxedTextBlock" style="min-width:100%;">
<!--[if gte mso 9]>
<table align="center" border="0" cellspacing="0" cellpadding="0" width="100%">
<![endif]-->
<tbody class="mcnBoxedTextBlockOuter">
<tr>
<td valign="top" class="mcnBoxedTextBlockInner">

<!--[if gte mso 9]>
<td align="center" valign="top" ">
<![endif]-->
<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;" class="mcnBoxedTextContentContainer">
<tbody><tr>
  
  <td style="padding-top:9px; padding-left:18px; padding-bottom:9px; padding-right:18px;">
  
      <table border="0" cellspacing="0" class="mcnTextContentContainer" width="100%" style="min-width: 100% !important;background-color: #E74360;">
          <tbody><tr>
              <td valign="top" class="mcnTextContent" style="padding: 18px;color: #FFFFFF;font-family: Helvetica;font-size: 14px;font-weight: normal;text-align: center;">
                  <span style="font-size:14px">Clique ici pour rejoindre le site :&nbsp;</span><br>
<a href="https://integration.utt.fr/">https://integration.utt.fr/</a><br>
<span style="font-size:14px"><em>Identifiant : {{ $user->login }}</em></span>
                  <br><span style="font-size:14px"><em>Mot de passe : (celui de ton inscription utt)</em></span>
              </td>
          </tr>
      </tbody></table>
  </td>
</tr>
</tbody></table>
<!--[if gte mso 9]>
</td>
<![endif]-->

<!--[if gte mso 9]>
</tr>
</table>
<![endif]-->
</td>
</tr>
</tbody>
</table>



    
@endsection
