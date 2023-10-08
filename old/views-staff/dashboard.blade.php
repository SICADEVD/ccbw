@extends('staff.layouts.app')
@section('panel')
    <div class="row gy-4">
        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--purple has-link box--shadow2">
                <a href="{{ route('staff.livraison.sent.queue') }}" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las la-hourglass-start f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text-white text--small">@lang("Livraison en attente")</span>
                            <h2 class="text-white">{{ $sentInQueue }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->
        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--cyan has-link box--shadow2">
                <a href="{{ route('staff.livraison.upcoming') }}" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las la-history f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text-white text--small">@lang("Livraison encours")</span>
                            <h2 class="text-white">{{ $upcomingLivraison }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->
        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--primary has-link overflow-hidden box--shadow2">
                <a href="{{ route('staff.livraison.dispatch') }}" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las la-dolly f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text-white text--small">@lang("Total Livraison envoyée")</span>
                            <h2 class="text-white">{{ $dispatchLivraison }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->





        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--pink has-link box--shadow2">
                <a href="{{ route('staff.livraison.delivery.queue') }}" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="lab la-accessible-icon f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text-white text--small">@lang("Livraison reçue")</span>
                            <h2 class="text-white">{{ $deliveryInQueue }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->

        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--green has-link box--shadow2">
                <a href="{{ route('staff.livraison.manage.sent.list') }}" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las la-check-double f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text-white text--small">@lang("Total Envoyé")</span>
                            <h2 class="text-white">{{ $totalSent }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->

        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--deep-purple has-link box--shadow2">
                <a href="{{ route('staff.livraison.manage.delivered') }}" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las  la-list-alt f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text-white text--small">@lang("Total Livré")</span>
                            <h2 class="text-white">{{ $totalDelivery }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->
        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--lime has-link box--shadow2">
                <a href="{{ route('staff.cooperative.index') }}" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las la-university f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text-white text--small">@lang("Total Coopérative")</span>
                            <h2 class="text-white">{{ $cooperativeCount }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->
        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--orange has-link box--shadow2">
                <a href="{{ route('staff.cash.livraison.income') }}" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las la-money-bill-wave f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text-white text--small">@lang("Total Recette")</span>
                            <h2 class="text-white">{{ showAmount($cashCollection) }} {{ $general->cur_sym }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->

        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--teal has-link box--shadow2">
                <a href="{{ route('staff.livraison.index') }}" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las  la-shipping-fast f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text-white text--small">@lang("Toutes les livraisons")</span>
                            <h2 class="text-white">{{ $totalLivraison }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->

    </div><!-- row end-->
    <div class="row mt-30 ">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-header mb-1">
                    <h6>@lang("Livraison Encours")</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang("Coopérative Expéditeur - Staff")</th>
                                    <th>@lang("Coopérative Destinataire - Magasin")</th>
                                    <th>@lang("Montant - Numéro Commande")</th>
                                    <th>@lang('Creations Date')</th>
                                    <th>@lang("Paiement Status")</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($livraisonDelivery as $livraisonInfo)
                                    <tr>
                                        <td>
                                            <span>{{ __($livraisonInfo->senderCooperative->name) }}</span><br>
                                            {{ __($livraisonInfo->senderStaff->fullname) }}
                                        </td>

                                        <td>
                                            <span>
                                                @if ($livraisonInfo->receiver_cooperative_id)
                                                    {{ __($livraisonInfo->receiverCooperative->name) }}
                                                @else
                                                    @lang('N/A')
                                                @endif
                                            </span>
                                            <br>
                                            @if ($livraisonInfo->receiver_magasin_section_id)
                                                {{ __($livraisonInfo->magasinSection->nom) }} - {{ __($livraisonInfo->magasinSection->email) }}
                                            @else
                                                <span>@lang('N/A')</span>
                                            @endif
                                        </td>

                                        <td>
                                            <span class="fw-bold">{{ getAmount($livraisonInfo->paymentInfo->final_amount) }}
                                                {{ __($general->cur_text) }}</span><br>
                                            <span>{{ $livraisonInfo->code }}</span>
                                        </td>

                                        <td>
                                            <span>{{ showDateTime($livraisonInfo->created_at, 'd M Y') }}</span><br>
                                            <span>{{ diffForHumans($livraisonInfo->created_at) }}</span>
                                        </td>

                                        <td>
                                            @if (@$livraisonInfo->paymentInfo->status == Status::PAYE)
                                                <span class="badge badge--success">@lang('Paye')</span>
                                            @elseif(@$livraisonInfo->paymentInfo->status == Status::IMPAYE)
                                                <span class="badge badge--danger">@lang('Impaye')</span>
                                            @endif
                                        </td>



                                        <td>
                                            @if ($livraisonInfo->status == Status::COURIER_QUEUE)
                                                <span class="badge badge--primary">@lang("Reçu")</span>
                                            @elseif($livraisonInfo->status == Status::COURIER_DISPATCH)
                                                <span class="badge badge--">@lang("Envoyé")</span>
                                            @elseif($livraisonInfo->status == Status::COURIER_UPCOMING)
                                                <span class="badge badge--warning">@lang("Encours")</span>
                                            @elseif($livraisonInfo->status == Status::COURIER_DELIVERED)
                                                <span class="badge badge--success">@lang("Livré")</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if ($livraisonInfo->status == Status::COURIER_DELIVERYQUEUE &&
                                                $livraisonInfo->paymentInfo->status == Status::COURIER_UPCOMING)
                                                <a href="javascript:void(0)" title=""
                                                    class="btn btn-sm btn-outline--secondary  delivery"
                                                    data-code="{{ $livraisonInfo->code }}"><i class="las la-truck"></i>
                                                    @lang("Livré")</a>
                                            @endif
                                            @if ($livraisonInfo->status == Status::COURIER_DELIVERYQUEUE &&
                                                $livraisonInfo->paymentInfo->status == Status::COURIER_QUEUE)
                                                <a href="javascript:void(0)" title=""
                                                    class="btn btn-sm btn-outline--success  payment"
                                                    data-code="{{ $livraisonInfo->code }}"><i
                                                        class="las la-credit-card"></i>
                                                    @lang("Paiement")</a>
                                            @endif
                                            <a href="{{ route('staff.livraison.invoice', encrypt($livraisonInfo->id)) }}"
                                                title="" class="btn btn-sm btn-outline--info "><i
                                                    class="las la-file-invoice"></i> @lang("Facture")</a>
                                            <a href="{{ route('staff.livraison.details', encrypt($livraisonInfo->id)) }}"
                                                title="" class="btn btn-sm btn-outline--primary "> <i
                                                    class="las la-info-circle"></i>@lang("Details")</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="paymentBy" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="" lass="modal-title" id="exampleModalLabel">@lang('Confirmation de Paiement')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('staff.livraison.payment') }}" method="POST">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="code">
                    <div class="modal-body">
                        <p>@lang('Etes-vous sûr d\'avoir payer ce(s) producteur(s)?')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Non')</button>
                        <button type="submit" class="btn btn--primary">@lang('Oui')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deliveryBy" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="" lass="modal-title" id="exampleModalLabel">@lang('Confirmation de Livraison')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="fa fa-times"></span>
                    </button>
                </div>
                <form action="{{ route('staff.livraison.delivery') }}" method="POST">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="code">
                    <div class="modal-body">
                        <p>@lang('Etes-vous sûr de terminer cette commande?')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Fermer')</button>
                        <button type="submit" class="btn btn--primary">@lang('Confirmer')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@push('breadcrumb-plugins')
    <div class="d-flex flex-wrap justify-content-end">
        <h3>{{ __(auth()->user()->cooperative->name) }}</h3>
    </div>
@endpush


@push('script')
    <script>
        (function() {
            'use strict';
            $('.payment').on('click', function() {
                var modal = $('#paymentBy');
                modal.find('input[name=code]').val($(this).data('code'))
                modal.modal('show');
            });

            $('.delivery').on('click', function() {
                var modal = $('#deliveryBy');
                modal.find('input[name=code]').val($(this).data('code'))
                modal.modal('show');
            });
        })(jQuery())
    </script>
@endpush
