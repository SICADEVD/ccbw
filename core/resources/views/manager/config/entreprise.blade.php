@extends('manager.layouts.app')
@section('panel')
    <x-setting-sidebar :activeMenu="$activeSettingMenu" />
    <x-setting-card>
        <x-slot name="header">
            <div class="s-b-n-header" id="tabs">
                <h2 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    @lang($pageTitle)</h2>
            </div>
        </x-slot>
        <div class="col-lg-12 col-md-12 ntfcn-tab-content-left w-100 p-4 ">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Nom')</th>
                                    <th>@lang('Adresse mail')</th>
                                    <th>@lang('Téléphone')</th>
                                    <th>@lang('Adresse')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Last Update')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($entreprises as $entreprise)
                                    <tr>
                                        <td>
                                            <span>{{ $entreprise->nom_entreprise }}</span>
                                        </td>

                                        <td>
                                            <span>{{ __($entreprise->telephone_entreprise) }}</span>
                                        </td>
                                        <td>
                                            <span>{{ __($entreprise->mail_entreprise) }}</span>
                                        </td>
                                        <td>
                                            <span>{{ __($entreprise->adresse_entreprise) }}</span>
                                        </td>
                                        {{-- <td>
                                            @php
                                                echo $entreprise->statusBadge;
                                            @endphp
                                        </td> --}}

                                        <td>
                                            <span class="d-block">{{ showDateTime($entreprise->updated_at) }}</span>
                                            <span>{{ diffForHumans($entreprise->updated_at) }}</span>
                                        </td>

                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline--primary  updateType"
                                                data-id="{{ $entreprise->id }}" data-nom="{{ $entreprise->nom }}"
                                                data-prenoms="{{ $entreprise->prenoms }}"
                                                data-sexe = "{{ $entreprise->sexe }}"
                                                data-datenaiss = "{{ $entreprise->date_naiss }}"
                                                data-phone1 = "{{ $entreprise->phone1 }}"
                                                data-phone2 = "{{ $entreprise->phone2 }}"
                                                data-nationalite = "{{ $entreprise->nationalite }}"
                                                data-niveauetude = "{{ $entreprise->niveau_etude }}"
                                                data-typepiece = "{{ $entreprise->type_piece }}"
                                                data-numpiece = "{{ $entreprise->num_piece }}"
                                                data-numpermis = "{{ $entreprise->num_permis }}"><i
                                                    class="las la-pen"></i>@lang('Edit')</button>

                                            @if ($entreprise->status == Status::DISABLE)
                                                <button type="button"
                                                    class="btn btn-sm btn-outline--success confirmationBtn"
                                                    data-action="}"
                                                    data-question="@lang('Etes-vous sûr de vouloir activer ce entreprise?')">
                                                    <i class="la la-eye"></i> @lang('Activé')
                                                </button>
                                            @else
                                                <button type="button"
                                                    class="btn btn-sm btn-outline--danger confirmationBtn"
                                                    data-action=""
                                                    data-question="@lang('Etes-vous sûr de vouloir désactiver ce entreprise?')">
                                                    <i class="la la-eye-slash"></i>@lang('Désactivé')
                                                </button>
                                            @endif
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
                @if ($entreprises->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($transporteurs) }}
                    </div>
                @endif
            </div>
        </div>
    </x-setting-card>
    
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <button class="btn btn-sm btn-outline--primary addType"><i class="las la-plus"></i>@lang('Ajouter nouveau')</button>
@endpush


@push('script')
    <script>
        (function($) {
            "use strict";
            $('.addType').on('click', function() {
                $('#typeModel').modal('show');
            });

            $('.updateType').on('click', function() {
                var modal = $('#typeModel');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.find('input[name=nom]').val($(this).data('nom'));
                modal.find('input[name=prenoms]').val($(this).data('prenoms'));
                modal.find('input[name=sexe]').val($(this).data('sexe'));
                modal.find('input[name=date_naiss]').val($(this).data('datenaiss'));
                modal.find('input[name=phone1]').val($(this).data('phone1'));
                modal.find('input[name=phone2]').val($(this).data('phone2'));
                modal.find('select[name=nationalite]').val($(this).data('nationalite'));
                modal.find('select[name=niveau_etude]').val($(this).data('niveauetude'));
                modal.find('select[name=type_piece]').val($(this).data('typepiece'));
                modal.find('input[name=num_piece]').val($(this).data('numpiece'));
                modal.find('input[name=num_permis]').val($(this).data('numpermis'));
                modal.modal('show');
            });
        })(jQuery);

        $("#section").chained("#user");
    </script>
@endpush
