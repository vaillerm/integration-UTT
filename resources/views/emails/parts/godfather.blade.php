@if ($user->godFather)
<table style="max-width: 600px; width:100%; margin: 15px auto 0 auto; border-collapse: collapse; background-color: #fff; box-shadow: 0 1px 1px rgba(0,0,0,0,1);">
    <tr>
        <td style="color: #FFF;background-color: #E74360 ;padding: 16px 16px 10px 16px;font-size:20px;font-weight:bold;text-align:center;">
            Contacte {{ $user->godFather->first_name }} {{ $user->godFather->last_name }}, {{ ($user->godFather->sex)?'ta marraine':'ton parrain' }} !
        </td>
    </tr>
    <tr>
        <td style="padding: 10px;">
            <p style="margin-top:0; text-align: justify;">Lorsque tu arrives à l'UTT, un étudiant plus ancien devient ton parrain ou ta marraine.
            Cet étudiant sera ton contact privilégié pour découvrir l'école, mais aussi la vie étudiante&nbsp;troyenne.
            Il pourra répondre à toutes tes questions, que ce soit sur l’UTT, les logements, les cours, la vie à Troyes...
            </p>

            <img src="{{ asset('/uploads/students-trombi/'.$user->godFather->student_id.'.jpg') }}" alt="Photo" style="float:left;width:100px;"/>
            <div style="margin-bottom:5px;margin-left:115px;line-height:26px; font-size: 15px">
                <span style="margin-right: 5px; font-size:20px;vertical-align:bottom">📞</span> {{ $user->godFather->phone }}<br/>
                <span style="margin-right: 5px; font-size:20px;vertical-align:bottom">📧</span> {{ $user->godFather->email }}<br/>
                <span style="margin-right: 5px; font-size:20px;vertical-align:bottom">🚀</span> {{ ($user->godFather->sex)?'Elle':'Il' }}
                vient de {{ $user->godFather->city }} en {{ $user->godFather->country }}<br/>
                @if ($user->godFather->facebook)
                    <span style="margin-right: 5px; font-size:20px;vertical-align:bottom">💬</span> <a style="color: #3c8dbc;" target="_blank" href="{{ $user->godFather->facebook }}">Profil Facebook</a><br/>
                @endif
                @if ($user->godFather->surname)
                    <span style="margin-right: 5px; font-size:20px;vertical-align:bottom">👋</span> On {{ ($user->godFather->sex)?'la':'le' }} surnomme <em>{{$user->godFather->surname}}</em>
                @endif
            </div>
            <div style="clear:both"></div>

            <h3>{{ ($user->godFather->sex)?'Elle':'Il' }} a un message pour toi !</h3>
            <p style="text-align:justify;font-size:1.1em"><em>{!! nl2br(e($user->godFather->referral_text)) !!}</em></p>

            <div style="text-align:center; margin-top: 15px;">
                <span style="font-style: bold;display:block; font-size: 1.2em">
                    <strong>{{ ($user->godFather->sex)?'Elle':'Il' }} n'a pas tes coordonnées, c'est à toi de faire le premier pas ;)</strong>
                </span>
            </div>
        </td>
    </tr>
</table>
@endif
