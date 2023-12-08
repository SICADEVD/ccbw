@extends('manager.layouts.app')
@section('panel')
<link  rel="stylesheet" href="{{asset('assets/fcadmin/css/vendor/datepicker.min.css')}}">
<link  rel="stylesheet" href="{{asset('assets/fcadmin/css/tokens.css')}}">
<div class="row mb-none-30">
    <div class="col-lg-12 col-md-12 mb-30">
        <div class="card">
            <form action="{{route('manager.livraison.section.store')}}" id="flocal" method="POST">
                <div class="card-body">
                    @csrf
                    <div class="row">
                        <input type="hidden" name="code" value="{{ $code }}">
                        <div class="col-lg-6 form-group">
                            <label for="">@lang("Date de livraison")</label>
                            <div class="input-group">
                                <input name="estimate_date" value="{{ old('estimate_date') }}" type="text" autocomplete="off"  class="form-control dates" placeholder="Date de livraison" required>
                                <span class="input-group-text"><i class="las la-calendar"></i></span>
                            </div>
                        </div>
                        
                        <div class="col-lg-6 form-group">
                            <label for="">@lang("Type de produit")</label>
                            <div class="input-group">
                            <select class="form-control selected_type" name="type" required> 
                                                        <option value="{{ __('Certifie') }}"
                                                        @selected(old('type')=='Certifie')>{{ __('Certifie') }}</option>
                                                        <option value="{{ __('Ordinaire') }}"
                                                        @selected(old('type')=='Ordinaire')>{{ __('Ordinaire') }}</option>
                                                </select>
                            </div>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card border--primary mt-3">
                                <h5 class="card-header bg--primary  text-white">@lang('Information Expéditeur(Magasin de Section)')</h5>
                                <div class="card-body">
                                    <div class="row">
                                    <div class="form-group col-lg-12">
                                            <label>@lang('Selectionner un magasin de section')</label>
                                            <select class="form-control" name="sender_magasin" id="sender_magasin" onchange="getSender()" required>
                                                <option value>@lang('Selectionner une option')</option>
                                                @foreach($magSections as $magasin)
                                                <option value="{{$magasin->id}}"
                                                data-id ="{{$magasin->id}}"
                                                data-name ="{{$magasin->user->lastname}} {{$magasin->user->firstname}}"
                                                data-phone ="{{$magasin->user->mobile}}"
                                                data-email ="{{$magasin->user->email}}"
                                                data-adresse ="{{$magasin->user->adresse}}"
                                                @selected(old('sender_magasin')==$magasin->id)>{{__($magasin->nom)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label>@lang('Nom')</label>
                                            <input type="text" class="form-control" name="sender_name"
                                            id="sender_name"
                                                value="{{old('sender_name')}}" readonly required>
                                        </div>
                                        <div class=" form-group col-lg-6">
                                            <label>@lang('Contact')</label>
                                            <input type="text" class="form-control" value="{{old('sender_phone')}}"
                                                name="sender_phone"
                                                id="sender_phone" 
                                                readonly required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>@lang('Email')</label>
                                            <input type="email" class="form-control" name="sender_email"
                                            id="sender_email"
                                                value="{{old('sender_email')}}" readonly required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>@lang('Adresse')</label>
                                            <input type="text" class="form-control" name="sender_address"
                                            id="sender_address"
                                                value="{{old('sender_address')}}" readonly >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card border--primary mt-3">
                                <h5 class="card-header bg--primary  text-white">@lang('Information Destinataire(Magasin Central)')</h5>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>@lang('Selectionner un Magasin Central')</label>
                                            <select class="form-control" name="magasin_central" id="magasin_central" onchange="getReceiver()" required>
                                                <option value>@lang('Selectionner une option')</option>
                                                @foreach($magCentraux as $magasin)
                                                <option value="{{$magasin->id}}" 
                                                data-name ="{{$magasin->user->lastname}} {{$magasin->user->firstname}}"
                                                data-phone ="{{$magasin->user->mobile}}"
                                                data-email ="{{$magasin->user->email}}"
                                                data-adresse ="{{$magasin->user->adresse}}"
                                                @selected(old('magasin_central')==$magasin->id)>{{__($magasin->nom)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label>@lang('Nom')</label>
                                            <input type="text" class="form-control" name="receiver_name"
                                            id="receiver_name"
                                                value="{{old('receiver_name')}}" readonly required>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label>@lang('Contact')</label>
                                            <input type="text" class="form-control" name="receiver_phone"
                                            id="receiver_phone"
                                                value="{{old('receiver_phone')}}" readonly required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        
                                        <div class="form-group col-lg-12">
                                            <label>@lang('Email')</label>
                                            <input type="email" class="form-control" name="receiver_email"
                                            id="receiver_email"
                                                value="{{old('receiver_email')}}" readonly required>
                                        </div>
                                        <div class="form-group col-lg-12">
                                            <label>@lang('Adresse')</label>
                                            <input type="text" class="form-control" name="receiver_address"
                                            id="receiver_address"
                                                value="{{old('receiver_address')}}" readonly 
                                                >
                                        </div>
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card border--primary mt-3">
                                <h5 class="card-header bg--primary  text-white">@lang('Information Transporteur')</h5>
                                <div class="card-body">
                                    <div class="row">
                                    <div class="form-group col-lg-6">
                                            <label>@lang('Selectionner un Transporteur')</label>
                                            <select class="form-control" name="sender_transporteur" id="sender_transporteur" onchange="getDriver()" required>
                                                <option value>@lang('Selectionner une option')</option>
                                                @foreach($transporteurs as $transporteur)
                                                <option value="{{$transporteur->id}}"
                                                data-name ="{{$transporteur->nom}} {{$transporteur->prenoms}}"
                                                data-phone ="{{$transporteur->phone1}}"
                                                data-email ="{{$transporteur->email}}" 
                                                @selected(old('sender_transporteur')==$transporteur->id)>{{__($transporteur->nom)}} {{__($transporteur->prenoms)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label>@lang('Selectionner un Véhicule')</label>
                                            <select class="form-control" name="sender_vehicule" id="sender_vehicule" required>
                                                <option value>@lang('Selectionner une option')</option>
                                                @foreach($vehicules as $vehicule)
                                                <option value="{{$vehicule->id}}" 
                                                @selected(old('sender_vehicule')==$vehicule->id)>{{__($vehicule->marque->nom)}}({{__($vehicule->vehicule_immat)}})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label>@lang('Nom')</label>
                                            <input type="text" class="form-control" name="transporteur_name"
                                            id="transporteur_name"
                                                value="{{old('transporteur_name')}}" readonly required>
                                        </div>
                                        <div class=" form-group col-lg-6">
                                            <label>@lang('Contact')</label>
                                            <input type="text" class="form-control" value="{{old('transporteur_phone')}}"
                                                name="transporteur_phone"
                                                id="transporteur_phone" 
                                                readonly required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>@lang('Email')</label>
                                            <input type="email" class="form-control" name="transporteur_email"
                                            id="transporteur_email"
                                                value="{{old('transporteur_email')}}" readonly>
                                        </div>
                                    </div>
                                     
                                </div>
                            </div>
                        </div>  
                    </div>
                    
                    <div class="row mb-30">
                        <div class="col-lg-12">
                            <div class="card border--primary mt-3">
                                <h5 class="card-header bg--primary text-white">@lang('Information de Livraison') 
                                </h5>
                                <div class="card-body">
                                    <div class="row" id="">
                                    <div class="form-group row">
                                <?php echo Form::label(__('Producteur'), null, ['class' => 'col-sm-4 control-label required']); ?>
                                <div class="col-xs-12 col-sm-8">
                                    <?php echo Form::select('producteur_id[]', [], null, ['class' => 'form-control producteurs select2', 'id' => 'producteurs', 'required', 'multiple']); ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <?php echo Form::label(null, null, ['class' => 'col-sm-4 control-label']); ?>
                                <div class="col-xs-12 col-sm-8">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th colspan="2">@lang('Producteur')</th>
                                                <th>@lang('Quantité')</th>
                                                <th>@lang('Total sacs')</th>
                                            </tr>
                                        </thead>
                                        <tbody id="listeprod">

                                        </tbody>

                                    </table>
                                </div>
                            </div>

                                    </div>
                                    <div class="border-line-area">
                                        <h6 class="border-line-title">@lang('Resume')</h6>
                                    </div>
                                   
                                    
                                    <div class=" d-flex justify-content-end mt-2">
                                        <div class="col-md-5 d-flex justify-content-between">
                                            <span class="fw-bold">@lang('Poids(Kg)'):</span>
                                            <div> <input type="number" name="poidsnet" id="poidsnet" class="form-control" readonly
                                        required /></div>
                                        </div>
                                        
                                    </div>
                                    <div class=" d-flex justify-content-end mt-2">
                                        <div class="col-md-5 d-flex justify-content-between">
                                            <span class="fw-bold">@lang('Nombre de sacs'):</span>
                                            <div> <input type="number" name="nombresacs" id="nombresacs" min="0"
                                        class="form-control" /></div>
                                        </div>
                                        
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
 

                    <button type="submit" class="btn btn--primary mt-25 h-45 w-100 Submitbtn"> @lang('Envoyer')</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@endsection  

 

@push('script')
<script src="{{asset('assets/fcadmin/js/vendor/datepicker.min.js')}}"></script>
<script src="{{asset('assets/fcadmin/js/vendor/datepicker.en.js')}}"></script>
<script src="{{asset('assets/fcadmin/js/vendor/datepicker.fr.js')}}"></script>
<script src="{{asset('assets/fcadmin/js/tokens.js')}}"></script>
<script>
    "use strict"; 
    $('#producteurs').select2();
    $(".scelleOld").select2({tags: true });

    function getReceiver() {
        let name = $("#magasin_central").find(':selected').data('name'); 
        let phone = $("#magasin_central").find(':selected').data('phone'); 
        let email = $("#magasin_central").find(':selected').data('email'); 
        let adresse = $("#magasin_central").find(':selected').data('adresse'); 
        $('#receiver_name').val(name); 
        $('#receiver_phone').val(phone);
        $('#receiver_email').val(email);
        $('#receiver_address').val(adresse);
    }
    function getDriver() { 
        let name = $("#sender_transporteur").find(':selected').data('name'); 
        let phone = $("#sender_transporteur").find(':selected').data('phone'); 
        let email = $("#sender_transporteur").find(':selected').data('email');  
        $('#transporteur_name').val(name); 
        $('#transporteur_phone').val(phone);
        $('#transporteur_email').val(email); 
    }
    function getSender() { 
        let idmag = $("#sender_magasin").find(':selected').data('id'); 
        let name = $("#sender_magasin").find(':selected').data('name'); 
        let phone = $("#sender_magasin").find(':selected').data('phone'); 
        let email = $("#sender_magasin").find(':selected').data('email'); 
        let adresse = $("#sender_magasin").find(':selected').data('adresse'); 
        $('#sender_name').val(name); 
        $('#sender_phone').val(phone);
        $('#sender_email').val(email);
        $('#sender_address').val(adresse); 

    }
    $('#sender_magasin').change(function() { 
$.ajax({
    type: 'GET',
    url: "{{ route('manager.livraison.get.producteur') }}",
    data: $('#flocal').serialize(),
    success: function(html) {
         
        $('#producteurs').html(html);

    }
});
});

$('#producteurs').change(function() {
 
$.ajax({
    type: 'GET',
    url: "{{ route('manager.livraison.get.listeproducteur') }}",
    data: $('#flocal').serialize(),
    success: function(html) {
        $('#listeprod').html(html.results);
        $('#poidsnet').val(html.total);
        $('#nombresacs').val(html.totalsacs);
        $("#nombresacs").attr({
            "max": html.totalsacs, // substitute your own
            "min": 1 // values (or variables) here
        });
    }
});
});

$('#flocal').change('keyup change blur', function() {
            update_amounts();
        });

        function update_amounts() {
            var sum = 0;
            var sumsacs = 0;

            $('#listeprod > tr').each(function() {

                var qty = $(this).find('.quantity').val();
                var qtysacs = $(this).find('.nbsacs').val();
                sum = parseFloat(sum) + parseFloat(qty);
                sumsacs = parseFloat(sumsacs) + parseFloat(qtysacs);

            });
            $('#poidsnet').val(sum);
            $('#nombresacs').val(sumsacs);
            $("#nombresacs").attr({
                "max": sumsacs, // substitute your own
                "min": 0 // values (or variables) here
            });
        } 

    (function ($) {

        $('.addUserData').on('click', function () {
            
            let count = $("#addedField select").length;
            let length=$("#addedField").find('.single-item').length; 
               
            let html = `
            <div class="row single-item gy-2">
                <div class="col-md-3">
                    <select class="form-control selected_type producteur" name="items[${length}][producteur]" required id='producteur-${length}' onchange=getParcelle(${length})>
                    </select>
                </div>  
                <div class="col-md-3">
                    <select class="form-control" name="items[${length}][parcelle]" required id="parcelle-${length}">
                        
                    </select>
                </div>
                <div class="col-md-2">
                <select class="form-control" name="items[${length}][type]" required> 
                            <option value="{{ __('Certifie') }}">{{ __('Certifie') }}</option>
                            <option value="{{ __('Ordinaire') }}">{{ __('Ordinaire') }}</option>
                    </select> 
                </div>
                <div class="col-md-2">
                    <div class="input-group mb-3">
                        <input type="number" class="form-control quantity" placeholder="@lang('Qte')" disabled name="items[${length}][quantity]"  required>
                        <span class="input-group-text unit">Kg</span>
                    </div>
                </div> 
                <div class="col-md-3">
                    <div class="input-group">
                        <input type="text"  class="form-control single-item-amount" placeholder="@lang('Entrer le prix')" name="items[${length}][amount]" required readonly>
                        <span class="input-group-text">{{__($general->cur_text)}}</span>
                    </div>
                </div>
                <div class="col-md-1">
                    <button class="btn btn--danger w-100 removeBtn w-100 h-45" type="button">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <br><hr class="panel-wide">
            </div>`;
            $('#addedField').append(html)
        });

        $('#addedField').on('change', '.selected_type', function (e) {
            let unit = $(this).find('option:selected').data('unit');
            let parent = $(this).closest('.single-item');
            $(parent).find('.quantity').attr('disabled', false);
            $(parent).find('.unit').html(`${unit || '<i class="las la-balance-scale"></i>'}`);
            calculation();
        });

        $('#addedField').on('click', '.removeBtn', function (e) {
            let length=$("#addedField").find('.single-item').length;
            if(length <= 1){
                notify('warning',"@lang('Au moins un élément est requis')");
            }else{
                $(this).closest('.single-item').remove();
            }
            calculation();
        });

        let discount=0;

        $('.discount').on('input',function (e) {
            this.value = this.value.replace(/^\.|[^\d\.]/g, '');

             discount=parseFloat($(this).val() || 0);
             if(discount >=100){
                discount=100;
                notify('warning',"@lang('La réduction ne peut être supérieure à 100 %')");
                $(this).val(discount);
             }
            calculation();
        });

        $('#addedField').on('input', '.quantity', function (e) {
            this.value = this.value.replace(/^\.|[^\d\.]/g, '');

            let quantity = $(this).val();
            if (quantity <= 0) {
                quantity = 0;
            }
            quantity=parseFloat(quantity);

            let parent   = $(this).closest('.single-item');
            let price    = parseFloat($(parent).find('.selected_type option:selected').data('price') || 0);
            let subTotal = price*quantity;

            $(parent).find('.single-item-amount').val(subTotal.toFixed(0));

            calculation()
        });

        function calculation ( ) {
            let items    = $('#addedField').find('.single-item');
            let subTotal = 0;

            $.each(items, function (i, item) {
                let price = parseFloat($(item).find('.selected_type option:selected').data('price') || 0);
                let quantity = parseFloat($(item).find('.quantity').val() || 0);
                subTotal+=price*quantity;
            });

            // subTotal=parseFloat(subTotal);

            // let discountAmount = (subTotal/100)*discount;
            // let total          = subTotal-discountAmount;

            // $('.subtotal').text(subTotal.toFixed(0));
            // $('.total').text(total.toFixed(0));
            $('.total').text(subTotal.toFixed(0));
        };

        $('.dates').datepicker({
            language  : 'fr',
            dateFormat: 'yyyy-mm-dd',
            maxDate   : new Date()
        });

        @if(old('items'))
            calculation();
        @endif

    })(jQuery);
    
</script>
@endpush

@push('style')
    <style>
        .border-line-area {
            position: relative;
            text-align: center;
            z-index: 1;
        }
        .border-line-area::before {
            position: absolute;
            content: '';
            top: 50%;
            left: 0;
            width: 100%;
            height: 1px;
            background-color: #e5e5e5;
            z-index: -1;
        }
        .border-line-title {
            display: inline-block;
            padding: 3px 10px;
            background-color: #fff;
        }
    </style>
@endpush
