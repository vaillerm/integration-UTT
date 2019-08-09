<!DOCTYPE html>
<html lang="fr" style="padding: 0; margin: 0;">
<head>
  <!-- NAME: 1 COLUMN -->
  <!--[if gte mso 15]>
  <xml>
      <o:OfficeDocumentSettings>
      <o:AllowPNG/>
      <o:PixelsPerInch>96</o:PixelsPerInch>
      </o:OfficeDocumentSettings>
  </xml>
  <![endif]-->
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>@yield('title')</title>

<style type="text/css">
p{
margin:10px 0;
padding:0;
}
table{
border-collapse:collapse;
}
h1,h2,h3,h4,h5,h6{
display:block;
margin:0;
padding:0;
}
img,a img{
border:0;
height:auto;
outline:none;
text-decoration:none;
}
body,#bodyTable,#bodyCell{
height:100%;
margin:0;
padding:0;
width:100%;
}
.mcnPreviewText{
display:none !important;
}
#outlook a{
padding:0;
}
img{
-ms-interpolation-mode:bicubic;
}
table{
mso-table-lspace:0pt;
mso-table-rspace:0pt;
}
.ReadMsgBody{
width:100%;
}
.ExternalClass{
width:100%;
}
p,a,li,td,blockquote{
mso-line-height-rule:exactly;
}
a[href^=tel],a[href^=sms]{
color:inherit;
cursor:default;
text-decoration:none;
}
p,a,li,td,body,table,blockquote{
-ms-text-size-adjust:100%;
-webkit-text-size-adjust:100%;
}
.ExternalClass,.ExternalClass p,.ExternalClass td,.ExternalClass div,.ExternalClass span,.ExternalClass font{
line-height:100%;
}
a[x-apple-data-detectors]{
color:inherit !important;
text-decoration:none !important;
font-size:inherit !important;
font-family:inherit !important;
font-weight:inherit !important;
line-height:inherit !important;
}
#bodyCell{
padding:10px;
}
.templateContainer{
max-width:600px !important;
border:20px none #ea5b3a;
}
a.mcnButton{
display:block;
}
.mcnImage,.mcnRetinaImage{
vertical-align:bottom;
}
.mcnTextContent{
word-break:break-word;
}
.mcnTextContent img{
height:auto !important;
}
.mcnDividerBlock{
table-layout:fixed !important;
}
body,#bodyTable{
background-color:#e74360;
}
#bodyCell{
border-top:0;
}

.templateContainer{
border:20px none #ea5b3a;
}

