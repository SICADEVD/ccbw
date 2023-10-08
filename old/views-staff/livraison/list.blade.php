@extends('staff.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>

                                    <th>@lang("Coopérative Expéditeur - Staff")</th>
                                    <th>@lang("Coopérative Destinataire - Magasin")</th>
                                    <th>@lang("Montant - Numéro Commande")</th>
                                    <th>@lang('Creations Date')</th>
                                    <th>@lang('Date estimative de livraison')</th>
                                    <th>@lang("Paiement Status")</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($livraisonLists as $livraisonInfo)
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
                                            <span class="fw-bold d-block">
                                                {{ showAmount(@$livraisonInfo->paymentInfo->final_amount) }}
                                                {{ __($general->cur_text) }}
                                            </span>
                                            <span>{{ $livraisonInfo->code }}</span>
                                        </td>
                                        <td>
                                            {{ showDateTime($livraisonInfo->created_at, 'd M Y') }}
                                        </td>
                                        <td>
                                            {{ showDateTime($livraisonInfo->estimate_date, 'd M Y') }}
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
                                                <span class="badge badge--warning">@lang('Sent in queue')</span>
                                            @elseif ($livraisonInfo->status == Status::COURIER_DISPATCH)
                                                @if (auth()->user()->cooperative_id == $livraisonInfo->sender_cooperative_id)
                                                    <span class="badge badge--warning">@lang("Expédié")</span>
                                                @else
                                                    <span class="badge badge--primary">@lang('Upcoming')</span>
                                                @endif
                                            @elseif ($livraisonInfo->status == Status::COURIER_DELIVERYQUEUE)
                                                <span class="badge badge--danger">@lang('Delivery in queue')</span>
                                            @elseif($livraisonInfo->status == Status::COURIER_DELIVERED)
                                                <span class="badge badge--success">@lang("Livré")</span>
                                            @endif
                                        </td>

                                        <td>
                                            <a href="{{ route('staff.livraison.invoice', encrypt($livraisonInfo->id)) }}"
                                                title="" class="btn btn-sm btn-outline--info"><i
                                                    class="las la-file-invoice"></i> @lang("Facture")</a>
                                            <a href="{{ route('staff.livraison.details', encrypt($livraisonInfo->id)) }}"
                                                title="" class="btn btn-sm btn-outline--primary"><i
                                                    class="las la-info-circle"></i> @lang("Details")</a>
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

                @if ($livraisonLists->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($livraisonLists) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@push('breadcrumb-plugins')
    <x-search-form placeholder="Search here..." />
    <x-date-filter placeholder="Start date - End date" />
@endpush
