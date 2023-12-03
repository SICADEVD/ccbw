@extends('manager.layouts.app')
@section('panel')
    <div class="card">
        <div class="card-body">
            <div id="printFacture">
                <div class="content-header">
                    <h3>
                        @lang('Numero Facture'):
                        <small>#{{ $livraisonInfo->invoice_id }}</small>
                        <br>
                        @lang('Date'):
                        {{ showDateTime($livraisonInfo->created_at) }}
                        <br>
                        @lang('Date de livraison: ') {{ showDateTime($livraisonInfo->estimate_date, 'd M Y') }}
                    </h3>
                </div>

                <div class="invoice">
                <?php $numeroScelle=''; ?>
                @foreach ($livraisonInfo->scelles as $scelle)
                            <?php $numeroScelle .= $scelle->numero_scelle.' | '; ?>
                            @endforeach
                    <div class="d-flex justify-content-between mt-3">
                        <div class="text-center">
                            <?php
                            $textQR = 'NUMEROS SCELLES :'."\n".$numeroScelle."\n"."\n".'N° COMMANDE: '.$livraisonInfo->code."\n".'N° FACTURE: '.$livraisonInfo->invoice_id;
                            ?>
                        {!! QrCode::size(150)->generate($textQR) !!}
                             
                        </div>
                        <div>
                            <b>@lang('N° Commande') #{{ $livraisonInfo->code }}</b><br> 
                            <b>@lang('Cooperative d\'envoi'):</b> {{ __($livraisonInfo->senderCooperative->name) }}<br>
                            <b>@lang('Cooperative de reception'):</b> {{ __($livraisonInfo->receiverCooperative->name) }}
                        </div>
                    </div>
                    <hr>
                    <div class=" invoice-info d-flex justify-content-between">
                        <div style="width:30%;">
                            @lang('DE')
                            <address>
                                <strong>{{ __($livraisonInfo->sender_name) }}</strong><br>
                                {{ __($livraisonInfo->sender_address) }}<br>
                                @lang('Contact'): {{ $livraisonInfo->sender_phone }}<br>
                                @lang('Email'): {{ $livraisonInfo->sender_email }}
                            </address>
                        </div> 
                        <div style="width:30%;">
                        @lang('A')
                            <address>
                                <strong>{{ __($livraisonInfo->receiver_name) }}</strong><br>
                                {{ __($livraisonInfo->receiver_address) }}<br>
                                @lang('Contact'): {{ $livraisonInfo->receiver_phone }}<br>
                                @lang('Email'): {{ $livraisonInfo->receiver_email }}
                            </address>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-12">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('Producteur')</th>
                                        <th>@lang('Parcelle')</th>
                                        <th>@lang('Type produit')</th>
                                        <th>@lang("Date d'envoi")</th>
                                        <th>@lang('Qte')</th>
                                        <th>@lang('Sous-total')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                     
                                    @foreach ($livraisonInfo->products as $livraisonProductInfo)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $livraisonProductInfo->parcelle->producteur->nom }} {{ $livraisonProductInfo->parcelle->producteur->prenoms }}</td>
                                            <td>{{ $livraisonProductInfo->parcelle->codeParc }}</td>
                                            <td>{{ __(@$livraisonProductInfo->type_produit) }}</td>
                                            <td>
                                                {{ showDateTime($livraisonProductInfo->created_at, 'd M Y') }}</td>
                                            <td>{{ $livraisonProductInfo->qty }} </td>
                                            <td>
                                                {{ getAmount($livraisonProductInfo->fee) }} {{ $general->cur_sym }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-30 mb-none-30">
                        <div class="col-lg-12 mb-30">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        
                                        <tr>
                                            <th>@lang('Total'):</th>
                                            <td>{{ showAmount(@$livraisonInfo->payment->final_amount) }} {{ $general->cur_sym }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
            <div class="row no-print">
                <div class="col-sm-12">
                    <div class="float-sm-end">
                        <button class="btn btn-outline--primary  printFacture"><i
                                class="las la-download"></i></i>@lang('Imprimer')</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        "use strict";
        $('.printFacture').click(function() {
            $('#printFacture').printThis();
        });
    </script>
@endpush
@push('breadcrumb-plugins')
    <x-back route="{{ route('manager.livraison.index') }}" />
@endpush
