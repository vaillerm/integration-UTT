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
    @include('emails.parts.style')
  </head>
  <body>
     <span class="mcnPreviewText" style="display:none; font-size:0px; line-height:0px; max-height:0px; max-width:0px; opacity:0; overflow:hidden; visibility:hidden; mso-hide:all;">@yield('title')</span>
     <center>
        <table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
           <tr>
              <td align="center" valign="top" id="bodyCell">
                 <table border="0" cellpadding="0" cellspacing="0" width="100%" class="templateContainer">
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
                          @include('emails.parts.social_networks')
                          {{-- @include('emails.parts.download_application') TODO : Reuse when application is working --}}
                          {{--
                          <div style="text-align: justify; padding: 10px">
                        	<span style="color:#696969; font-size:14px">
                        	 Nous sommes présents tout l'été pour répondre à tes questions, ainsi n'hésite pas à nous contacter ou venir nous voir à l'UTT !
                        	</span>
                          </div>
                          --}}
                          @include('emails.parts.footer')
                       </td>
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

