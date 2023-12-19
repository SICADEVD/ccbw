@extends('manager.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body">
                    {!! Form::open([
                        'route' => ['manager.suivi.application.store'],
                        'method' => 'POST',
                        'class' => 'form-horizontal',
                        'id' => 'flocal',
                        'enctype' => 'multipart/form-data',
                    ]) !!}

                    <div class="form-group row">
                        <?php echo Form::label(__('Campagne'), null, ['class' => 'col-sm-4 control-label required']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('campagne_id', $campagnes, null, ['class' => 'form-control campagnes', 'id' => 'campagnes', 'required' => 'required']); ?>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-sm-4 control-label">@lang('Section')</label>
                        <div class="col-xs-12 col-sm-8">
                            <select class="form-control" name="section" id="section" required>
                                <option value="">@lang('Selectionner une option')</option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}" @selected(old('section'))>
                                        {{ $section->libelle }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 control-label">@lang('Localite')</label>
                        <div class="col-xs-12 col-sm-8">
                            <select class="form-control" name="localite" id="localite" required>
                                <option value="">@lang('Selectionner une option')</option>
                                @foreach ($localites as $localite)
                                    <option value="{{ $localite->id }}"
                                        data-chained="{{ $localite->section->id }}"@selected(old('localite'))>
                                        {{ $localite->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 control-label">@lang('Producteur')</label>
                        <div class="col-xs-12 col-sm-8">
                            <select class="form-control" name="producteur" id="producteur" required>
                                <option value="">@lang('Selectionner une option')</option>
                                @foreach ($producteurs as $producteur)
                                    <option value="{{ $producteur->id }}"
                                        data-chained="{{ $producteur->localite->id }}"@selected(old('producteur'))>
                                        {{ $producteur->nom }} {{ $producteur->prenoms }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 control-label">@lang('Parcelle')</label>
                        <div class="col-xs-12 col-sm-8">
                            <select class="form-control" name="parcelle_id" id="parcelle" onchange="getSuperficie()"
                                required>
                                <option value="">@lang('Selectionner une option')</option>
                                @foreach ($parcelles as $parcelle)
                                    @if ($parcelle->producteur)
                                        <option value="{{ $parcelle->id }}" data-chained="{{ $parcelle->producteur->id }}">
                                            {{ __('Parcelle') }} {{ $parcelle->codeParc }}
                                        </option>
                                    @endif
                                @endforeach

                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <?php echo Form::label(__('Qui a réalisé l\'application ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('personneApplication', ['Producteur' => __('Producteur'), 'Applicateur coop' => __('Applicateur coop'), 'Independant' => __('Independant')], null, ['class' => 'form-control personneApplication', 'required']); ?>
                        </div>
                    </div>

                    <div class="form-group row" id="applicateurs">
                        <label class="col-sm-4 control-label">@lang('Applicateur')</label>
                        <div class="col-xs-12 col-sm-8">
                            <select class="form-control" name="applicateur" id="applicateur" required>
                                <option value="">@lang('Selectionner une option')</option>
                                @foreach ($staffs as $staff)
                                    <option value="{{ $staff->id }}" @selected(old('applicateur'))>
                                        {{ $staff->lastname }} {{ $staff->firstname }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div id="infosIndependant">
                        <div class="form-group row">
                            <?php echo Form::label(__('A-t-il suivi une formation ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::select('suiviFormation', ['non' => __('non'), 'oui' => __('oui')], null, ['class' => 'form-control suiviFormation']); ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <?php echo Form::label(__('A-t-il une attestation ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::select('attestion', ['non' => __('non'), 'oui' => __('oui')], null, ['class' => 'form-control attestion']); ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <?php echo Form::label(__('A-t-il fait un bilan de santé ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::select('bilanSante', ['non' => __('non'), 'oui' => __('oui')], null, ['class' => 'form-control bilanSante']); ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <?php echo Form::label(__('possede t-il un EPI ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::select('independantEpi', ['non' => __('non'), 'oui' => __('oui')], null, ['class' => 'form-control independantEpi']); ?>
                            </div>
                        </div>

                        <div class="form-group row" id="etatEpis">
                            <?php echo Form::label(__('Est-il en bon état ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::select('etatEpi', ['non' => __('non'), 'oui' => __('oui')], null, ['class' => 'form-control etatEpi']); ?>
                            </div>
                        </div>
                    </div>
                    <hr class="panel-wide">
                    <div class="form-group row">
                        <div class="col-xs-12 col-sm-12">
                            <table class="table table-striped table-bordered">
                                <tbody id="pesticide_area">
                                    <tr>
                                        <td class="row">
                                            <div class="col-xs-12 col-sm-12 bg-success">
                                                <badge class="btn  btn-outline--warning h-45 btn-sm">@lang('Pesticide')
                                                </badge>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-4">
                                                    <div class="form-group row">
                                                        <label class="control-label">Pesticides</label>
                                                        <select name="pesticides[0][nom]" id="pesticides-1"
                                                            class="form-control">
                                                            <option value="">Selectionner une option</option>
                                                            <option value="Herbicides">Herbicides</option>
                                                            <option value="Fongicides">Fongicides</option>
                                                            <option value="Nematicide">Nematicide</option>
                                                            <option value="Insecticides">Insecticides</option>
                                                            <option value="Acaricides">Acaricides</option>
                                                            <option value="Pesticides">Pesticides</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-4">
                                                    <div class="form-group row">
                                                        {{ Form::label(__('Nom commercial'), null, ['class' => 'control-label']) }}
                                                        <input name="pesticides[0][nomCommercial]" id="nomCommercial-1"
                                                            class="form-control" placeholder="Nom commercial">
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-4">
                                                    <div class="form-group row">
                                                        <label>Matières actives</label>
                                                        <input type="text" name="pesticides[0][matiereActive[]]"
                                                            id="matiereActive-1" class="form-control"
                                                            placeholder="matière active 1, matière active 2 ....">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-xs-12 col-sm-4">
                                                    <div class="form-group row">
                                                        <label class="control-label">Toxicicologie</label>
                                                        <select name="pesticides[0][toxicicologie]" id="toxicicologie-1"
                                                            class="form-control">
                                                            <option value="">Selectionner une option</option>
                                                            <option value="I">I</option>
                                                            <option value="IA">IA</option>
                                                            <option value="IB">IB</option>
                                                            <option value="II">II</option>
                                                            <option value="III">III</option>
                                                            <option value="IV">IV</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-4">
                                                    <div class="form-group row">
                                                        <label>Dose</label>
                                                        <input name="pesticides[0][dose]" id="dose-1"
                                                            class="form-control" placeholder="L/Ha">
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-4">
                                                    <div class="form-group row">
                                                        <label>Fréquence</label>
                                                        <input name="pesticides[0][frequence]" id="frequence-1"
                                                            class="form-control" placeholder="Fréquence">
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                </tbody>
                                <tfoot style="background: #e3e3e3;">
                                    <tr>

                                        <td colspan="3">
                                            <button id="addRowPesticide" type="button" class="btn btn-success btn-sm"><i
                                                    class="fa fa-plus"></i></button>
                                        </td>
                                    <tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="form-group row mt-3">
                        <label class="col-sm-4 control-label">@lang('Maladies observées dans la parcelle')</label>
                        <div class="col-xs-12 col-sm-8">
                            <select class="form-control select2-multi-select protections" name="maladies[]" multiple
                                required>
                                <option value="">@lang('Selectionner les protections')</option>
                                <option value="">Maladie1</option>
                                <option value="">Maladie2</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <?php echo Form::label(__('Superficie Pulvérisée'), null, ['class' => 'col-sm-4 control-label required']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::text('superficiePulverisee', null, ['placeholder' => __('Superficie Pulvérisée'), 'class' => 'form-control superficiePulverisee', 'required', 'min' => '0.1']); ?>
                        </div>
                    </div>


                    <div class="form-group row">
                        <?php echo Form::label(__('Délais de Réentrée du produit en jours'), null, ['class' => 'col-sm-4 control-label required']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::number('delaisReentree', null, ['id' => 'delaisReentree', 'class' => 'form-control', 'required', 'min' => '1']); ?>
                        </div>
                    </div>

                    <hr class="panel-wide">

                    <div class="form-group row">
                        {{ Form::label(__("Date d'application"), null, ['class' => 'col-sm-4 control-label required']) }}
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::date('date_application', null, ['class' => 'form-control dateactivite required', 'required' => 'required']); ?>
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
    <x-back route="{{ route('manager.suivi.application.index') }}" />
@endpush

@push('script')
    <script type="text/javascript">
        function getSuperficie() {
            let superficie = $("#parcelle").find(':selected').data('superficie');
            $('#superficie').val(superficie);
        }

        $(document).ready(function() {

            var pesticideCount = $("#pesticide_area tr").length;
            $(document).on('click', '#addRowPesticide', function() {

                //---> Start create table tr
                var html_table = '<tr>';
                html_table +=
                    '<td class="row"><div class="col-xs-12 col-sm-12 bg-success"><badge class="btn  btn-outline--warning h-45 btn-sm">Pesticide ' +
                    pesticideCount +
                    '</badge></div><div class="col-xs-12 col-sm-4"><div class="form-group row"><label for="" class="">Pesticides</label><select class="form-control" id="pesticides-' +
                    pesticideCount +
                    '" name="pesticides[' + pesticideCount +
                    '][nom]"><option value="">Selectionner une option</option><option value="Herbicides">Herbicides</option><option value="Fongicides">Fongicides</option><option value="Nematicide">Nematicide</option><option value="Insecticide">Insecticide</option><option value="Acaricides">Acaricides</option><option value="Pesticides">Pesticides</option></select></div></div><div class="col-xs-12 col-sm-4"><div class="form-group row"><label> Nom commercial</label><input type="text" name="pesticides[' + pesticideCount +
                    '][nomCommercial]" id="nomCommercial' +
                    pesticideCount +
                    '" class="form-control" placeholder="Nom commercial"></div></div><div class="col-xs-12 col-sm-4"><div class="form-group"><label for="" class="">Matières actives</label><input type="text" name="pesticides[' + pesticideCount +
                    '][matiereActive[]]" id="matiereActive' +
                    pesticideCount +
                    '" class="form-control" placeholder="matière active 1, matière active 2 ...."></div></div><di class="row mt-3"><div class="col-xs-12 col-sm-4"><div class="form-group row"><label class="control-label">Toxicicologie</label><select class="form-control" id="toxicicologie-' +
                    pesticideCount +
                    '" name="pesticides[' + pesticideCount +
                    '][toxicicologie]"> <option value="">Selectionner une option</option><option value="I">I</option><option value="IA">IA</option><option value="IB">IB</option><option value="II">II</option><option value="III">III</option><option value="IV">IV</option></select></div></div><div class="col-xs-12 col-sm-4"><div class="form-group row"><label>Dose</label><input type="text" name="pesticides[' + pesticideCount +
                    '][dose]" id="dose' +
                    pesticideCount +
                    '" class="form-control" placeholder="L/Ha"></div></div><div class="col-xs-12 col-sm-4"><div class="form-group row"><label>Fréquence</label><input type="text" name="pesticides[' + pesticideCount +
                    '][frequence]" id="frequence' +
                    pesticideCount +
                    '" class="form-control" placeholder="Fréquence"></div></div></di><div class="col-xs-12 col-sm-8"><button type="button" id="' +
                    pesticideCount +
                    '" class="removeRowPesticide btn btn-danger btn-sm"><i class="fa fa-minus"></i></button></div></td>';

                html_table += '</tr>';
                //---> End create table tr

                pesticideCount = parseInt(pesticideCount) + 1;
                $('#pesticide_area').append(html_table);

            });
            $(document).on('click', '.removeRowPesticide', function() {

                var row_id = $(this).attr('id');

                // delete only last row id
                if (row_id == $("#pesticide_area tr").length - 1) {

                    $(this).parents('tr').remove();

                    pesticideCount = parseInt(pesticideCount) - 1;
                }
            });

            $('#applicateurs,#infosIndependant,#etatEpis').hide();

            $('.personneApplication').change(function() {
                var personneApplication = $('.personneApplication').val();
                if (personneApplication == 'Independant') {
                    $('#infosIndependant').show('slow');
                    $('.suiviFormation').attr('required', true);
                    $('.attestion').attr('required', true);
                    $('.bilanSante').attr('required', true);

                } else {
                    $('#infosIndependant').hide('slow');
                    $('.suiviFormation').attr('required', false);
                    $('.attestion').attr('required', false);
                    $('.bilanSante').attr('required', false);
                }

                if (personneApplication == 'Applicateur coop') {
                    $('#applicateurs').show('slow');
                } else {
                    $('#applicateurs').hide('slow');
                }
            });
            $('.independantEpi').change(function() {
                var independantEpi = $('.independantEpi').val();
                if (independantEpi == 'oui') {
                    $('#etatEpis').show('slow');
                    $('.etatEpi').attr('required', true);
                } else {
                    $('#etatEpis').hide('slow');
                    $('.etatEpi').attr('required', false);
                }
            });
        });
        $('#localite').chained("#section")
        $("#producteur").chained("#localite");
        $("#parcelle").chained("#producteur");
    </script>
@endpush