h1{
color:#202020;
font-family:Helvetica;
font-size:26px;
font-style:normal;
font-weight:bold;
line-height:125%;
letter-spacing:normal;
text-align:left;
}
h2{
color:#202020;
font-family:Helvetica;
font-size:22px;
font-style:normal;
font-weight:bold;
line-height:125%;
letter-spacing:normal;
text-align:left;
}
h3{
color:#202020;
font-family:Helvetica;
font-size:20px;
font-style:normal;
font-weight:bold;
line-height:125%;
letter-spacing:normal;
text-align:left;
}
h4{
color:#202020;
font-family:Helvetica;
font-size:18px;
font-style:normal;
font-weight:bold;
line-height:125%;
letter-spacing:normal;
text-align:left;
}
#templatePreheader{
background-color:#ffffff;
background-image:none;
background-repeat:no-repeat;
background-position:center;
background-size:cover;
border-top:0;
border-bottom:0;
padding-top:9px;
padding-bottom:9px;
}
#templatePreheader .mcnTextContent,#templatePreheader .mcnTextContent p{
color:#656565;
font-family:Helvetica;
font-size:12px;
line-height:150%;
text-align:left;
}
#templatePreheader .mcnTextContent a,#templatePreheader .mcnTextContent p a{
color:#656565;
font-weight:normal;
text-decoration:underline;
}
#templateHeader{
background-color:#ffffff;
background-image:none;
background-repeat:no-repeat;
background-position:center;
background-size:cover;
border-top:0;
border-bottom:0;
padding-top:9px;
padding-bottom:0;
}
#templateHeader .mcnTextContent,#templateHeader .mcnTextContent p{
color:#202020;
font-family:Helvetica;
font-size:16px;
line-height:150%;
text-align:left;
}
#templateHeader .mcnTextContent a,#templateHeader .mcnTextContent p a{
color:#007C89;
font-weight:normal;
text-decoration:underline;
}
#templateBody{
background-color:#ffffff;
background-image:none;
background-repeat:no-repeat;
background-position:center;
background-size:cover;
border-top:0;
border-bottom:2px solid #EAEAEA;
padding-top:0;
padding-bottom:9px;
}
#templateBody .mcnTextContent,#templateBody .mcnTextContent p{
color:#202020;
font-family:Helvetica;
font-size:16px;
line-height:150%;
text-align:left;
}
#templateBody .mcnTextContent a,#templateBody .mcnTextContent p a{
color:#ffd249;
font-weight:normal;
text-decoration:underline;
}
#templateFooter{
background-color:#fafafa;
background-image:none;
background-repeat:no-repeat;
background-position:center;
background-size:cover;
border-top:0;
border-bottom:0;
padding-top:9px;
padding-bottom:9px;
}
#templateFooter .mcnTextContent,#templateFooter .mcnTextContent p{
color:#656565;
font-family:Helvetica;
font-size:12px;
line-height:150%;
text-align:center;
}
#templateFooter .mcnTextContent a,#templateFooter .mcnTextContent p a{
color:#656565;
font-weight:normal;
text-decoration:underline;
}
@media only screen and (min-width:768px){
.templateContainer{
width:600px !important;
}

}	@media only screen and (max-width: 480px){
body,table,td,p,a,li,blockquote{
-webkit-text-size-adjust:none !important;
}

}	@media only screen and (max-width: 480px){
body{
width:100% !important;
min-width:100% !important;
}

}	@media only screen and (max-width: 480px){
#bodyCell{
padding-top:10px !important;
}

}	@media only screen and (max-width: 480px){
.mcnRetinaImage{
max-width:100% !important;
}

}	@media only screen and (max-width: 480px){
.mcnImage{
width:100% !important;
}

}	@media only screen and (max-width: 480px){
.mcnCartContainer,.mcnCaptionTopContent,.mcnRecContentContainer,.mcnCaptionBottomContent,.mcnTextContentContainer,.mcnBoxedTextContentContainer,.mcnImageGroupContentContainer,.mcnCaptionLeftTextContentContainer,.mcnCaptionRightTextContentContainer,.mcnCaptionLeftImageContentContainer,.mcnCaptionRightImageContentContainer,.mcnImageCardLeftTextContentContainer,.mcnImageCardRightTextContentContainer,.mcnImageCardLeftImageContentContainer,.mcnImageCardRightImageContentContainer{
max-width:100% !important;
width:100% !important;
}

}	@media only screen and (max-width: 480px){
.mcnBoxedTextContentContainer{
min-width:100% !important;
}

}	@media only screen and (max-width: 480px){
.mcnImageGroupContent{
padding:9px !important;
}

}	@media only screen and (max-width: 480px){
.mcnCaptionLeftContentOuter .mcnTextContent,.mcnCaptionRightContentOuter .mcnTextContent{
padding-top:9px !important;
}

}	@media only screen and (max-width: 480px){
.mcnImageCardTopImageContent,.mcnCaptionBottomContent:last-child .mcnCaptionBottomImageContent,.mcnCaptionBlockInner .mcnCaptionTopContent:last-child .mcnTextContent{
padding-top:18px !important;
}

}	@media only screen and (max-width: 480px){
.mcnImageCardBottomImageContent{
padding-bottom:9px !important;
}

}	@media only screen and (max-width: 480px){
.mcnImageGroupBlockInner{
padding-top:0 !important;
padding-bottom:0 !important;
}

}	@media only screen and (max-width: 480px){
.mcnImageGroupBlockOuter{
padding-top:9px !important;
padding-bottom:9px !important;
}

}	@media only screen and (max-width: 480px){
.mcnTextContent,.mcnBoxedTextContentColumn{
padding-right:18px !important;
padding-left:18px !important;
}

}	@media only screen and (max-width: 480px){
.mcnImageCardLeftImageContent,.mcnImageCardRightImageContent{
padding-right:18px !important;
padding-bottom:0 !important;
padding-left:18px !important;
}

}	@media only screen and (max-width: 480px){
.mcpreview-image-uploader{
display:none !important;
width:100% !important;
}

}	@media only screen and (max-width: 480px){
h1{
font-size:22px !important;
line-height:125% !important;
}

}	@media only screen and (max-width: 480px){
h2{
font-size:20px !important;
line-height:125% !important;
}

}	@media only screen and (max-width: 480px){
h3{
font-size:18px !important;
line-height:125% !important;
}

}	@media only screen and (max-width: 480px){
h4{
font-size:16px !important;
line-height:150% !important;
}

}	@media only screen and (max-width: 480px){
.mcnBoxedTextContentContainer .mcnTextContent,.mcnBoxedTextContentContainer .mcnTextContent p{
font-size:14px !important;
line-height:150% !important;
}

}	@media only screen and (max-width: 480px){
#templatePreheader{
display:block !important;
}

}	@media only screen and (max-width: 480px){
#templatePreheader .mcnTextContent,#templatePreheader .mcnTextContent p{
font-size:14px !important;
line-height:150% !important;
}

}	@media only screen and (max-width: 480px){
#templateHeader .mcnTextContent,#templateHeader .mcnTextContent p{
font-size:16px !important;
line-height:150% !important;
}

}	@media only screen and (max-width: 480px){
#templateBody .mcnTextContent,#templateBody .mcnTextContent p{
font-size:16px !important;
line-height:150% !important;
}

}	@media only screen and (max-width: 480px){
#templateFooter .mcnTextContent,#templateFooter .mcnTextContent p{
font-size:14px !important;
line-height:150% !important;
}

}</style></head>
<body>
  <span class="mcnPreviewText" style="display:none; font-size:0px; line-height:0px; max-height:0px; max-width:0px; opacity:0; overflow:hidden; visibility:hidden; mso-hide:all;">@yield('title')</span>
  <center>
    <table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
      <tr>
        <td align="center" valign="top" id="bodyCell">
          <table border="0" cellpadding="0" cellspacing="0" width="100%" class="templateContainer">
            <tr>
              <td valign="top" id="templatePreheader">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnImageBlock" style="min-width:100%;">
                  <tbody class="mcnImageBlockOuter">
                    <tr>
                      <td valign="top" style="padding:9px" class="mcnImageBlockInner">
                        <table align="left" width="100%" border="0" cellpadding="0" cellspacing="0" class="mcnImageContentContainer" style="min-width:100%;">
                          <tbody>
                            <tr>
                              <td class="mcnImageContent" valign="top" style="padding-right: 9px; padding-left: 9px; padding-top: 0; padding-bottom: 0; text-align:center;">
                                <a style="color: #fff;text-decoration:none;" href="{{ route('index') }}">
                                  <img align="center" alt="" src="https://gallery.mailchimp.com/864c7a10f1be9b6cea247370a/images/5705f2d4-e12e-4da1-bb8e-bb08c5c21a22.png" width="375" style="max-width:750px; padding-bottom: 0; display: inline !important; vertical-align: bottom;" class="mcnRetinaImage">
                                </a>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>
            <tr>
              <td valign="top" id="templateHeader">
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
                                      <span style="font-size:16px">@yield('title')</span>
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
              </td>
            </tr>
            <tr>
              <td valign="top" id="templateBody">
                @yield('content')

