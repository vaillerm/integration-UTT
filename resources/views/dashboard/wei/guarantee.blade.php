@extends('layouts.dashboard')

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

    <div class="callout callout-info">
        <h4>Comment fonctionne la caution en ligne ?</h4>
        <p>
            L'opération de dépôt de caution est assimilable à un <strong>simple paiement en ligne</strong>. Assurez-vous donc d'avoir les fonds suffisant, car la somme sera bien prélevée de votre compte.
        </p>
        <p>
            Contrairement à un paiement classique, vous recevrez un <strong>remboursement sur la carte qui a émis le paiement</strong>, la semaine suivant le WEI (sauf si nous devons encaisser la caution).
        </p>
    </div>

    <div class="box box-default">
        <form action="{{route('dashboard.wei.guarantee.submit')}}" method="post">
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
                                    La somme sera prélevée sur le compte puis remboursée automatiquement sur la carte bancaire émettrice une semaine après le WEI.<br/>
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
                            <th id="total"></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="box-body">
                <div class="checkbox">
                    <label><input type="checkbox" name="cgv"> J'accepte les <a href="{{ asset('docs/cgv.pdf')}}">Conditions Générales de Vente</a></label>
                </div>
                <input type="submit" class="btn btn-success form-control" value="Donner ma caution"/>
            </div>
        </form>
    </div>
@endsection
