@extends('layouts.dashboard')

@section('title')
Week-End d'Intégration
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
            <h3 class="box-title">Modification de l'étudiant <strong>{{{ $user->first_name . ' ' . $user->last_name }}}</strong></h3>
            <div class="pull-right">
                <a class="btn btn-xs btn-danger" href="{{ route('dashboard.wei.search')}}">Retour</a>
            </div>
        </div>
        <div class="box-body" id="accordion">

            <fieldset class="panel">
                <legend>Informations générales</legend>
                <div class="form-group">
                    <label for="name" class="col-lg-2 control-label">Nom complet</label>
                    <div class="col-lg-10">
                        <input class="form-control" type="text" id="name" name="name" disabled value="{{{ $user->first_name . ' ' . $user->last_name }}}">
                    </div>
                </div><br/><br/>
                <div class="form-group">
                    <label for="name" class="col-lg-2 control-label">Photo</label>
                    <div class="col-lg-10 text-center">
                        <img src="{{ asset('/uploads/students-trombi/'.$user->student_id.'.jpg') }}" style="width:100px" alt="Photo"/>
                    </div>
                </div>
            </fieldset>

            @if($user->volunteer)
            <form class="form-horizontal" action="{{ route('dashboard.wei.student.edit.submit', $user->student_id) }}" method="post" enctype="multipart/form-data">
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
                                            <strong>Week-End d'Intégration</strong><br/>
                                            <em>{{$user->first_name. ' '.$user->last_name}} paye le tarif "{{$priceName}}", contactez un coord si vous pensez que ce n'est pas le bon tarif.</em>
                                        </td>
                                        <td class="price">{{ sprintf('%04.2f', $price) }} €</td>
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
                                <label for="check-write" class="col-lg-2 text-right">Écrire le numéro <strong>{{ $user->student_id }}</strong> derrière le chèque</label>
                                <div class="col-lg-10">
                                    <input type="checkbox" id="check-write" name="check-write" @if (old('check-write')) checked="checked" @endif/>
                                </div>
                            </div>
                        </div>
                        <div class="input-card">
                            <div class="form-group">
                                <label for="card-write" class="col-lg-2 text-right">Écrire le numéro <strong>{{ $user->student_id }}</strong> derrière le ticket et le mettre dans la caisse</label>
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
            <form class="form-horizontal" action="{{ route('dashboard.wei.student.edit.submit', $user->student_id) }}" method="post" enctype="multipart/form-data">
                <fieldset>
                    <legend id="parrainage">Paiement de la caution</legend>

                    @if(!$guaranteeCount)
                        <div class="callout callout-success">
                            Caution payée !
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
                                                Autorisation de prélèvement en ligne qui ne sera pas prélevée (sauf en cas de soucis).<br/>
                                                Équivalent d'un chèque, mais en ligne, et qui expire en 29 jours.<br/>
                                                Les conditions d'encaissement de la caution sont disponibles dans les <a href="{{asset('docs/cgv.pdf')}}">Conditions Générales de Vente</a>.
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
                                <input class="form-control" type="text" name="check2-number" id="check2-number" value="{{{ old('check-number') }}}">
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
                            <label for="check2-write" class="col-lg-2 text-right">Écrire le numéro <strong>{{ $user->student_id }}</strong> derrière le chèque</label>
                            <div class="col-lg-10">
                                <input type="checkbox" id="check2-write" name="check2-write" @if (old('check2-write')) checked="checked" @endif/>
                            </div>
                        </div>
                        <input type="submit" class="btn btn-success form-control" value="Enregistrement de la caution" />
                        <hr/>
                    @endif
                </fieldset>
                </form>
            @else
                <h3>Inscription au WEI</h3>
                <strong>{{ $user->first_name }}</strong> doit d'abord se connecter lui même au site de l'inté pour demander à <strong>"devenir bénévole"</strong>. Il ne pourra pas être inscrit au WEI tant que ce n'est pas fait.
            @endif
        </form>
                <fieldset class="panel">
                    <legend>Week-End</legend>
                    <?php
                        if($user->ce)
                            $type = "CE";
                        elseif($user->orga)
                            $type = "orga";
                        else
                            $type = "Bénévole";
                        ?>
                    <div class="form-group">
                        <p>Type: <strong>{{ $type }}</strong></p>
                    </div>
                    <div class="form-group">
                        @if(!$user->checkin)
                            <a href="{{ route('dashboard.wei.checkin', ['type'=> 'students', 'id'=> (int)$user->student_id]) }}" class="btn btn-success form-control">Check-In</a>
                        @else
                            <div class="callout callout-warning">
                                Check-In déja effectué
                            </div>
                        @endif

                    </div>

                </fieldset>
    </div>
@endsection
