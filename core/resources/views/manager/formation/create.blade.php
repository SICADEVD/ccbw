@extends('manager.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body">
                    {!! Form::open([
                        'route' => ['manager.suivi.formation.store'],
                        'method' => 'POST',
                        'class' => 'form-horizontal',
                        'id' => 'flocal',
                        'enctype' => 'multipart/form-data',
                    ]) !!}

                    <div class="form-group row">
                        <label class="col-sm-4 control-label">@lang('Selectionner une localite')</label>
                        <div class="col-xs-12 col-sm-8">
                            <select class="form-control" name="localite" id="localite" required>
                                <option value="">@lang('Selectionner une option')</option>
                                @foreach ($localites as $localite)
                                    <option value="{{ $localite->id }}" @selected(old('localite'))>
                                        {{ $localite->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 control-label">@lang('Les producteurs présents à la formation')</label>
                        <div class="col-xs-12 col-sm-8">
                            <select class="form-control select2-multi-select" name="producteur[]" id="producteur" multiple
                                required>
                                <option value="">@lang('Selectionner une option')</option>
                                @foreach ($producteurs as $producteur)
                                    <option value="{{ $producteur->id }}" data-chained="{{ $producteur->localite->id }}"
                                        @selected(old('producteur'))>
                                        {{ $producteur->nom }} {{ $producteur->prenoms }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <hr class="panel-wide">
                    <div class="form-group row">
                        <?php echo Form::label(__('Type de la formation'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('formation_type', ['Cible' => 'Ciblé', 'Groupe' => 'Groupé'], null, ['placeholder' => __('Selectionner une option'), 'class' => 'form-control', 'id' => 'formation_type', 'required' => 'required']); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <?php echo Form::label(__('Lieu de la formation'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('lieu_formation', ['Dans le ménage' => 'Dans le ménage', 'Place Publique' => 'Place Publique', 'Champs Ecole' => 'Champs Ecole'], null, ['placeholder' => __('Selectionner une option'), 'class' => 'form-control', 'id' => 'lieu_formations', 'required' => 'required']); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <?php echo Form::label(__('Module de la formation'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <select class="form-control select2-multi-select" name="type_formation[]" id="typeformation"
                                multiple required>
                                <option value="">@lang('Selectionner une option')</option>
                                @foreach ($typeformations as $typeformation)
                                    <option value="{{ $typeformation->id }}" @selected(old('type_formation'))>
                                        {{ $typeformation->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <?php echo Form::label(__('Thème de la formation'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <select class="form-control select2-multi-select" name="theme[]" id="theme" multiple
                                required>
                                <option value="">@lang('Selectionner une option')</option>
                                @foreach ($themes as $theme)
                                    <option value="{{ $theme->id }}" data-chained="{{ $theme->type_formation_id ?? '' }}"
                                        @selected(old('theme'))>
                                        {{ $theme->nom }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <?php echo Form::label(__('Sous-thème de la formation'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <select class="form-control select2-multi-select" name="sous_theme[]" id="sous_theme"
                                multiple required>
                                <option value="">@lang('Selectionner une option')</option>
                                @foreach ($sousThemes as $soustheme)
                                    <option value="{{ $soustheme->id }}" data-chained="{{ $soustheme->theme_formation_id ?? '' }}"
                                        @selected(old('sous_theme'))>
                                        {{ $soustheme->nom }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <hr class="panel-wide">

                    <div class="form-group row">
                        <?php echo Form::label(__('Staff ayant dispensé la formation'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <select class="form-control" name="staff" id="staff" required>
                                <option value="">@lang('Selectionner une option')</option>
                                @foreach ($staffs as $staff)
                                    <option value="{{ $staff->id }}" @selected(old('staff'))>
                                        {{ $staff->lastname }} {{ $staff->firstname }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <?php echo Form::label(__('Date de Début & Fin de la formation'), null, ['class' => 'col-sm-4 control-label required']); ?>
                        <div class="col-xs-12 col-sm-8">

                            <?php echo Form::text('multi_date', now('Africa/Abidjan')->translatedFormat('Y-m-d'), ['class' => 'form-control', 'id' => 'multi_date', 'required' => 'required']); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <?php echo Form::label(__('Durée de la formation'), null, ['class' => 'col-sm-4 control-label required']); ?>
                        <div class="col-xs-12 col-sm-8 bootstrap-timepicker timepicker">
                            <?php echo Form::text('duree_formation', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Ex : 04:10']); ?>

                        </div>
                    </div>

                    <div class="form-group row">
                        <?php echo Form::label(__('Observation de la formation'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::textarea('observation_formation', null, ['class' => 'form-control duree_formation', 'rows' => 4]); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <?php echo Form::label(__('Photo de la formation'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <input type="file" name="photo_formation" class="form-control dropify-fr">
                        </div>
                    </div>

                    <div class="form-group row">
                        <?php echo Form::label(__('Rapport de la formation'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <input type="file" name="rapport_formation" class="form-control dropify-fr"
                                data-allowed-file-extensions="pdf docx doc xls xlsx">
                        </div>
                    </div>

                    <hr class="panel-wide">

                    <input type="hidden" name="multiStartDate" id="multiStartDate">
                    <input type="hidden" name="multiEndDate" id="multiEndDate">

                    <div class="form-group row">
                        <button type="submit" class="btn btn--primary w-100 h-45"> @lang('Envoyer')</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('manager.suivi.formation.index') }}" />
@endpush

@push('script')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/daterangepicker.css') }}">
    <script src="{{ asset('assets/vendor/jquery/daterangepicker.min.js') }}"></script>
    <script type="text/javascript">
        $("#producteur").chained("#localite");
       
        $('#duree_formation').timepicker({
            showMeridian: (false)
        });

        $('#multi_date').daterangepicker({
            linkedCalendars: false,
            multidate: true,
            todayHighlight: true,
            format: 'yyyy-mm-d'
        });
        $('#multi_date').change(function() {
            var dates = $(this).val();

            var startDate = moment(new Date(dates.split(' - ')[0]));
            var endDate = moment(new Date(dates.split(' - ')[1]));
            var totalDays = endDate.diff(startDate, 'days') + 1;

            startDate = startDate.format('YYYY-MM-DD');

            endDate = endDate.format('YYYY-MM-DD');

            var multiDate = [];
            multiDate = [startDate, endDate];
            $('#multi_date').val(multiDate);

            $('#multiStartDate').val(startDate);
            $('#multiEndDate').val(endDate);
            $('.date-range-days').html(totalDays + ' Jours sélectionnés');
        });
        $(document).ready(function() {
            //idée de ce bloque de code c'est de remplire l'objet optionParTheme avec les themes provenant de la base de données
            var optionParTheme = new Object();
            $("#theme option").each(function() {
                var curreentArray = optionParTheme[($(this).data('chained'))] ? optionParTheme[($(this)
                    .data('chained'))] : [];
                curreentArray[$(this).val()] = $(this).text().trim();
                Object.assign(optionParTheme, {
                    [$(this).data('chained')]: curreentArray
                });
                $(this).remove();
            });

            var optionParSousTheme = new Object();
            $("#sous_theme option").each(function() {
                var curreentArray = optionParSousTheme[($(this).data('chained'))] ? optionParSousTheme[($(this)
                    .data('chained'))] : [];
                curreentArray[$(this).val()] = $(this).text().trim();
                Object.assign(optionParSousTheme, {
                    [$(this).data('chained')]: curreentArray
                });
                $(this).remove();
                console.log(optionParSousTheme);
            });

            $('#typeformation').change(function() {
                var typeformation = $(this).val();
                $("#theme").empty();
                $("#sous_theme").empty();
                var optionsHtml2 = "";
                window.optionSousTheme = "";
                $(this).find('option:selected').each(function() {
                    //console.log($(this).val());
                    optionsHtml2 = updateTheme(optionsHtml2, $(this).val(), optionParTheme, optionParSousTheme);
                })
            });

            $('#theme').change(function() {
                $("#sous_theme").empty();
                window.optionSousTheme = "";
                $(this).find('option:selected').each(function() {
                    //console.log($(this).val());
                    window.optionSousTheme = updateSousTheme(window.optionSousTheme, $(this).val(), optionParSousTheme);
                })
            });
        });

        function updateTheme(optionsHtml2, id, optionParTheme, optionParSousTheme) {
            var optionsHtml = optionsHtml2
            if (id != '') {
                optionParTheme[id].forEach(function(key, element) {
                    optionsHtml += '<option value="' + element + '">' + key + '</option>';
                    window.optionSousTheme = updateSousTheme(window.optionSousTheme, element, optionParSousTheme);
                });
                $("#theme").html(optionsHtml);
            }
            return optionsHtml;
        }
        
        function updateSousTheme(optionsHtml2, id, optionParSousTheme) {
            var optionsHtml = optionsHtml2
            if (id != '' && optionParSousTheme[id] != undefined) {
                optionParSousTheme[id].forEach(function(key, element) {
                    optionsHtml += '<option value="' + element + '">' + key + '</option>';
                });
                $("#sous_theme").html(optionsHtml);
            }
            return optionsHtml;
        }
    </script>
@endpush
