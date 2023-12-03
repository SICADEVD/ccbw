@extends('manager.layouts.app')
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
                                    <th>@lang('Coopérative Destinataire - Magasin')</th>
                                    <th>@lang("Montant - Numéro Commande")</th>
                                    <th>@lang('Date de livraison')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($livraisonInfos as $livraisonInfo)
                                    <tr>
                                        <td>
                                            <span>{{ __($livraisonInfo->senderCooperative->name) }}</span><br>
                                            <a class="text--primary" href="{{ route('manager.staff.edit', encrypt($livraisonInfo->senderStaff->id)) }}">
                                                <span class="text--primary">@</span>{{ __($livraisonInfo->senderStaff->lastname) }} {{ __($livraisonInfo->senderStaff->firstname) }}
                                            </a>
                                        </td>
                                        <td>
                                            <span>
                                                @if (@$livraisonInfo->receiver_cooperative_id)
                                                    {{ __($livraisonInfo->receiverCooperative->name) }}
                                                @else
                                                    @lang('N/A')
                                                @endif
                                            </span>
                                            <br>
                                            @if(@$livraisonInfo->receiver_magasin_section_id)
                                                <span class="text--primary">{{ __($livraisonInfo->magasinSection->nom) }}</span>
                                            @else
                                                <span>@lang('N/A')</span>
                                            @endif
                                        </td>

                                        <td>
                                            <span class="fw-bold">{{ showAmount(@$livraisonInfo->paymentInfo->final_amount) }}
                                                {{ __($general->cur_text) }}</span><br>
                                            <span>{{ $livraisonInfo->code }}</span>
                                        </td>

                                        <td>
                                            {{ showDateTime($livraisonInfo->estimate_date, 'd M Y') }}<br>
                                            {{ diffForHumans($livraisonInfo->estimate_date) }}
                                        </td>


                                        <td>
                                            @if ($livraisonInfo->status == Status::COURIER_QUEUE)
                                                <span class="badge badge--danger">@lang('Sent In Queue')</span>
                                            @elseif($livraisonInfo->status == Status::COURIER_DISPATCH)
                                                @if (auth()->user()->cooperative_id == $livraisonInfo->sender_cooperative_id)
                                                    <span class="badge badge--warning">@lang("Expédié")</span>
                                                @else
                                                    <span class="badge badge--warning">@lang('Upcoming')</span>
                                                @endif
                                            @elseif($livraisonInfo->status == Status::COURIER_DELIVERYQUEUE)
                                                <span class="badge badge--primary">@lang('Confirmation de reception en attente')</span>
                                            @elseif($livraisonInfo->status == Status::COURIER_DELIVERED)
                                                <span class="badge badge--success">@lang("Livré")</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('manager.livraison.invoice', encrypt($livraisonInfo->id)) }}"
                                                title="" class="btn btn-sm btn-outline--info">
                                                <i class="las la-file-invoice"></i> @lang("Facture")
                                            </a>
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
                @if ($livraisonInfos->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($livraisonInfos) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
<x-search-form placeholder="Livraison Code" />
<x-date-filter placeholder="Start date - End date"/>
<a href="{{ route('manager.livraison.create') }}" class="btn  btn-outline--primary h-45 addNewCooperative">
        <i class="las la-plus"></i>@lang("Enregistrer une livraison")
    </a>
<a href="{{ route('manager.livraison.exportExcel.livraisonAll') }}" class="btn  btn-outline--warning h-45"><i class="las la-cloud-download-alt"></i> Exporter en Excel</a>
@endpush

