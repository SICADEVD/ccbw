@extends('manager.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 mb-3">
                <div class="card-body">
                    <form action="">
                        <div class="d-flex flex-wrap gap-4">
                            <input type="hidden" name="table" value="suivi_formaions" />
                            <div class="flex-grow-1">
                                <label>@lang('Recherche par Mot(s) cle(s)')</label>
                                <input type="text" name="search" value="{{ request()->search }}" class="form-control">
                            </div>
                            <div class="flex-grow-1">
                                <label>@lang('Modules de formation')</label>
                                <select name="module" class="form-control">
                                    <option value="">@lang('Tous')</option>
                                    @foreach ($modules as $module)
                                        <option value="{{ $module->id }}">{{ $module->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-grow-1">
                                <label>@lang('Date')</label>
                                <input name="date" type="text" class="dates form-control"
                                    placeholder="@lang('Date de debut - Date de fin')" autocomplete="off" value="{{ request()->date }}">
                            </div>
                            <div class="flex-grow-1 align-self-end">
                                <button class="btn btn--primary w-100 h-45"><i class="fas fa-filter"></i>
                                    @lang('Filter')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card b-radius--10 ">
                <div class="card-body  p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Cooperative')</th>
                                    <th>@lang('Entreprises')</th>
                                    <th>@lang('Formateurs')</th>
                                    <th>@lang('Lieu')</th>
                                    <th>@lang('Date formation')</th>
                                    <th>@lang('Ajoutée le')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($formations as $formation)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $formation->cooperative->name }}</span>
                                        </td>
                                        <td>
                                            <span>
                                                @foreach ($formation->entreprises as $entreprise)
                                                    {{ $entreprise->nom_entreprise }}
                                                    @if (!$loop->last)
                                                        ,
                                                    @endif
                                                @endforeach
                                            </span>
                                        </td>
                                        <td>
                                            <span>
                                                <a href="{{ route('manager.formation-staff.edit', $formation->id) }}">

                                                    <span>@</span>
                                                    @foreach ($formation->formateurs as $formateur)
                                                        {{ $formateur->nom_formateur }}
                                                        {{ $formateur->prenom_formateur }}

                                                        @if (!$loop->last)
                                                            ,
                                                        @endif
                                                    @endforeach
                                                </a>
                                            </span>
                                        </td>
                                        <td>
                                            <span>{{ $formation->lieu_formation }}</span>
                                        </td>
                                        <td>
                                            <span class="d-block">{{ showDateTime($formation->date_formation) }}</span>
                                            <span>{{ diffForHumans($formation->date_formation) }}</span>
                                        </td>
                                        <td>
                                            <span class="d-block">{{ showDateTime($formation->created_at) }}</span>
                                            <span>{{ diffForHumans($formation->created_at) }}</span>
                                        </td>
                                        <td> @php echo $formation->statusBadge; @endphp </td>
                                        <td>

                                            <button type="button" class="btn btn-sm btn-outline--primary"
                                                data-bs-toggle="dropdown" aria-expanded="false"><i
                                                    class="las la-ellipsis-v"></i>@lang('Action')
                                            </button>
                                            <div class="dropdown-menu p-0">
                                                <a href="{{ route('manager.formation-staff.edit', $formation->id) }}"
                                                    class="dropdown-item"><i class="la la-pen"></i>@lang('Edit')</a>
                                                @if ($formation->status == Status::DISABLE)
                                                    <button type="button" class="confirmationBtn  dropdown-item"
                                                        data-action="{{ route('manager.formation-staff.status', $formation->id) }}"
                                                        data-question="@lang('Are you sure to enable this formation?')">
                                                        <i class="la la-eye"></i> @lang('Active')
                                                    </button>
                                                @else
                                                    <button type="button" class="confirmationBtn dropdown-item"
                                                        data-action="{{ route('manager.formation-staff.status', $formation->id) }}"
                                                        data-question="@lang('Are you sure to disable this formation?')">
                                                        <i class="la la-eye-slash"></i> @lang('Désactive')
                                                    </button>
                                                @endif

                                            </div>
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
                @if ($formations->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($formations) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Search here..." />
    <a href="{{ route('manager.formation-staff.create') }}" class="btn  btn-outline--primary h-45 addNewCooperative">
        <i class="las la-plus"></i>@lang('Ajouter nouveau')
    </a>
    <a href="{{ route('manager.formation-staff.exportExcel.formationAll') }}" class="btn  btn-outline--warning h-45"><i
            class="las la-cloud-download-alt"></i> @lang('Exporter en Excel')</a>
@endpush
@push('style')
    <style>
        .table-responsive {
            overflow-x: auto;
        }
    </style>
@endpush
@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/fcadmin/css/vendor/datepicker.min.css') }}">
@endpush
@push('script')
    <script src="{{ asset('assets/fcadmin/js/vendor/datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/fcadmin/js/vendor/datepicker.fr.js') }}"></script>
    <script src="{{ asset('assets/fcadmin/js/vendor/datepicker.en.js') }}"></script>
@endpush
@push('script')
    <script>
        (function($) {
            "use strict";

            $('.dates').datepicker({
                maxDate: new Date(),
                range: true,
                multipleDatesSeparator: "-",
                language: 'en'
            });

            let url = new URL(window.location).searchParams;
            if (url.get('localite') != undefined && url.get('localite') != '') {
                $('select[name=localite]').find(`option[value=${url.get('localite')}]`).attr('selected', true);
            }
            if (url.get('module') != undefined && url.get('module') != '') {
                $('select[name=module]').find(`option[value=${url.get('module')}]`).attr('selected', true);
            }

        })(jQuery)
    </script>
@endpush
