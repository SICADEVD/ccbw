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
                                <th>@lang('Section')</th>
                                    <th>@lang('Staff')</th> 
                                    <th>@lang('Nom magasin')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Last Update')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($magasinSections as $magasin)
                                    <tr>
                                    <td>
                                            <span>{{ $magasin->section->libelle }}</span>
                                        </td> 
                                    <td>
                                            <span>{{  $magasin->user->lastname }} {{  $magasin->user->firstname }}</span>
                                        </td> 
                                        
                                        <td>
                                            <span>{{ __($magasin->nom) }}</span>
                                        </td> 
                                        <td>
                                            @php
                                                echo $magasin->statusBadge;
                                            @endphp
                                        </td>

                                        <td>
                                            <span class="d-block">{{ showDateTime($magasin->updated_at) }}</span>
                                            <span>{{ diffForHumans($magasin->updated_at) }}</span>
                                        </td>

                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline--primary  updateType"
                                                data-id="{{ $magasin->id }}" 
                                                data-nom="{{ $magasin->nom }}"
                                                data-user="{{ $magasin->staff_id }}"
                                                data-section="{{ $magasin->section_id }}"><i
                                                 class="las la-pen"></i>@lang('Edit')</button>

                                            @if ($magasin->status == Status::DISABLE)
                                                <button type="button"
                                                    class="btn btn-sm btn-outline--success confirmationBtn"
                                                    data-action="{{ route('manager.settings.magasinSection.status', $magasin->id) }}"
                                                    data-question="@lang('Etes-vous sûr de vouloir activer ce magasin de formation?')">
                                                    <i class="la la-eye"></i> @lang('Activé')
                                                </button>
                                            @else
                                                <button type="button"
                                                    class="btn btn-sm btn-outline--danger confirmationBtn"
                                                    data-action="{{ route('manager.settings.magasinSection.status', $magasin->id) }}"
                                                    data-question="@lang('Etes-vous sûr de vouloir désactiver ce magasin de formation?')">
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
                @if ($magasinSections->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($magasinSections) }}
                    </div>
                @endif
            </div>
        </div>
        </x-setting-card>
    <div id="typeModel" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Ajouter un Magasin de Section')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i> </button>
                </div>
                <form action="{{ route('manager.settings.magasinSection.store') }}" method="POST">
                    @csrf
                    <div class="modal-body"> 
                    <input type="hidden" name='id'>
                    <div class="form-group row">
                                <label class="col-sm-4 control-label">@lang('Nom Staff')</label>
                                <div class="col-xs-12 col-sm-8">
                                <select class="form-control" name="user" id="user" required>
                                    <option value="">@lang('Selectionner une option')</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" @selected(old('user'))>
                                            {{ __($user->nom) }}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div> 
                            <div class="form-group row">
                                <label class="col-sm-4 control-label">@lang('Section')</label>
                                <div class="col-xs-12 col-sm-8">
                                <select class="form-control" name="section" id="section" required>
                                    <option value="">@lang('Selectionner une option')</option>
                                    @foreach($sections as $section)
                                        <option value="{{ $section->localite->section->id }}" @selected(old('section')) data-chained="{{ $section->user_id }}">
                                            {{ __($section->localite->section->libelle) }}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div> 

        <div class="form-group row">
            {{ Form::label(__('Nom du magasin'), null, ['class' => 'control-label col-sm-4']) }}
            <div class="col-xs-12 col-sm-8 col-md-8">
            {!! Form::text('nom', null, array('placeholder' => __('Nom du magasin'),'class' => 'form-control','required')) !!}
        </div>
    </div>
    
 
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45 ">@lang('Enregistrer')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <button class="btn btn-sm btn-outline--primary addType"><i class="las la-plus"></i>@lang("Ajouter nouveau")</button>
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
                modal.find('select[name=user]').val($(this).data('user')); 
                modal.find('select[name=section]').val($(this).data('section'));
                modal.modal('show');
            });
        })(jQuery);

        $("#section").chained("#user");
    </script>
@endpush