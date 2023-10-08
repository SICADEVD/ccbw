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
                                    <th>
                                        <input type="checkbox" class="checkAll"> @lang('Select All')
                                    </th>
                                    <th>@lang("Coopérative Expéditeur - Staff")</th>
                                    <th>@lang("Coopérative Destinataire - Magasin")</th>
                                    <th>@lang("Montant - Numéro Commande")</th>
                                    <th>@lang('Creations Date')</th>
                                    <th>@lang('Date estimative de livraison')</th>
                                    <th>@lang("Paiement Status")</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($livraisonLists as $livraisonInfo)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="childCheckBox" data-id="{{ $livraisonInfo->id }}">
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ __($livraisonInfo->senderCooperative->name) }}</span><br>

                                            <small class="text-mute"><i>
                                                    {{ __($livraisonInfo->senderStaff->fullname) }}</i></small>
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
                                            <span class="fw-bold">{{ showAmount(@$livraisonInfo->paymentInfo->final_amount) }}
                                                {{ __($general->cur_text) }}</span><br>
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
                                            <a href="{{ route('staff.livraison.invoice', encrypt($livraisonInfo->id)) }}"
                                                title="" class="btn btn-sm btn-outline--info"><i
                                                    class="las la-file-invoice"></i>@lang("Facture")</a>
                                            <a href="{{ route('staff.livraison.details', encrypt($livraisonInfo->id)) }}"
                                                title="" class="btn btn-sm btn-outline--primary"><i
                                                    class="las la-info-circle"></i>@lang("Details")</a>
                                            <button type="button" class="btn btn-sm btn-outline--success confirmationBtn"
                                                data-action="{{ route('staff.livraison.dispatched', $livraisonInfo->id) }}"
                                                data-question="@lang('Etes-vous sûr de vouloir confirmer l\'envoi de ce(s) produit(s)?')">
                                                <i class="las la-arrow-circle-right"></i>@lang("Expédié")
                                            </button>
                                            <a href="{{ route('staff.livraison.edit', encrypt($livraisonInfo->id)) }}"
                                                class="btn btn-sm btn-outline--primary">
                                                <i class="las la-pen"></i>@lang('Edit')
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                                <tr class="d-none dispatch">
                                    <td colspan="8">
                                        <button class="btn btn-sm btn--primary h-45 w-100 " id="dispatch_all"> <i
                                                class="las la-arrow-circle-right "></i> @lang("Expédié")</button>
                                    </td>
                                </tr>

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

    <x-confirmation-modal />
@endsection


@push('breadcrumb-plugins')
    <x-search-form placeholder="Search here..." />
    <x-date-filter placeholder="Start date - End date" /> 
@endpush
@push('script')
    <script>
        (function($) {
            "use strict";
            $(".childCheckBox").on('change', function(e) {
                let totalLength = $(".childCheckBox").length;
                let checkedLength = $(".childCheckBox:checked").length;
                if (totalLength == checkedLength) {
                    $('.checkAll').prop('checked', true);
                } else {
                    $('.checkAll').prop('checked', false);
                }
                if (checkedLength) {
                    $('.dispatch').removeClass('d-none')
                } else {
                    $('.dispatch').addClass('d-none')
                }
            });

            $('.checkAll').on('change', function() {
                if ($('.checkAll:checked').length) {
                    $('.childCheckBox').prop('checked', true);
                } else {
                    $('.childCheckBox').prop('checked', false);
                }
                $(".childCheckBox").change();
            });
            $('#dispatch_all').on('click', function() {
                let ids = [];
                $('.childCheckBox:checked').each(function() {
                    ids.push($(this).attr('data-id'))
                })
                let id = ids.join(',')
                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('staff.livraison.dispatch.all') }}",
                    data: {
                        id: id
                    },
                    success: function(data) {
                        notify('success', 'Livraison dispatched successfully')
                        location.reload();
                    }
                })
            });

        })(jQuery)
    </script>
@endpush
