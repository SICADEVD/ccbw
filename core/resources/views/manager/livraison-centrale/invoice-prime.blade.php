@extends('manager.layouts.app')
@section('panel')
    <div class="card">
        <div class="card-body">
            <div id="printFacture">
                

                <div class="invoice"> 
                    <hr>
                    <div class=" invoice-info d-flex justify-content-between">
                         
                        <div style="width:30%;">
                        @lang('A')
                            <address>
                                <strong>COMPAGNIE CACAOYERE DU BANDAMA (CCB)</strong><br>
                                @lang('Contact'): <br>
                                @lang('Email'):
                            </address>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-12">
                            <table class="table table-striped table-responsive">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('Campagne')</th>
                                        <th>@lang('Periode')</th>
                                        <th>@lang('Producteur')</th>
                                        <th>@lang('Parcelle')</th>
                                        <th>@lang('Type produit')</th>
                                        <th>@lang("Date d'envoi")</th>
                                        <th>@lang('Qte')</th>
                                        <th>@lang('Sous-total')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                     
                                    @foreach ($livraisonInfo as $livraisonProductInfo)
                                        <tr>
                                        <td>
                                            {{ $livraisonProductInfo->campagne->nom }} 
                                        </td> 
                                        <td>
                                            {{ $livraisonProductInfo->campagnePeriode->nom }} 
                                        </td> 
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $livraisonProductInfo->parcelle->producteur->nom }} {{ $livraisonProductInfo->parcelle->producteur->prenoms }}</td>
                                            <td>{{ $livraisonProductInfo->parcelle->codeParc }}</td>
                                            <td>{{ __(@$livraisonProductInfo->type_produit) }}</td> 
                                            <td>{{ getAmount($livraisonProductInfo->quantite) }} </td>
                                            <td>
                                                {{ getAmount($livraisonProductInfo->montant) }} {{ $general->cur_sym }}
                                            </td>
                                            <td>
                                                {{ showDateTime($livraisonProductInfo->created_at, 'd M Y') }}
                                            </td>

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
                                            <td>{{ showAmount(@$livraisonInfo->sum('montant')) }} {{ $general->cur_sym }}
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
    <x-back route="{{ route('manager.livraison.prime.producteur') }}" />
@endpush