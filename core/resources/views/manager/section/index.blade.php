@extends('manager.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body  p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Cooperative')</th>
                                    <th>@lang('Section')</th>
                                    <th>@lang('Ajoutée le')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sections as $section)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ __($section->cooperative->name) }}</span>
                                        </td>
                                        <td> 
                                            <span class="small">
                                                <a href="{{ route('manager.section.edit', $section->id) }}">
                                                    <span>@</span>{{$section->libelle }}
                                                </a>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="d-block">{{ showDateTime($section->created_at) }}</span>
                                            <span>{{ diffForHumans($section->created_at) }}</span>
                                        </td>
                                        {{-- <td>
                                            <a href="{{route('manager.section.localitesection', $section->id)}}" class="icon-btn btn--info ml-1">@lang('Voir localités')</a>
                                        </td> --}}
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline--primary" data-bs-toggle="dropdown" aria-expanded="false"><i
                                                    class="las la-ellipsis-v"></i>@lang('Action')
                                             </button>
                                            <div class="dropdown-menu p-0">
                                                <a href="{{ route('manager.section.edit', $section->id) }}"
                                                    class="dropdown-item"><i class="la la-pen"></i>@lang('Edit')
                                                </a>
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
                @if ($sections->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($sections) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    {{-- modal qui permet d'importer des sections (besoins d'explication)--}}
    <div id="typeModel" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Importer des sections')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i> </button>
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">   
                        <p>Fichier d'exemple à utiliser :<a href="" target="_blank">@lang('section-import-exemple.xlsx')</a></p>
                    </div>    
                    <div class="form-group row">
                        <label class="col-sm-4 control-label">@lang('Type de Formation')</label>
                        <div class="col-xs-12 col-sm-8">
                            <select class="form-control" name="localite_id" required>
                                <option value="">@lang('Selectionner une option')</option>
                                {{-- @foreach($localites as $localite)
                                    <option value="{{ $localite->id }}" @selected(old('cooperative'))> {{ __($localite->libelle) }}</option>
                                @endforeach --}}
                            </select>
                        </div> 
                    </div>
                    <div class="form-group row">
                        
    
                        {{ Form::label(__('Fichier(.xls, .xlsx)'), null, ['class' => 'control-label col-sm-4']) }}
                        <div class="col-xs-12 col-sm-8 col-md-8">
                            <input type="file" name="uploaded_file" accept=".xls, .xlsx" class="form-control dropify-fr" placeholder="Choisir une image" id="image" required> 
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45 ">@lang('Envoyer')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>  
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Search here..." />
    <a href="{{ route('manager.section.create') }}" class="btn  btn-outline--primary h-45 addNewCooperative">
        <i class="las la-plus"></i>@lang("Ajouter nouveau")
    </a>
    <a class="btn  btn-outline--info h-45 addType"><i class="las la-cloud-upload-alt"></i> Importer des Sections</a>
@endpush
@push('style')
    <style>
        .table-responsive {
            overflow-x: auto;
        }
    </style>
@endpush
@push('script')
    <script>
        (function($) {
            "use strict";

            $('.addType').on('click', function() {
                $('#typeModel').modal('show');
            });
              

        })(jQuery)
    </script>
@endpush

