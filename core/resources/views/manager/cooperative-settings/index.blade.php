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
                     
                    <input type="hidden" name="id" value="{{ $cooperative->id }}">
                    <input type="hidden" name="codeApp" value="{{ $cooperative->codeApp }}">

                    <div class="modal-body">
                    <div class="form-group">
                    <label>@lang('Statut juridique')</label>
                                <select name="statut_juridique" class="form-control">
                                    <option value="">@lang('Toutes')</option>
                                    <option value="SCOOPS" {{ request()->localite == 'SCOOPS' ? 'selected' : '' }}>SCOOPS</option>
                                        <option value="COOP CA" {{ request()->localite == 'COOP CA' ? 'selected' : '' }}>COOP CA</option>
                                   
                                </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Nom de la cooperative')</label>
                            <input type="text" class="form-control" name="name" value="{{ $cooperative->name }}" readonly required>
                        </div>

                        <div class="form-group">
                            <label>@lang('Code de la cooperative')</label>
                            <input type="text" class="form-control" name="codeCoop" value="{{ $cooperative->codeCoop }}" required>
                        </div>

                        <div class="form-group">
                            <label>@lang('Adresse Email de la coopérative')</label>
                            <input type="email" class="form-control" value="{{ $cooperative->email }}" name="email" required>
                        </div>

                        <div class="form-group">
                            <label>@lang('Contacts')</label>
                            <input type="text" class="form-control" value="{{ $cooperative->phone }}" name="phone" required>
                        </div>
                       

                        <div class="form-group">
                            <label>@lang('Adresse de la coopérative')</label>
                            <input type="text" class="form-control" name="address" value="{{ $cooperative->address }}" required>
                        </div>
                        <hr class="panel-wide">
                        <div class="form-group">
                            <label>@lang('Année de creation')</label>
                            <input type="number" class="form-control years" min="1960" max="{{ gmdate('Y')}}" name="annee_creation" value="{{ $cooperative->annee_creation }}" >
                        </div>
                        <div class="form-group">
                            <label>@lang('Code CCC')</label>
                            <input type="text" class="form-control phone" name="code_ccc" value="{{ $cooperative->code_ccc }}" >
                        </div>
                        <div class="form-group">
                            <label>@lang('Nombre de membres à la creation')</label>
                            <input type="number" class="form-control" name="nb_membres_creation" value="{{ $cooperative->nb_membres_creation }}">
                        </div>
                        <div class="form-group">
                            <label>@lang('Nombre de sections à la creation')</label>
                            <input type="text" class="form-control" name="nb_sections_creation" value="{{ $cooperative->nb_sections_creation }}">
                        </div>
                        <div class="form-group">
                            <label>@lang('Nombre de membres actuel')</label>
                            <input type="number" class="form-control" name="nb_membres_actuel" value="{{ $cooperative->nb_membres_actuel }}" >
                        </div>
                        <div class="form-group">
                            <label>@lang('Nombre de sections actuel')</label>
                            <input type="text" class="form-control" name="nb_sections_actuel" value="{{ $cooperative->nb_sections_actuel }}">
                        </div>
                        <div class="form-group">
                            <label>@lang('Nombre de PCA qui se sont succédés depuis la creation')</label>
                            <input type="text" class="form-control" name="nb_pca_creation" value="{{ $cooperative->nb_pca_creation }}">
                        </div>
                        <div class="form-group">
                    <label>@lang('Existence de Documents administratifs')</label><br>
                                <select name="document[]" class="form-control select-picker" multiple>
                                    <option value="">@lang('Toutes')</option>
                                    @foreach ($documents as $local)
                                        <option value="{{ $local->id }}" {{ request()->localite == $local->id ? 'selected' : '' }}>{{ $local->nom }}</option>
                                    @endforeach
                                </select>
                        </div>
                        <hr class="panel-wide">
                        <div class="form-group">
                            <label>@lang('Longitude')</label>
                            <input type="text" class="form-control" name="longitude" value="{{ $cooperative->longitude }}" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Latitude')</label>
                            <input type="text" class="form-control" name="latitude" value="{{ $cooperative->latitude }}" required>
                        </div>
                        <hr class="panel-wide">
                        <div class="form-group">
                            <label>@lang('Utilisateurs Mobile')</label>
                            <input type="number" class="form-control" name="mobile" value="{{ $cooperative->mobile }}" readonly required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Utilisateurs Web')</label>
                            <input type="number" class="form-control" name="web" value="{{ $cooperative->web }}" readonly required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="save-form" class="btn btn--primary w-100 h-45">@lang('app.save')</button>
                    </div>
               
                    </div>
                </div>
                 
            </div><!-- card end --> 
            </div>
        </x-setting-card>
    
@endsection
 
@push('script')
    <script>
         
        $('#save-form').click(function () {
            var url = "{{ route('manager.settings.cooperative-settings.update', $cooperative->id) }}";
            
            $.easyAjax({
                url: url,
                container: '#editSettings',
                type: "POST",
                disableButton: true,
                blockUI: true,
                buttonSelector: "#save-form",
                data: $('#editSettings').serialize(),
            })
        });
    </script>
@endpush