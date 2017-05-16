@extends('layouts.newcomer')

@section('title')
Paiement du week-end
@endsection

@section('smalltitle')
Le Week-End d'Intégration
@endsection

@section('js')
    <script>
    function updateTotal() {
    	var total = 0;
        $('#basket tr').each(function(){
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
    	$('#total').html(total + ' €');
    }

    $('#basket').find('select.quantity').change(function(){
        updateTotal();
    })

    $(function(){
        updateTotal();
    })


    </script>
@endsection

@section('content')
		<div class="box box-default">
            <form action="{{route('newcomer.wei.pay.submit')}}" method="post">
    		    <div class="box-body table-responsive no-padding">
    		        <table class="table table-hover" id="basket">
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
    								Nous vous proposons donc un panier repas (sandwich, chips, fruit et bouteille d'eau) préparé par le CROUS (qui gère le restaurant universitaire).<br/>
                                    Si tu as un régime particulier (sans porc, vegetarien, ...) pense à préciser dans <em>régime particulier</em> sur ton profil.
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
    							<th id="total"></th>
    							<th></th>
    						</tr>
    					</tfoot>
    				</table>
    			</div>
    		    <div class="box-body">
    				<div class="checkbox">
    					<label><input type="checkbox" name="cgv"> J'accepte les <a href="{{@asset('docs/cgv.pdf')}}">Conditions générales de vente</a></label>
    				</div>
    				<input type="submit" class="btn btn-success form-control" value="Payer"/>
    				<div class="text-center">
    					<a href="#cannotpay" data-toggle="collapse">Je ne peux pas payer en ligne !</a>
    					<p id="cannotpay" class="collapse">
    						Il faudra passer nous voir à la rentrée pour payer par chèque au nom de <em>BDE UTT</em>, par carte bancaire ou en espèce.<br/>
    						<a href="{{ route('newcomer.wei.guarantee')}}" class="btn btn-warning">Passer cette étape<br/>Je viendrais payer à la rentrée</a>
    					</p>
    				</div>
    			</div>
            </form>
        </div>
@endsection
