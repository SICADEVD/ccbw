@extends('staff.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
        <div class="card b-radius--10 mb-3">
                <div class="card-body">
                    <form action="">
                        <div class="d-flex flex-wrap gap-4">
                            <input type="hidden" name="table" value="suivi_formaions"/>
                            <div class="flex-grow-1">
                                <label>@lang('Recherche par Mot(s) clé(s)')</label>
                                <input type="text" name="search" value="{{ request()->search }}" class="form-control">
                            </div>
                            <div class="flex-grow-1">
                                <label>@lang('Localité')</label>
                                <select name="localite" class="form-control">
                                    <option value="">@lang('Toutes')</option>
                                    @foreach($localites as $local)
                                    <option value="{{ $local->id }}">{{ $local->nom }}</option>
                                    @endforeach 
                                </select>
                            </div> 
                            <div class="flex-grow-1">
                                <label>@lang('Localité')</label>
                                <select name="module" class="form-control">
                                    <option value="">@lang('Tous')</option>
                                    @foreach($modules as $module)
                                    <option value="{{ $module->id }}">{{ $module->nom }}</option>
                                    @endforeach 
                                </select>
                            </div>
                            <div class="flex-grow-1">
                                <label>@lang('Date')</label>
                                <input name="date" type="text" class="date form-control" placeholder="@lang('Date de début - Date de fin')" autocomplete="off" value="{{ request()->date }}">
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
                                    <th>@lang('Localite')</th>
                                    <th>@lang('Formateur')</th>
                                    <th>@lang('Module')</th>
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
                                            <span class="fw-bold">{{ $formation->localite->nom }}</span>
                                        </td>
                                        <td>
                                            <span> <a href="{{ route('staff.suivi.formation.edit', $formation->id) }}">
                                                    <span>@</span>{{ $formation->user->lastname }} {{ $formation->user->firstname }}
                                                </a></span>
                                        </td> 
                                        <td>
                                            <span>{{ $formation->typeFormation->nom }}</span>
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
                                         
                                            <button type="button" class="btn btn-sm btn-outline--primary" data-bs-toggle="dropdown" aria-expanded="false"><i
                                                    class="las la-ellipsis-v"></i>@lang('Action')
                                             </button>
                                            <div class="dropdown-menu p-0">
                                                @can('staff.suivi.formation.edit')
                                                    <a href="{{ route('staff.suivi.formation.edit', $formation->id) }}"
                                                        class="dropdown-item"><i class="la la-pen"></i>@lang('Edit')
                                                    </a> 
                                                @endcan
                                                @can('staff.suivi.formation.status')
                                                    @if ($formation->status == Status::DISABLE)
                                                        <button type="button" class="confirmationBtn  dropdown-item"
                                                            data-action="{{ route('staff.suivi.formation.status', $formation->id) }}"
                                                            data-question="@lang('Are you sure to enable this formation?')">
                                                            <i class="la la-eye"></i> @lang('Activé')
                                                        </button>
                                                    @else
                                                        <button type="button" class="confirmationBtn dropdown-item"
                                                            data-action="{{ route('staff.suivi.formation.status', $formation->id) }}"
                                                            data-question="@lang('Are you sure to disable this formation?')">
                                                            <i class="la la-eye-slash"></i> @lang('Désactivé')
                                                        </button>
                                                    @endif  
                                                @endcan
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
    @can('staff.suivi.formation.create')
        <a href="{{ route('staff.suivi.formation.create') }}" class="btn  btn-outline--primary h-45 addNewCooperative">
            <i class="las la-plus"></i>@lang("Ajouter nouveau")
        </a>
    @endcan
    @can('staff.suivi.formation.exportExcel')
        <a href="{{ route('staff.suivi.formation.exportExcel.formationAll') }}" class="btn  btn-outline--warning h-45"><i class="las la-cloud-download-alt"></i> Exporter en Excel</a>
    @endcan
@endpush