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
            <div class="card">
                <div class="card-body">
                    {!! Form::model($localite, [
                        'method' => 'POST',
                        'route' => ['manager.settings.localite-settings.store', $localite->id],
                        'class' => 'form-horizontal',
                        'id' => 'flocal',
                        'enctype' => 'multipart/form-data',
                    ]) !!}
                    @method('POST')
                    <input type="hidden" name="id" value="{{ $localite->id }}">
                    <input type="hidden" name="codeLocal" value="{{ $localite->codeLocal }}">
                    <div class="form-group row">
                        <label class="col-xs-12 col-sm-4">@lang('Select Cooperative')</label>
                        <div class="col-xs-12 col-sm-8">
                            <select class="form-control" disabled>
                                <option value="">{{ __($manager->cooperative->name) }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-xs-12 col-sm-4">@lang('Select section')</label>
                        <div class="col-xs-12 col-sm-8">
                            <select class="form-control" name="section_id">
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}" @selected($section->id == $localite->section_id)>
                                        {{ __($section->libelle) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <?php echo Form::label(__('Nom de la localite'), null, ['class' => 'control-label col-xs-12 col-sm-4']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::text('nom', null, ['placeholder' => __('Nom de la localite'), 'class' => 'form-control', 'required']); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <?php echo Form::label(__('Type de localite'), null, ['class' => 'control-label col-xs-12 col-sm-4']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('type_localites', ['Ville' => 'Ville', 'Campement' => 'Campement', 'Village' => 'Village'], null, ['placeholder' => __('Selectionner une option'), 'class' => 'form-control', 'required']); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <?php echo Form::label(__('Sous préfecture'), null, ['class' => 'control-label col-xs-12 col-sm-4']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::text('sousprefecture', null, ['placeholder' => __('sous prefecture'), 'class' => 'form-control', 'required']); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <?php echo Form::label(__('Estimation de la population'), null, ['class' => 'control-label col-xs-12 col-sm-4']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::number('population', null, ['placeholder' => __('nombre'), 'class' => 'form-control', 'min' => '1']); ?>
                        </div>
                    </div>
                    <hr class="panel-wide">

                    <div class="form-group row">
                        <?php echo Form::label(__('Existe-t-il un centre de santé dans la localité ?'), null, ['class' => 'control-label col-xs-12 col-sm-4']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('centresante', ['oui' => 'oui', 'non' => 'non'], null, ['class' => 'form-control centresante', 'required']); ?>
                        </div>
                    </div>
                    {{-- Si non on affiche le champs si dessous --}}

                    <div class="form-group row" id="kmCentresante">
                        <?php echo Form::label(__('A combien de km du village se situe le centre de santé le plus proche ?'), null, ['class' => 'control-label col-xs-12 col-sm-4']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::number('kmCentresante', null, ['placeholder' => __('nombre'), 'class' => 'form-control kmCentresante', 'min' => '0']); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <?php echo Form::label(__('Nom de ce centre de santé'), null, ['class' => 'control-label col-xs-12 col-sm-4']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::text('nomCentresante', null, ['placeholder' => __('Nom du centre de Santé'), 'class' => 'form-control']); ?>
                        </div>
                    </div>
                    <hr class="panel-wide">

                    <div class="form-group row">
                        <?php echo Form::label(__('Existe-t-il une ou des école(s) primaire(s) publique(s) dans la localité ?'), null, ['class' => 'control-label col-xs-12 col-sm-4']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('ecole', ['non' => 'non', 'oui' => 'oui'], null, ['class' => 'form-control ecole', 'required']); ?>
                        </div>
                    </div>

                    {{-- Si non afficher le champs si dessous --}}
                    <div class="form-group row" id="kmEcoleproche">
                        <?php echo Form::label(__("A combien de km se trouve l'école la plus proche ?"), null, ['class' => 'control-label col-xs-12 col-sm-4']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::number('kmEcoleproche', null, ['placeholder' => __('nombre'), 'class' => 'form-control kmEcoleproche', 'min' => '0']); ?>
                        </div>
                    </div>
                    {{-- fin de champs si dessous --}}

                    <div class="form-group row" id="nomEcoleproche">
                        <?php echo Form::label(__('Nom Ecole primaire'), null, ['class' => 'control-label col-xs-12 col-sm-4']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::text('nomEcoleproche', null, ['placeholder' => '...', 'class' => 'form-control nomEcoleproche', 'required']); ?>
                        </div>
                    </div>

                    <div id="nombrecole">
                        <div class="form-group row">
                            <?php echo Form::label(__('Citez les 3 Principales Ecoles'), null, ['class' => 'control-label col-xs-12 col-sm-4']); ?>
                        </div>

                        <div class="form-group row col-lg-12">
                            <table class="table table-striped table-bordered">
                                <tbody id="maladies">
                                    <?php
                                 if($localite->ecoleprimaires)
        {  
        $i=0;
        $a=1;
        foreach($localite->ecoleprimaires as $data) {
           ?>
                                    <tr>
                                        <td class="row">
                                            <div class="col-xs-12 col-sm-12 bg-success">
                                                <badge class="btn  btn-outline--warning h-45 btn-sm">@lang('Informations sur l\'école primaire 1')
                                                    <?php echo $a; ?></badge>
                                            </div>
                                            <div class="col-xs-12 col-sm-12">
                                                <div class="form-group">
                                                    <input type="text" name="nomecolesprimaires[]"
                                                        placeholder="Nom de l'école primaire"
                                                        id="nomecolesprimaires-<?php echo $a; ?>" class="form-control"
                                                        value="<?php echo $data->nomecole; ?>">
                                                </div>
                                            </div>

                                            <?php if($a>=1):?>
                                            <div class="col-xs-12 col-sm-12"><button type="button"
                                                    id="<?php echo $a; ?>" class="removeRowMal btn btn-danger btn-sm"><i
                                                        class="fa fa-minus"></i></button></div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php
           $a++;
            $i++;
        }
    }else{
        ?>
                                    <tr>
                                        <td class="row">
                                            <div class="col-xs-12 col-sm-12 bg-success">
                                                <badge class="btn  btn-outline--warning h-45 btn-sm">@lang('Informations sur l\'école primaire 1')
                                                </badge>
                                                </badge>
                                            </div>
                                            <div class="col-xs-12 col-sm-12">
                                                <div class="form-group row">
                                                    <input type="text" name="nomecolesprimaires[]"
                                                        placeholder="Nom de l'école primaire" id="nomecolesprimaires-1"
                                                        class="form-control" value="{{ old('nomecolesprimaires') }}">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
        }
        ?>
                                </tbody>
                                <tfoot style="background: #e3e3e3;">
                                    <tr>
                                        <td colspan="3">
                                            <button id="addRowMal" type="button" class="btn btn-success btn-sm"><i
                                                    class="fa fa-plus"></i></button>
                                        </td>
                                    <tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>


                    <hr class="panel-wide">

                    <div class="form-group row">
                        <?php echo Form::label(__("Quelle est la source d'eau potable dans la localité ?"), null, ['class' => 'control-label col-xs-12 col-sm-4']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('sources_eaux', ['Pompe Hydraulique Villageoise' => 'Pompe Hydraulique Villageoise', 'SODECI' => 'SODECI', 'Marigot' => 'Marigot', 'Puits Individuel' => 'Puits Individuel'], null, ['placeholder' => __('selectionner une option'), 'class' => 'form-control sourceeau', 'required']); ?>
                        </div>
                    </div>

                    <div class="form-group row" id="etatpompehydrau">
                        <?php echo Form::label(__('Est-il en bon état ?'), null, ['class' => 'control-label col-xs-12 col-sm-4']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('etatpompehydrau', ['oui' => 'oui', 'non' => 'non'], null, ['class' => 'form-control etatpompehydrau']); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <?php echo Form::label(__("Existe-t-il l'éclairage public dans la localité ?"), null, ['class' => 'control-label col-xs-12 col-sm-4']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('electricite', ['oui' => 'oui', 'non' => 'non'], null, ['class' => 'form-control', 'required']); ?>
                        </div>
                    </div>
                    <hr class="panel-wide">
                    <div class="form-group row">
                        <?php echo Form::label(__('Existe-t-il un marché dans la localité ?'), null, ['class' => 'control-label col-xs-12 col-sm-4']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('marche', ['oui' => 'oui', 'non' => 'non'], null, ['class' => 'form-control marche', 'required']); ?>
                        </div>
                    </div>

                    <div class="form-group row" id="jourmarche">
                        <?php echo Form::label(__('Quel est le jour du marché ?'), null, ['class' => 'control-label col-xs-12 col-sm-4']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('jourmarche', ['Lundi' => __('lundi'), 'Mardi' => __('mardi'), 'Mercredi' => __('mercredi'), 'Jeudi' => __('jeudi'), 'Vendredi' => __('vendredi'), 'Samedi' => __('samedi'), 'Dimanche' => __('dimanche')], null, ['class' => 'form-control jourmarche']); ?>
                        </div>
                    </div>
                    <div class="form-group row" id="kmmarcheproche">
                        <?php echo Form::label(__('A combien de km se trouve le marché le plus proche ?'), null, ['class' => 'control-label col-xs-12 col-sm-4']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::number('kmmarcheproche', null, ['placeholder' => __('Kilomètre du marché le plus proche'), 'class' => 'form-control kmmarcheproche', 'min' => '1']); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <?php echo Form::label(__('Existe-t-il un endroit public pour le déversement des déchets dans la localité ?'), null, ['class' => 'control-label col-xs-12 col-sm-4']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('deversementDechets', ['oui' => 'oui', 'non' => 'non'], null, ['class' => 'form-control', 'required']); ?>
                        </div>
                    </div>
                    <hr class="panel-wide">
                    <div class="form-group row">
                        <?php echo Form::label(__("Nombre comite de main d'œuvre qu'il y a dans la localité"), null, ['class' => 'control-label col-xs-12 col-sm-4']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::number('comiteMainOeuvre', null, ['placeholder' => __('nombre'), 'class' => 'form-control', 'min' => '0']); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <?php echo Form::label(__("Nombre d'association de femmes qu'il y a dans la localité"), null, ['class' => 'control-label col-xs-12 col-sm-4']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::number('associationFemmes', null, ['placeholder' => __('nombre'), 'class' => 'form-control', 'min' => '0']); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <?php echo Form::label(__('Nombre d’association de jeunes qu’il y a dans la localité'), null, ['class' => 'control-label col-xs-12 col-sm-4']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::number('associationJeunes', null, ['placeholder' => __('nombre'), 'class' => 'form-control', 'min' => '0']); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <?php echo Form::label(__("Nombre d'association de jeunes qu'il y a dans la localité"), null, ['class' => 'control-label col-xs-12 col-sm-4']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::number('associationJeunes', null, ['placeholder' => __('nombre'), 'class' => 'form-control', 'min' => '0']); ?>
                        </div>
                    </div>
                    <hr class="panel-wide">

                    <div class="form-group row">
                        <?php echo Form::label(__('Prise des coordonnées gps de la localité'), null, ['class' => 'control-label col-xs-12 col-sm-12']); ?>
                    </div>
                    <div class="form-group row">
                        <?php echo Form::label('Longitude', null, ['class' => 'control-label col-xs-12 col-sm-4']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::text('localongitude', null, ['placeholder' => __('longitude'), 'class' => 'form-control']); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <?php echo Form::label('Latitude', null, ['class' => 'control-label col-xs-12 col-sm-4']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::text('localatitude', null, ['placeholder' => __('latitude'), 'class' => 'form-control']); ?>
                        </div>
                    </div>


                    <div class="form-group">
                        <button type="submit" id="save-form" class="btn btn--primary w-100 h-45"> @lang('app.save')</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        </x-setting-card>
@endsection
@push('breadcrumb-plugins')
    <x-back route="{{ route('manager.settings.localite-settings.index') }}" />
@endpush
@push('script')
    <script type="text/javascript">
        $(document).ready(function() {

            var maladiesCount = $("#maladies tr").length + 1;
            $(document).on('click', '#addRowMal', function() {

                //---> Start create table tr
                var html_table = '<tr>';
                html_table +=
                    '<td class="row"><div class="col-xs-12 col-sm-12 bg-success"><badge class="btn btn-warning btn-sm">Nom Ecole Primaire ' +
                    maladiesCount +
                    '</badge></div><div class="col-xs-12 col-sm-12"><div class="form-group"><input placeholder="..." class="form-control" id="nomecolesprimaires-' +
                    maladiesCount +
                    '" name="nomecolesprimaires[]" type="text"></div></div><div class="col-xs-12 col-sm-8"><button type="button" id="' +
                    maladiesCount +
                    '" class="removeRowMal btn btn-danger btn-sm"><i class="fa fa-minus"></i></button></div></td>';

                html_table += '</tr>';
                //---> End create table tr

                maladiesCount = parseInt(maladiesCount) + 1;
                $('#maladies').append(html_table);

            });

            $(document).on('click', '.removeRowMal', function() {

                var row_id = $(this).attr('id');

                // delete only last row id
                if (row_id == $("#maladies tr").length) {

                    $(this).parents('tr').remove();

                    maladiesCount = parseInt(maladiesCount) - 1;

                }
            });
            if ($('.marche').val() == 'oui') {
                $('#kmmarcheproche').hide('slow');
                $('.kmmarcheproche').css('display', 'block');
            } else {
                $('#kmmarcheproche').show('slow');
            }
            $('.marche').change(function() {
                var marche = $('.marche').val();
                if (marche == 'non') {
                    $('#kmmarcheproche').show('slow');
                    $('.kmmarcheproche').css('display', 'block');
                } else {
                    $('#kmmarcheproche').hide('slow');
                }
            });
            // CENTRE DE SANTE

            if ($('.centresante').val() == 'oui') {
                $('#kmCentresante').hide('slow');
                $('.kmCentresante').css('display', 'block');
            } else {
                $('#kmCentresante').show('slow');

            }
            $('.centresante').change(function() {
                var centresante = $('.centresante').val();
                if (centresante == 'non') {
                    $('#kmCentresante').show('slow');
                    $('.kmCentresante').css('display', 'block');
                } else {
                    $('#kmCentresante').hide('slow');
                    $('.kmCentresante').val('');
                }
            });

            // ECOLE
            if ($('.ecole').val() == 'oui') {
                $('#kmEcoleproche').hide('slow');
                $('.kmEcoleproche').val('');

                $('#nombrecole').show('slow');
                $('.nombrecole').show('slow');

                $('#nomEcoleproche').hide('slow');
                $('.nomEcoleproche').val('');
                $('.nombrecole').css('display', 'block');
            } else {
                $('#kmEcoleproche').show('slow');
                $('.kmEcoleproche').show('slow');
                $('#nombrecole').hide('slow');
                $('nomEcoleproche').show('slow');
                $('.kmEcoleproche').css('display', 'block');
            }

            $('.ecole').change(function() {
                var ecole = $('.ecole').val();
                if (ecole == 'oui') {
                    $('#kmEcoleproche').hide('slow');
                    $('.kmEcoleproche').val('');

                    $('#nomEcoleproche').hide('slow');
                    $('.nomEcoleproche').val('');

                    $('#nombrecole').show('slow');
                    $('.nombrecole').show('slow');
                    $('.nombrecole').css('display', 'block');
                } else {
                    $('#kmEcoleproche').show('slow');
                    $('.kmEcoleproche').show('slow');

                    $('#nomEcoleproche').show('slow');
                    $('.nomEcoleproche').show('slow');

                    $('#nombrecole').hide('slow');
                    $('.nombrecole').val('');
                }
            });
            // EAU HYDRAULIQUE
            if ($('.sourceeau').val() == 'Pompe Hydraulique Villageoise') {
                $('#etatpompehydrau').show('slow');
                $('.etatpompehydrau').css('display', 'block');
            } else {
                $('#etatpompehydrau').hide('slow');
            }

            $('.sourceeau').change(function() {
                var sourceeau = $('.sourceeau').val();
                if (sourceeau == 'Pompe Hydraulique Villageoise') {
                    $('#etatpompehydrau').show('slow');
                    $('.etatpompehydrau').css('display', 'block');
                } else {
                    $('#etatpompehydrau').hide('slow');
                }
            });

        });

        $('#save-form').click(function () {
            var url = "{{ route('manager.settings.localite-settings.store') }}";
            
            $.easyAjax({
                url: url,
                container: '#editSettings',
                type: "POST",
                disableButton: true,
                blockUI: true,
                redirect: true,
                buttonSelector: "#save-form",
                data: $('#editSettings').serialize(),
                success: function (response) {
                    if (response.status == 'success') {
                        window.location.href = response.redirectUrl;
                    }
                }
            })
        });
    </script>
@endpush
