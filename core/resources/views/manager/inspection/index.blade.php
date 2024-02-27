@extends('manager.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
        <div class="card b-radius--10 mb-3">
                <div class="card-body">
                    <form action="">
                        <div class="d-flex flex-wrap gap-4">
                            <input type="hidden" name="table" value="inspections"/>
                            <div class="flex-grow-1">
                                <label>@lang('Recherche par Mot(s) clé(s)')</label>
                                <input type="text" name="search" value="{{ request()->search }}" class="form-control">
                            </div>
                            <div class="flex-grow-1">
                                <label>@lang('Localité')</label>
                                <select name="localite" class="form-control" id="localite">
                                    <option value="">@lang('Toutes')</option>
                                    @foreach($localites as $local)
                                    <option value="{{ $local->id }}">{{ $local->nom }}</option>
                                    @endforeach 
                                </select>
                            </div> 
                            <div class="flex-grow-1">
                                <label>@lang('Producteur')</label>
                                <select name="producteur" class="form-control" id="producteur">
                                    <option value="">@lang('Tous')</option>
                                    @foreach($producteurs as $producteur)
                                    <option value="{{ $producteur->id }}" data-chained="{{ $producteur->localite->id }}">{{ $producteur->nom }} {{ $producteur->prenoms }}</option>
                                    @endforeach 
                                </select>
                            </div> 
                            <div class="flex-grow-1">
                                <label>@lang('Inspecteur')</label>
                                <select name="staff" class="form-control">
                                    <option value="">@lang('Tous')</option>
                                    @foreach($staffs as $staff)
                                    <option value="{{ $staff->id }}">{{ $staff->lastname }} {{ $staff->firstname }}</option>
                                    @endforeach 
                                </select>
                            </div> 
                            <div class="flex-grow-1">
                                <label>@lang('Date')</label>
                                <input name="date" type="text" class="dates form-control" placeholder="@lang('Date de début - Date de fin')" autocomplete="off" value="{{ request()->date }}">
                            </div>
                            <div class="flex-grow-1 align-self-end">
                                <button class="btn btn--primary w-100 h-45"><i class="fas fa-filter"></i> @lang('Filter')</button>
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
                                <th>@lang('Type inspection')</th>
                                    <th>@lang('Localite')</th>  
                                    <th>@lang('Producteur')</th>
                                    <th>@lang('Formateur')</th>
                                    <th>@lang('Note')</th>
                                    <th>@lang('Date_evaluation')</th>  
                                    <th>@lang('Ajoutée le')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($inspections as $inspection)
                                    <tr>
                                    <td> 
                                            @foreach(json_decode($inspection->certificat) as $data)
                                                <span class="btn btn-sm btn-outline--success">{{ $data }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $inspection->producteur->localite->nom }}</span>
                                        </td> 
                                        
                                        <td> 
                                            <span class="small">
                                            {{ $inspection->producteur->nom }} {{ $inspection->producteur->prenoms }}
                                            </span>
                                        </td>
                                        <td>
                                            <span>{{ $inspection->user->lastname }} {{ $inspection->user->firstname }}</span>
                                        </td>
                                        <td>
                                            <span>{{ $inspection->note }}%</span>
                                        </td>
                                        <td>
                                        <span class="d-block">{{ showDateTime($inspection->created_at) }}</span>
                                            <span>{{ diffForHumans($inspection->created_at) }}</span>
                                        </td> 
                                        <td>
                                            <span class="d-block">{{ showDateTime($inspection->date_evaluation) }}</span>
                                            <span>{{ diffForHumans($inspection->date_evaluation) }}</span>
                                        </td>
                                        <td> @php echo $inspection->statusBadge; @endphp </td>
                                        <td>
                                        <a href="{{ route('manager.suivi.inspection.exportExcel.inspectionAll',['id'=>encrypt($inspection->id)]) }}" class="btn  btn-outline--info ml-1"><i class="las la-cloud-download-alt"></i> Exporter</a>
                                        <a href="{{ route('manager.suivi.inspection.show', $inspection->id) }}"
                                                    class="btn btn-outline--warning ml-1"><i class="la la-file"></i>@lang('Details')</a>
                                            <button type="button" class="btn btn-sm btn-outline--primary" data-bs-toggle="dropdown" aria-expanded="false"><i
                                                    class="las la-ellipsis-v"></i>@lang('Action')
                                             </button>
                                            <div class="dropdown-menu p-0">
                                                
                                                @if ($inspection->status == Status::DISABLE)
                                                    <button type="button" class="confirmationBtn  dropdown-item"
                                                        data-action="{{ route('manager.suivi.inspection.status', $inspection->id) }}"
                                                        data-question="@lang('Are you sure to enable this inspection?')">
                                                        <i class="la la-eye"></i> @lang('Activé')
                                                    </button>
                                                @else
                                                    <button type="button" class="confirmationBtn dropdown-item"
                                                        data-action="{{ route('manager.suivi.inspection.status', $inspection->id) }}"
                                                        data-question="@lang('Are you sure to disable this inspection?')">
                                                        <i class="la la-eye-slash"></i> @lang('Désactivé')
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
                @if ($inspections->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($inspections) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins') 
    <a href="{{ route('manager.suivi.inspection.create') }}" class="btn  btn-outline--primary h-45 addNewCooperative">
        <i class="las la-plus"></i>@lang("Ajouter nouveau")
    </a>
    <a href="{{ route('manager.suivi.inspection.exportExcel.inspectionAll') }}" class="btn  btn-outline--warning h-45"><i class="las la-cloud-download-alt"></i> Exporter en Excel</a>
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
        $("#producteur").chained("#localite");
        (function($) {
            "use strict";

            $('.dates').datepicker({
                maxDate:new Date(),
                range:true,
                multipleDatesSeparator:"-",
                language:'en'
            });

            let url=new URL(window.location).searchParams;
            if(url.get('localite') != undefined && url.get('localite') != ''){
                $('select[name=localite]').find(`option[value=${url.get('localite')}]`).attr('selected',true);
            }
            if(url.get('producteur') != undefined && url.get('producteur') != ''){
                $('select[name=producteur]').find(`option[value=${url.get('producteur')}]`).attr('selected',true);
            }
            if(url.get('staff') != undefined && url.get('staff') != ''){
                $('select[name=staff]').find(`option[value=${url.get('staff')}]`).attr('selected',true);
            }

        })(jQuery)
    </script>
@endpush
