@extends('manager.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body">
                    {!! Form::open([
                        'route' => ['manager.traca.parcelle.store'],
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
                        <label class="col-sm-4 control-label">@lang('Selectionner un producteur')</label>
                        <div class="col-xs-12 col-sm-8">
                            <select class="form-control" name="producteur" id="producteur" required>
                                <option value="">@lang('Selectionner une option')</option>
                                @foreach ($producteurs as $producteur)
                                    <option value="{{ $producteur->id }}" data-chained="{{ $producteur->localite->id }}"
                                        @selected(old('producteur'))>
                                        {{ $producteur->nom }} {{ $producteur->prenoms }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ Form::label(__('Quelle est l\'année de création de la parcelle'), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::number('anneeCreation', null, ['placeholder' => 'Année de création', 'class' => 'form-control', 'id' => 'anneeCreation', 'required', 'min' => 1990]); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ Form::label(__('Quel est l\'âge moyen des cacaoyers ?'), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::text('ageMoyenCacao', null, ['placeholder' => 'Age moyen des cacaoyers', 'class' => 'form-control', 'id' => 'ageMoyenCacao', 'required']); ?>
                        </div>
                    </div>


                    <div class="form-group row">
                        {{ Form::label(__('Est ce que la parcelle a été régenerer ?'), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('parcelleRegenerer', ['non' => __('non'), 'oui' => __('oui')], null, ['class' => 'form-control parcelleRegenerer', 'required']); ?>
                        </div>
                    </div>
                    <div id="anneeRegenerer">
                        <div class="form-group row">
                            {{ Form::label(__('Année de régéneration'), null, ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::number('anneeRegenerer', null, ['placeholder' => 'Année de régéneration', 'class' => 'form-control anneeRegenerer']); ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            {{ Form::label(__('Superficie concerné'), null, ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::number('superficieConcerne', null, ['placeholder' => 'Superficie concernée', 'class' => 'form-control superficieConcerne']); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        {{ Form::label(__('Quel type de documents  possèdes-tu ?'), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('typeDoc', ['Attestation de plantation' => 'Attestation de plantation', 'Attestation coutumières' => 'Attestation coutumières', 'Cadastre' => 'Cadastre', 'Certificat foncier' => 'Certificat foncier', 'Contrat agraire' => 'Contrat agraire', 'Aucun document' => 'Aucun document'], null, ['class' => 'form-control', 'required']); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ Form::label(__('Ya-t-il un cour ou plan d’eau dans la parcelle ?'), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('presenceCourDeau', ['non' => __('non'), 'oui' => __('oui')], null, ['class' => 'form-control presenceCourDeau', 'required']); ?>
                        </div>
                    </div>
                    <div class="form-group row" id="courDeau">
                        {{ Form::label(__('Quel est le cour ou plan d\'eau'), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('courDeau', ['Bas-fond' => 'Bas-fond', 'Marigot' => 'Marigot', 'Rivière' => 'Rivière', 'Source d’eau' => 'Source d’eau', 'Autre' => 'Autre'], null, ['class' => 'form-control courDeau']); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ Form::label(__('Est ce qu\'il existe des mésures de protection ?'), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('existeMesureProtection', ['non' => __('non'), 'oui' => __('oui')], null, ['class' => 'form-control existeMesureProtection', 'required']); ?>
                        </div>
                    </div>

                    
                    <div class="form-group row" id="protection">
                        <label class="col-sm-4 control-label">@lang('Sélectionner les protections')</label>
                        <div class="col-xs-12 col-sm-8">
                            <select class="form-control select2-multi-select" name="protection[]" multiple>
                                <option value="">@lang('Selectionner les protections')</option>
                                <option value="barriere de végétation">Barrière de végétation</option>
                                <option value="zone tampon">Zone tampon</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                    </div>


                    <div class="form-group row">
                        {{ Form::label(__('Ya-t-il une pente dans la Parcelle ?'), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('existePente', ['non' => __('non'), 'oui' => __('oui')], null, ['class' => 'form-control existePente', 'required']); ?>
                        </div>
                    </div>
                    <div class="form-group row" id="niveauPente">
                        {{ Form::label(__('Quel est le niveau de la pente?'), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('niveauPente', ['Douce' => 'Douce', 'Moyenne' => 'Moyenne', 'Forte' => 'Forte'], null, ['class' => 'form-control niveauPente']); ?>
                        </div>
                    </div>
                    
                    <hr class="panel-wide">
                    <div class="form-group row">
                        {{ Form::label(__('Information GPS de la parcelle'), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-12">
                            <table class="table table-bordered">
                                <tbody id="product_area">

                                    <tr>
                                        <td class="row">

                                            <div class="col-xs-12 col-sm-12">
                                                <div class="form-group row">
                                                    {{ Form::label(__('Superficie'), null, ['class' => 'col-sm-4 control-label required']) }}
                                                    {!! Form::text('superficie', null, [
                                                        'placeholder' => __('Superficie'),
                                                        'class' => 'form-control superficie',
                                                        'id' => 'superficie-1',
                                                        'required',
                                                    ]) !!}

                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-12">
                                                <div class="form-group row">
                                                    {{ Form::label(__('Latitude'), null, ['class' => 'col-sm-4 control-label']) }}
                                                    {!! Form::text('latitude', null, [
                                                        'placeholder' => __('Latitude'),
                                                        'class' => 'form-control',
                                                        'id' => 'latitude-1',
                                                    ]) !!}

                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-12">
                                                <div class="form-group row">
                                                    {{ Form::label(__('Longitude'), null, ['class' => 'col-sm-4 control-label']) }}
                                                    {!! Form::text('longitude', null, [
                                                        'placeholder' => __('Longitude'),
                                                        'class' => 'form-control',
                                                        'id' => 'longitude-1',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <hr class="panel-wide">

                    <div class="form-group row">

                        <?php echo Form::label(__('Fichier KML ou GPX existant'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <input type="file" name="fichier_kml_gpx" class="form-control dropify-fr">
                        </div>
                    </div>

                    <hr class="panel-wide">

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
    <x-back route="{{ route('manager.traca.parcelle.index') }}" />
@endpush

@push('script')
    <script type="text/javascript">
        $("#producteur").chained("#localite");
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#anneeRegenerer,#courDeau,#protection,#niveauPente').hide();

            $('.parcelleRegenerer').change(function() {
                var parcelleRegenerer = $('.parcelleRegenerer').val();
                if (parcelleRegenerer == 'oui') {
                    $('#anneeRegenerer').show('slow');
                    $('.anneeRegenerer').show('slow');
                } else {
                    $('#anneeRegenerer').hide('slow');
                    $('.anneeRegenerer').val('');
                    $('.superficieConcerne').val('');

                }
            });

            $('.presenceCourDeau').change(function() {
                var presenceCourDeau = $('.presenceCourDeau').val();
                if (presenceCourDeau == 'oui') {
                    $('#courDeau').show('slow');
                    $('.courDeau').show('slow');

                } else {
                    $('#courDeau').hide('slow');
                    $('.courDeau').val('');

                }
            });
            $('.existePente').change(function() {
                var existPente = $('.existePente').val();
                if (existPente == 'oui') {
                    $('#niveauPente').show('slow');
                    $('.niveauPente').show('slow');

                } else {
                    $('#niveauPente').hide('slow');
                    $('.niveauPente').val('');

                }
            });
            $('.existeMesureProtection').change(function() {
                var existeMesureProtection = $('.existeMesureProtection').val();
                if (existeMesureProtection == 'oui') {
                    $('#protection').show('slow');

                } else {
                    $('#protection').hide('slow');
                    $('select[name="protection[]"]').empty();

                }
            });

        });
    </script>
@endpush
