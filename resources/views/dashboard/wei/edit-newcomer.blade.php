@extends('layouts.dashboard')

@section('title')
Week-end d'intégration
@endsection

@section('smalltitle')
Modification d'un étudiant
@endsection

@section('js')
    <script>
    function updateTotal() {
        $('.basket').each(function(){
        	var total = 0;
            $(this).find('tr').each(function(){
        		var priceObj = $(this).children('.price');
        		var quantityObj = $(this).find('.quantity');
        		if(priceObj.length && quantityObj.length) {
        			var quantity = quantityObj.val();
        			if(!quantity) {
        				quantity = quantityObj.html();
        			}
        			total += parseFloat(priceObj.html()) * parseInt(quantity);
        		}
            });
        	$(this).find('.total').val(total);
    	});
    }

    $('.basket').find('select.quantity').change(function(){
        updateTotal();
    })

    $(function(){
        updateTotal();
    })


    function updateMean() {
        $('.mean').val()
        $('div.input-cash').hide();
        $('div.input-card').hide();
        $('div.input-check').hide();
        $('div.input-'+$('.mean').val()).show();
    }

    $('select.mean').change(function(){
        updateMean();
    })

    $(function(){
        updateMean();
    })



    </script>
@endsection


@section('content')
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Modification du nouveau <strong>{{{ $user->first_name . ' ' . $user->last_name }}}</strong></h3>
            <div class="pull-right">
                <a class="btn btn-xs btn-danger" href="{{ route('dashboard.wei.search')}}">Retour</a>
            </div>
        </div>
        <div class="box-body" id="accordion">

        @if($count >= Config::get('services.wei.newcomerMax') && $user->wei == 0)
            <div class="callout callout-danger">
                Impossible d'inscrire ce nouveau au WEI, il n'y a plus de place :/
            </div>
        @else
            <form class="form-horizontal" action="{{ route('dashboard.wei.newcomer.edit.submit', $user->id) }}" method="post" enctype="multipart/form-data">

            <fieldset class="panel">
                <legend>Informations générales</legend>
                <div class="form-group">
                    <label for="name" class="col-lg-2 control-label">Nom complet</label>
                    <div class="col-lg-10">
                        <input class="form-control" type="text" id="name" name="fullName" readonly value="{{{ $user->first_name . ' ' . $user->last_name }}}">
                    </div>
                </div>

                @if(!$user->isPageChecked('profil'))

                    <div class="form-group">
                        <label for="email" class="col-lg-2 control-label">Email</label>
                        <div class="col-lg-10">
                            <input class="form-control" name="email" id="email" placeholder="email@domain.fr" type="text" value="{{{ old('email') ?? $user->email }}}">
                            <small class="text-muted">Il sera utilisé pour te tenir informé avant l'intégration. Par exemple, pour te prévenir qu'il y a une nouveauté sur le site.<br/>Si tu ne souhaites plus recevoir d'emails tu peux à tout moment revenir sur ce site et enlever ton adresse email.</small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone" class="col-lg-2 control-label">Portable</label>
                        <div class="col-lg-10">
                            <input class="form-control" name="phone" id="phone" placeholder="06.12.34.56.78" type="text" value="{{{ old('phone') ?? $user->phone }}}">
                            <small class="text-muted">Il sera utilisé pour te tenir informé par sms pendant l'intégration. Par exemple, pour te prévenir d'un changement de programme le lendemain.<br/>Si tu ne souhaites plus recevoir de sms tu peux à tout moment revenir sur ce site et enlever ton numéro.</small>
                        </div>
                    </div>


            </fieldset>
            <fieldset>
                <legend>Informations <em>en cas de soucis</em></legend>
                <p class="text-center">
                    Pendant l'intégration tu vas être amené à manger des repas que nous t'aurons préparés et à faire des activités sportives.<br/>
                    Ces informations seront utilisés uniquement pour réagir rapidement en cas de soucis.<br/>
                    Elles ne seront accessible que par les responsables de l'Intégration et par l'association de secourisme de l'école (SecUTT) qui s'occupe de mettre en place des postes de secours.
                </p>

                <div class="form-group">
                    <label for="parent_name" class="col-lg-2 control-label">Personne à contacter en cas d'urgence</label>
                    <div class="col-lg-10">
                        <input class="form-control" name="parent_name" id="parent_name" placeholder="Prénom Nom" type="text" value="{{{ old('parent_name') ?? $user->parent_name }}}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="parent_phone" class="col-lg-2 control-label">Numéro de téléphone de cette personne</label>
                    <div class="col-lg-10">
                        <input class="form-control" name="parent_phone" id="parent_phone" placeholder="06.12.34.56.78" type="text" value="{{{ old('parent_phone') ?? $user->parent_phone }}}">
                        <small class="text-muted">Note : Numéro de téléphone étranger accepté. N'oublie pas l'indicatif pour un numéro étranger.</small>
                    </div>
                </div>

                <div class="form-group">
                    <label for="medical_allergies" class="col-lg-2 control-label">Allergies</label>
                    <div class="col-lg-10">
                        <textarea class="form-control" name="medical_allergies" id="medical_allergies">{{{ old('medical_allergies') ?? $user->medical_allergies }}}</textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="medical_treatment" class="col-lg-2 control-label">Traitement ou régime particulier pendant la semaine d'intégration</label>
                    <div class="col-lg-10">
                        <textarea class="form-control" name="medical_treatment" id="medical_treatment">{{{ old('medical_treatment') ?? $user->medical_treatment }}}</textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="medical_note" class="col-lg-2 control-label">Remarques</label>
                    <div class="col-lg-10">
                        <textarea class="form-control" name="medical_note" id="medical_note">{{{ old('medical_note') ?? $user->medical_note }}}</textarea>
                    </div>
                </div>
                <input type="submit" class="btn btn-success form-control" value="Enregistrement des informations" />
            @endif
            </fieldset>
        </form>
        <form class="form-horizontal" action="{{ route('dashboard.wei.newcomer.edit.submit', $user->id) }}" method="post" enctype="multipart/form-data">

            @if($user->isPageChecked('profil'))
                <fieldset>
                    <legend id="parrainage">Paiement du WEI et du panier repas</legend>

                    @if(!$weiCount)
        				<div class="callout callout-success">
        					WEI payé !
        				</div>
                    @endif

                    @if(!$sandwichCount)
        				<div class="callout callout-success">
        					Panier repas payé !
        				</div>
                    @endif

                    @if($weiCount || $sandwichCount)

                            <table class="table table-hover basket">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Prix</th>
                                        <th>Quantité</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="vert-align">
                                        <td>
            								<strong>Week-end d'intégration</strong>
            								<ul>
            									<li>Départ le vendredi 9 septembre 2016 à 11h30</li>
            									<li>Retour à Troyes le dimanche vers 19h</li>
            									<li>Hébergement compris (sauf sac de couchage)</li>
            									<li>Repas compris</li>
            								</ul>
                                        </td>
                                        <td class="price">{{ sprintf('%04.2f', Config::get('services.wei.price')) }} €</td>
                                        <td>
                                            <select name="wei" class="quantity">
                                               <option value="{{ $weiCount }}">{{ $weiCount }}</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr class="vert-align">
                                        <td>
                                            <strong>Panier repas du vendredi midi</strong>
                                            <p>
                                            Le départ au weekend se faisant à partir de 11h30, vous n'aurez généralement pas le temps d'aller acheter à manger, (sauf si vous l'avez préparé avant).<br/>
                                            Nous proposons donc un panier repas (sandwich, chips, fruit et bouteille d'eau) préparé par le CROUS (qui gère le restaurant universitaire).<br/>
                                            </p>
                                        </td>
                                        <td class="price">{{ sprintf('%04.2f', Config::get('services.wei.sandwichPrice')) }} €</td>
                                        <td>
                                            <select name="sandwich" class="quantity">
                                                <option value="0" @if ((old('sandwich') ?? 1) == 0) selected="selected" @endif>0</option>
                                                @if($sandwichCount >= 1)
                                                    <option value="1" @if ((old('sandwich') ?? 1) == 1) selected="selected" @endif>1</option>
                                                @endif
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="vert-align">
                                        <th class="text-right">
                                            Total :
                                        </th>
                                        <th>
                                            <div class="input-group">
                                                <input type="text" class="form-control total" name="wei-total" readonly>
                                                <span class="input-group-addon">€</span>
                                            </div>
                                        </th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>

                        <hr/>

                        <div class="form-group">
                            <label for="mean" class="col-lg-2 control-label">Moyen de paiement</label>
                            <div class="col-lg-10">
                                <select id="mean" name="mean" class="form-control mean">
                                    <option value=""></option>
                                    <option value="cash" @if ((old('mean')) == 'cash') selected="selected" @endif>Espèce</option>
                                    <option value="check" @if ((old('mean')) == 'check') selected="selected" @endif>Chèque</option>
                                    <option value="card" @if ((old('mean')) == 'card') selected="selected" @endif>CB</option>
                                </select>
                            </div>
                        </div>

                        <div class="input-cash">
                            <div class="form-group">
                                <label for="cash-number" class="col-lg-2 control-label">Numéro de caisse</label>
                                <div class="col-lg-10">
                                    <input class="form-control" type="text" name="cash-number" id="cash-number" value="{{{ old('cash-number') }}}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="cash-color" class="col-lg-2 control-label">Couleur de caisse</label>
                                <div class="col-lg-10">
                                    <input class="form-control" type="text" name="cash-color" id="cash-color" value="{{{ old('cash-color') }}}">
                                </div>
                            </div>
                        </div>

                        <div class="input-check">
                            <div class="form-group">
                                <label for="check-number" class="col-lg-2 control-label">Numéro de chèque</label>
                                <div class="col-lg-10">
                                    <input class="form-control" type="text" name="check-number" id="check-number" value="{{{ old('check-number') }}}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="check-bank" class="col-lg-2 control-label">Banque du chèque</label>
                                <div class="col-lg-10">
                                    <input class="form-control" type="text" name="check-bank" id="check-bank" value="{{{ old('check-bank') }}}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="check-name" class="col-lg-2 control-label">Prénom et nom de l'émetteur du chèque</label>
                                <div class="col-lg-10">
                                    <input class="form-control" type="text" name="check-name" id="check-name" value="{{{ old('check-name') }}}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="check-write" class="col-lg-2 text-right">Écrire le numéro <strong>{{ $user->id }}</strong> derrière le cheque</label>
                                <div class="col-lg-10">
                                    <input type="checkbox" id="check-write" name="check-write" @if (old('check-write')) checked="checked" @endif/>
                                </div>
                            </div>
                        </div>
                        <div class="input-card">
                            <div class="form-group">
                                <label for="card-write" class="col-lg-2 text-right">Écrire le numéro <strong>{{ $user->id }}</strong> derrière le ticket et le mettre dans la caisse</label>
                                <div class="col-lg-10">
                                    <input type="checkbox" id="card-write" name="card-write" @if (old('card-write')) checked="checked" @endif/>
                                </div>
                            </div>
                        </div>
                        <input type="submit" class="btn btn-success form-control" value="Enregistrement du paiement" />
                        <hr/>
                    @endif
                </fieldset>
            </form>
            <form class="form-horizontal" action="{{ route('dashboard.wei.newcomer.edit.submit', $user->id) }}" method="post" enctype="multipart/form-data">

                <fieldset>
                    <legend id="parrainage">Paiement de la caution</legend>

                    @if(!$guaranteeCount)
                        <div class="callout callout-success">
                            Caution payé !
                        </div>
                    @else
                            <table class="table table-hover basket">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Prix</th>
                                        <th>Quantité</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="vert-align">
                                        <td>
                                            <strong>Caution du week-end</strong>
                                            <p>
                                                Autorisation de prélèvement en ligne qui ne sera pas prélevé (sauf en cas de soucis)<br/>
                                                Équivalent d'un chêque, mais en ligne, et qui expire en 29 jours.<br/>
                                                Les conditions d'encaissement de la caution sont disponibles dans les <a href="{{@asset('docs/cgv.pdf')}}">conditions générales de vente</a>.
                                            </p>
                                        </td>
                                        <td class="price">{{ sprintf('%04.2f', Config::get('services.wei.guaranteePrice')) }} €</td>
                                        <td>
                                            <select name="guarantee" class="quantity">
                                                <option value="1" selected>1</option>
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="vert-align">
                                        <th class="text-right">
                                            Total :
                                        </th>
                                        <th>
                                            <div class="input-group">
                                                <input type="text" class="form-control total" name="guarantee-total" readonly>
                                                <span class="input-group-addon">€</span>
                                            </div>
                                        </th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>

                        <hr/>

                        <div class="form-group">
                            <label for="mean2" class="col-lg-2 control-label">Moyen de paiement</label>
                            <div class="col-lg-10"><input class="form-control" type="text" value="Chèque" name="mean2" readonly></div>
                        </div>

                        <div class="form-group">
                            <label for="check2-number" class="col-lg-2 control-label">Numéro de chèque</label>
                            <div class="col-lg-10">
                                <input class="form-control" type="text" name="check2-number" id="check2-number" value="{{{ old('check2-number') }}}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="check2-bank" class="col-lg-2 control-label">Banque du chèque</label>
                            <div class="col-lg-10">
                                <input class="form-control" type="text" name="check2-bank" id="check2-bank" value="{{{ old('check2-bank') }}}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="check2-name" class="col-lg-2 control-label">Prénom et nom de l'émetteur du chèque</label>
                            <div class="col-lg-10">
                                <input class="form-control" type="text" name="check2-name" id="check2-name" value="{{{ old('check2-name') }}}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="check2-write" class="col-lg-2 text-right">Écrire le numéro <strong>{{ $user->id }}</strong> derrière le cheque</label>
                            <div class="col-lg-10">
                                <input type="checkbox" id="check2-write" name="check2-write" @if (old('check2-write')) checked="checked" @endif/>
                            </div>
                        </div>
                        <input type="submit" class="btn btn-success form-control" value="Enregistrement de la caution" />
                        <hr/>
                    @endif
                </fieldset>
            </form>
            <form class="form-horizontal" action="{{ route('dashboard.wei.newcomer.edit.submit', $user->id) }}" method="post" enctype="multipart/form-data">

                <fieldset>
                    <legend id="parrainage">Autorisation parentale</legend>

                    @if(!$underage)
                        <div class="callout callout-success">
                            Majeur ! Pas besoin d'autorisation !
                        </div>
                    @elseif($user->parent_authorization)
                        <div class="callout callout-success">
                            Autorisation donné !
                        </div>
                    @else

                        <div class="form-group">
                            <label for="authorization1" class="col-lg-2 text-right">Autorisation parentale signé récupérée</label>
                            <div class="col-lg-10">
                                <input type="checkbox" id="authorization1" name="authorization1" @if (old('authorization1')) checked="checked" @endif/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="authorization2" class="col-lg-2 text-right">Écrire le numéro <strong>{{ $user->id }}</strong> derrière l'autorisation parentale</label>
                            <div class="col-lg-10">
                                <input type="checkbox" id="authorization2" name="authorization2" @if (old('authorization2')) checked="checked" @endif/>
                            </div>
                        </div>
                        <input type="submit" class="btn btn-success form-control" value="Autorisation parentale récupéré !" />
                    @endif
                </fieldset>
                </form>
            @endif
        </form>
    @endif
            <fieldset class="panel">
                <legend>WeekEnd</legend>
                <div class="form-group">
                    <p>Type: <strong>Nouveau</strong></p>
                </div>
                @if($underage)
                    <div class="callout callout-danger">
                        Mineur !
                    </div>
                @endif
                <div class="form-group">
                    @if(!$user->checkin)
                        <a href="{{ route('dashboard.wei.checkin', ['type'=> 'students', 'id'=> $user->id]) }}" class="btn btn-success form-control">Check-In</a>
                    @else
                        <div class="callout callout-warning">
                            Checkin déja effectué
                        </div>
                    @endif

                </div>

            </fieldset>
    </div>
@endsection
