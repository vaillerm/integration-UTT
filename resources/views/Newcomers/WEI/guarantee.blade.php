@extends('layouts.newcomer')

@section('title')
Dépôt de la caution du Week-End
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
            <form action="{{route('newcomer.wei.guarantee.submit')}}" method="post">
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
    								<strong>Caution du Week-End</strong>
    								<p>
                                        Autorisation de prélèvement en ligne qui ne sera pas prélevée (sauf en cas de soucis).<br/>
                                        Équivalent d'un chèque, mais en ligne, et qui expire en 29 jours.<br/>
                                        Les conditions d'encaissement de la caution sont disponibles dans les <a href="{{ asset('docs/cgv.pdf')}}">Conditions Générales de Vente</a>.
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
    							<th id="total"></th>
    							<th></th>
    						</tr>
    					</tfoot>
    				</table>
    			</div>
    		    <div class="box-body">
    				<div class="checkbox">
    					<label><input type="checkbox" name="cgv"> J'accepte les <a href="{{ asset('docs/cgv.pdf') }}">Conditions Générales de Vente</a></label>
    				</div>
    				<input type="submit" class="btn btn-success form-control" value="Donner ma caution"/>
    				<div class="text-center">
    					<a href="#cannotpay" data-toggle="collapse">Je ne peux pas donner de caution en ligne !</a>
    					<p id="cannotpay" class="collapse">
    						Il faudra passer nous voir à la rentrée pour donner ta caution par chèque au nom de <em>BDE UTT</em>.<br/>
    						<a href="{{ route('newcomer.wei.authorization')}}" class="btn btn-warning">Passer cette étape<br/>Je viendrais donner un chèque à la rentrée</a>
    					</p>
    				</div>
    			</div>
            </form>
        </div>
@endsection