<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">
<tbody class="mcnTextBlockOuter">
  <tr>
      <td valign="top" class="mcnTextBlockInner" style="padding-top:9px;">
          <!--[if mso]>
  <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;">
  <tr>
  <![endif]-->

  <!--[if mso]>
  <td valign="top" width="600" style="width:600px;">
  <![endif]-->
          <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%; min-width:100%;" width="100%" class="mcnTextContentContainer">
              <tbody><tr>

                  <td valign="top" class="mcnTextContent" style="padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;">

                      <br>
<span style="font-family:merriweather sans,helvetica neue,helvetica,arial,sans-serif"><span style="color:#E74360"><span style="font-size:16px"><strong>Télécharge l'application et rejoins nous sur les réseaux</strong></span></span></span><br>
<br>
<span style="color:#696969"><span style="font-size:14px">Pour une expérience optimale de ton intégration, nous avons mis au point une application qui te détaillera les infos pratiques des événements de la semaine. Il est important que tu la télécharge, elle nous permettra de communiquer avec toi plus facilement.</span></span><br>
&nbsp;
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
</table><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnButtonBlock" style="min-width:100%;">
<tbody class="mcnButtonBlockOuter">
  <tr>
      <td style="padding-top:0; padding-right:18px; padding-bottom:18px; padding-left:18px;" valign="top" align="center" class="mcnButtonBlockInner">
          <table border="0" cellpadding="0" cellspacing="0" class="mcnButtonContentContainer" style="border-collapse: separate !important;border-radius: 50px;background-color: #E74360;">
              <tbody>
                  <tr>
                      <td align="center" valign="middle" class="mcnButtonContent" style="font-family: Arial; font-size: 14px; padding: 18px;">
                          <a class="mcnButton " title="Télécharger l'application" href="https://integration.utt.fr/app" target="_blank" style="font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;">Télécharger l'application</a>
                      </td>
                  </tr>
              </tbody>
          </table>
      </td>
  </tr>
</tbody>
</table><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">
<tbody class="mcnTextBlockOuter">
  <tr>
      <td valign="top" class="mcnTextBlockInner" style="padding-top:9px;">

          <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%; min-width:100%;" width="100%" class="mcnTextContentContainer">
              <tbody><tr>

                  <td valign="top" class="mcnTextContent" style="padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;">

                      <div style="text-align: center;"><span style="font-family:merriweather sans,helvetica neue,helvetica,arial,sans-serif"><span style="color:#E74360"><strong>Rejoins nous sur les réseaux !</strong></span></span></div>

                  </td>
              </tr>
          </tbody></table>
      </td>
  </tr>
</tbody>
</table><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnFollowBlock" style="min-width:100%;">
<tbody class="mcnFollowBlockOuter">
  <tr>
      <td align="center" valign="top" style="padding:9px" class="mcnFollowBlockInner">
          <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnFollowContentContainer" style="min-width:100%;">
<tbody><tr>
  <td align="center" style="padding-left:9px;padding-right:9px;">
      <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;" class="mcnFollowContent">
          <tbody><tr>
              <td align="center" valign="top" style="padding-top:9px; padding-right:9px; padding-left:9px;">
                  <table align="center" border="0" cellpadding="0" cellspacing="0">
                      <tbody><tr>
                          <td align="center" valign="top">


                                      <table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline;">
                                          <tbody><tr>
                                              <td valign="top" style="padding-right:10px; padding-bottom:9px;" class="mcnFollowContentItemContainer">
                                                  <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnFollowContentItem">
                                                      <tbody><tr>
                                                          <td align="left" valign="middle" style="padding-top:5px; padding-right:10px; padding-bottom:5px; padding-left:9px;">
                                                              <table align="left" border="0" cellpadding="0" cellspacing="0" width="">
                                                                  <tbody><tr>

                                                                          <td align="center" valign="middle" width="24" class="mcnFollowIconContent">
                                                                              <a href="https://www.facebook.com/bde.utt/" target="_blank"><img src="https://cdn-images.mailchimp.com/icons/social-block-v2/color-facebook-48.png" alt="Facebook" style="display:block;" height="24" width="24" class=""></a>
                                                                          </td>


                                                                  </tr>
                                                              </tbody></table>
                                                          </td>
                                                      </tr>
                                                  </tbody></table>
                                              </td>
                                          </tr>
                                      </tbody></table>


                                      <table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline;">
                                          <tbody><tr>
                                              <td valign="top" style="padding-right:10px; padding-bottom:9px;" class="mcnFollowContentItemContainer">
                                                  <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnFollowContentItem">
                                                      <tbody><tr>
                                                          <td align="left" valign="middle" style="padding-top:5px; padding-right:10px; padding-bottom:5px; padding-left:9px;">
                                                              <table align="left" border="0" cellpadding="0" cellspacing="0" width="">
                                                                  <tbody><tr>

                                                                          <td align="center" valign="middle" width="24" class="mcnFollowIconContent">
                                                                              <a href="https://www.instagram.com/bdeutt/" target="_blank"><img src="https://cdn-images.mailchimp.com/icons/social-block-v2/color-instagram-48.png" alt="Instagram" style="display:block;" height="24" width="24" class=""></a>
                                                                          </td>


                                                                  </tr>
                                                              </tbody></table>
                                                          </td>
                                                      </tr>
                                                  </tbody></table>
                                              </td>
                                          </tr>
                                      </tbody></table>


                                      <table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline;">
                                          <tbody><tr>
                                              <td valign="top" style="padding-right:0; padding-bottom:9px;" class="mcnFollowContentItemContainer">
                                                  <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnFollowContentItem">
                                                      <tbody><tr>
                                                          <td align="left" valign="middle" style="padding-top:5px; padding-right:10px; padding-bottom:5px; padding-left:9px;">
                                                              <table align="left" border="0" cellpadding="0" cellspacing="0" width="">
                                                                  <tbody><tr>

                                                                          <td align="center" valign="middle" width="24" class="mcnFollowIconContent">
                                                                              <a href="https://www.snapchat.com/add/integrationutt" target="_blank"><img src="https://cdn-images.mailchimp.com/icons/social-block-v2/color-snapchat-48.png" alt="Snapchat" style="display:block;" height="24" width="24" class=""></a>
                                                                          </td>


                                                                  </tr>
                                                              </tbody></table>
                                                          </td>
                                                      </tr>
                                                  </tbody></table>
                                              </td>
                                          </tr>
                                      </tbody></table>
                          </td>
                      </tr>
                  </tbody></table>
              </td>
          </tr>
      </tbody></table>
  </td>
</tr>
</tbody></table>

      </td>
  </tr>
</tbody>
</table><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">
<tbody class="mcnTextBlockOuter">
  <tr>
      <td valign="top" class="mcnTextBlockInner" style="padding-top:9px;">
          <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%; min-width:100%;" width="100%" class="mcnTextContentContainer">
              <tbody><tr>

                  <td valign="top" class="mcnTextContent" style="padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;">

                      <div style="text-align: justify;"><span style="color:#696969"><span style="font-size:14px">
Nous sommes présents tout l'été pour répondre à tes questions, ainsi n'hésite pas à nous contacter ou venir nous voir à l'UTT !</span></span></div>

                  </td>
              </tr>
          </tbody></table>
      </td>
  </tr>
</tbody>
</table></td>
                      </tr>
                      <tr>
                          <td valign="top" id="templateFooter"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">
<tbody class="mcnTextBlockOuter">
  <tr>
      <td valign="top" class="mcnTextBlockInner" style="padding-top:9px;">

          <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%; min-width:100%;" width="100%" class="mcnTextContentContainer">
              <tbody><tr>

                  <td valign="top" class="mcnTextContent" style="padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;">

                      <span style="font-size:12px"><em>Association BDE UTT - 12 rue Marie Curie - 10000 - Troyes<br>
Contact : <a href='mailto:integration@utt.fr'>integration@utt.fr</a></em></span>
                  </td>
              </tr>
          </tbody></table>
          <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%; min-width:100%;" width="100%" class="mcnTextContentContainer">
            <tbody><tr>


                <td valign="top" class="mcnTextContent" style="padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;">
                  <span style="font-size:12px">Généré et envoyé par le site de l'intégration de l'Université de Technologie de Troyes.<br />
                  @if(isset($mail) && isset($unsuscribe_link) && $mail->isPublicity)
                    Pour ne plus recevoir de mails de notre part, cliquez <a href="{{ $unsuscribe_link }}">ici</a>.
                  @endif</span>
                </td>
            </tr>
        </tbody></table>
      </td>
  </tr>
</tbody>
</table></td>
                      </tr>
                  </table>
              </td>
          </tr>
      </table>
  </center>

	@if(isset($mail_id))
  <img src="{{ url()->route('emails.opening', ['mail_id' => $mail_id]) }}" height="1" width="1" class="">
@endif
</body>
</html>
