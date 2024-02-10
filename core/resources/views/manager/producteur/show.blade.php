@extends('manager.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body">
                    {!! Form::model($infosproducteur, [
                        'method' => 'POST',
                        'route' => ['manager.traca.producteur.storeinfo'],
                        'class' => 'form-horizontal',
                        'id' => 'flocal',
                        'enctype' => 'multipart/form-data',
                    ]) !!}
                    <input type="hidden" name="producteur_id" value="{{ $infosproducteur->producteur_id }}" />
                    <input type="hidden" name="id" value="{{ $infosproducteur->id }}" />
                    <div class="modal-body">

                        <div class="form-group row">
                            <?php echo Form::label(__('Avez-vous des forets ou jachère ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::select('foretsjachere', ['non' => 'Non', 'oui' => 'Oui'], null, ['class' => 'form-control foretsjachere', 'required','disabled']); ?>

                            </div>
                        </div>


                        <div class="form-group row" id="superficie">
                            <?php echo Form::label(__('Superficie'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::number('superficie', null, ['placeholder' => 'Nombre', 'class' => 'form-control superficie', 'min' => '0','readonly']); ?>
                            </div>
                        </div>


                        <div class="form-group row">
                            <?php echo Form::label(__('Avez-vous D’autres Cultures En Dehors Du Cacao?'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::select('autresCultures', ['non' => 'Non', 'oui' => 'Oui'], null, ['class' => 'form-control autresCultures', 'required','disabled']); ?>

                            </div>
                        </div>
                        <div class="form-group row">
                            <?php echo Form::label('', null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8" id="listecultures">
                                <table class="table table-striped table-bordered">
                                    <tbody id="product_area">
                                        <?php
     
        if($infosproducteur->typesculture)
        {  
        $i=0;
        $a=1;
        foreach($infosproducteur->typesculture as $data) {
           ?>
                                        <tr>
                                            <td class="row">
                                                <div class="col-xs-12 col-sm-12 bg-success">
                                                    <badge class="btn  btn-outline--warning h-45 btn-sm text-white">@lang('Information de la culture')
                                                        <?php echo $a; ?></badge>
                                                </div>
                                                <div class="col-xs-12 col-sm-12">
                                                    <div class="form-group">
                                                        {{ Form::label(__('Type de culture'), null, ['class' => 'control-label']) }}
                                                        <input type="text" name="typeculture[]" readonly
                                                            placeholder="Riz, Maïs, Igname, Banane, ..."
                                                            id="typeculture-<?php echo $a; ?>" class="form-control"
                                                            value="<?php echo $data->typeculture; ?>">
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-12">
                                                    <div class="form-group">
                                                        {{ Form::label(__('Superficie de la culture'), null, ['class' => 'control-label']) }}
                                                        <input type="text" name="superficieculture[]" readonly
                                                            placeholder="Superficie de culture"
                                                            id="superficieculture-<?php echo $a; ?>"
                                                            class="form-control " value="<?php echo $data->superficieculture; ?>">
                                                    </div>
                                                </div>
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
                                                <div class="col-xs-12 col-sm-12 col-md-12 bg-success">
                                                    <badge class="btn  btn-outline--warning h-45 btn-sm text-white">@lang('Information culture 1')
                                                    </badge>
                                                </div>
                                                <div class="col-xs-12 col-sm-12">
                                                    <div class="form-group">
                                                        {{ Form::label(__('Type de culture'), null, ['class' => 'control-label']) }}
                                                        <input type="text" name="typeculture[]" readonly
                                                            placeholder="Riz, Maïs, Igname, Banane, ..." id="typeculture-1"
                                                            class="form-control" value="{{ old('typeculture') }}">
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-12">
                                                    <div class="form-group">
                                                        {{ Form::label(__('Superficie de la culture'), null, ['class' => 'control-label']) }}
                                                        <input type="text" name="superficieculture[]" readonly
                                                            placeholder="Superficie de culture" id="superficieculture-1"
                                                            class="form-control " value="{{ old('superficieculture') }}">
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
                                        <tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        {{-- autre activité en dehors du cacao --}}
                        <div class="form-group row">
                            <?php echo Form::label(__('Avez-vous d’autres activités en dehors des cultures?'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::select('autreActivite', ['non' => 'Non', 'oui' => 'Oui'], null, ['class' => 'form-control autreActivite', 'required','readonly']); ?>

                            </div>
                        </div>
                        <div class="form-group row">
                            <?php echo Form::label('', null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8" id="listeactivites">
                                <table class="table table-striped table-bordered">
                                    <tbody id="activity_area">
                                        <?php

                                       if($infosproducteur->autresactivites)
        {  
        $i=0;
        $a=1;
        foreach($infosproducteur->autresactivites as $data) {
           ?>
                                        <tr>
                                            <td class="row">
                                                <div class="col-xs-12 col-sm-12 bg-success">
                                                    <badge class="btn  btn-outline--warning h-45 btn-sm text-white">@lang('Information Activité')
                                                        <?php echo $a; ?></badge>
                                                </div>
                                                <div class="col-xs-12 col-sm-12">
                                                    <div class="form-group">
                                                        {{ Form::label(__('Type D\'activité'), null, ['class' => 'control-label']) }}
                                                        <input type="text" name="typeactivite[]" readonly
                                                            placeholder="Riz, Maïs, Igname, Banane, ..."
                                                            id="typeactivite-<?php echo $a; ?>" class="form-control"
                                                            value="<?php echo $data->typeactivite; ?>">
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
           $a++;
            $i++;
        }
    }?>

                                    </tbody>
                                    <tfoot style="background: #e3e3e3;">
                                        <tr>
                                        <tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <hr class="panel-wide">

                        <div class="form-group row">
                            <?php echo Form::label(__('Avez-vous recours à une main d\'œuvre familiale  ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::select('mainOeuvreFamilial', ['non' => 'non', 'oui' => 'oui'], null, ['class' => 'form-control mainOeuvreFamilial', 'required','readonly']); ?>
                            </div>
                        </div>

                        <div class="form-group row" id="travailleurFamilial">
                            <?php echo Form::label(__('Combien de personnes (de la famille travaillent)'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::number('travailleurFamilial', null, ['placeholder' => 'Nombre', 'class' => 'form-control travailleurFamilial', 'min' => '0','readonly']); ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <?php echo Form::label(__('Combien de travailleurs (rémunéré) avez-vous ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::number('travailleurs', null, ['placeholder' => 'Nombre', 'class' => 'form-control', 'min' => '0', 'required','readonly']); ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <?php echo Form::label(__('Nombre de Travailleurs Permanents (plus de 12mois)'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::number('travailleurspermanents', null, ['placeholder' => 'Nombre', 'class' => 'form-control', 'min' => '0', 'required','readonly']); ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <?php echo Form::label(__('Nombre de Travailleurs temporaires'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::number('travailleurstemporaires', null, ['placeholder' => 'Nombre', 'class' => 'form-control', 'min' => '0','readonly']); ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <?php echo Form::label(__('Etes vous membre de société de travail ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::select('societeTravail', ['non' => 'Non', 'oui' => 'Oui'], null, ['class' => 'form-control societeTravail', 'required','readonly']); ?>
                            </div>
                        </div>
                        <div class="form-group row" id="societe">
                            <?php echo Form::label(__('Nombre de Personne'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::number('nombrePersonne', null, ['placeholder' => 'Nombre', 'id' => 'nombrePersonne', 'class' => 'form-control nombrePersonne', 'min' => '0','readonly']); ?>
                            </div>
                        </div>

                        <hr class="panel-wide">

                        <div class="form-group row">
                            <?php echo Form::label(__('As-tu un Compte Mobile Money ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::select('mobileMoney', ['non' => 'Non', 'oui' => 'Oui'], null, ['class' => 'form-control mobileMoney', 'required','disabled']); ?>

                            </div>
                        </div>
                        <div id="numeroCompteMM">
                            <div class="form-group row">
                                <?php echo Form::label('', null, ['class' => 'col-sm-4 control-label']); ?>
                                <div class="col-xs-12 col-sm-8" id="listeoperateurs">
                                    <table class="table table-striped table-bordered">
                                        <tbody id="compagny_area">
                                            <?php

                                       if($infosproducteur->mobiles)
        {  
        $i=0;
        $a=1;
        foreach($infosproducteur->mobiles as $data) {
           ?>
                                            <tr>
                                                <td class="row">
                                                    <div class="col-xs-12 col-sm-12 bg-success">
                                                        <badge class="btn  btn-outline--warning h-45 btn-sm text-white">
                                                            @lang('Information mobile monnaie')
                                                            <?php echo $a; ?></badge>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            {{ Form::label(__('Opérateur'), null, ['class' => 'control-label']) }}
                                                            <select class="form-control" readonly
                                                                id="operateurMM-<?php echo $a; ?>"
                                                                name="operateurMM[]">
                                                                <option value="MTN" <?php if ($data->operateur == 'MTN') {
                                                                    echo 'selected';
                                                                } ?>>MTN</option>
                                                                <option value="Orange" <?php if ($data->operateur == 'Orange') {
                                                                    echo 'selected';
                                                                } ?>>Orange</option>
                                                                <option value="Moov" <?php if ($data->operateur == 'Moov') {
                                                                    echo 'selected';
                                                                } ?>>Moov</option>
                                                                <option value="Wave" <?php if ($data->operateur == 'Wave') {
                                                                    echo 'selected';
                                                                } ?>>Wave</option>
                                                                <option value="Push" <?php if ($data->operateur == 'Push') {
                                                                    echo 'selected';
                                                                } ?>>Push</option>
                                                            </select>

                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            {{ Form::label(__('Numéro'), null, ['class' => 'control-label']) }}
                                                            <input type="text" name="numeros[]" readonly
                                                                placeholder="Numéro de compte mobile monnaie"
                                                                id="numeros-<?php echo $a; ?>" class="form-control "
                                                                value="<?php echo $data->numero; ?>">
                                                        </div>
                                                    </div>
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
                                                        <badge class="btn  btn-outline--warning h-45 btn-sm text-white">
                                                            @lang('Information Activité')
                                                        </badge>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-12">
                                                        <div class="form-group row">
                                                            {{ Form::label(__('Ajouter une activité'), null, ['class' => 'control-label']) }}
                                                            @foreach (old('typeactivite', []) as $typeactivite)
                                                                <input type="text" name="typeactivite[]"
                                                                    placeholder="Elevage, Commerce, Prestation de service, ..."
                                                                    id="typeactivite-0" class="form-control"
                                                                    value="{{ $typeactivite }}">
                                                            @endforeach
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
                                            <tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <?php echo Form::label(__('As-tu un compte bancaire (dans une banque) ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::select('compteBanque', ['non' => 'Non', 'oui' => 'Oui'], null, ['class' => 'form-control compteBanque', 'required','readonly']); ?>

                            </div>
                        </div>
                        <div class="form-group row" id="nomBanque">
                            <?php echo Form::label(__('Nom de la banque'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::select('nomBanque', ['Advans' => 'Advans', 'Coopec' => 'Coopec', 'Microcred' => 'Microcred', 'Autre' => 'Autre'], null, ['class' => 'form-control nomBanque','disabled']); ?>
                            </div>
                        </div>
                        <div class="form-group row" id="autreBanque">
                            <?php echo Form::label(__('Nom de la banque'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::text('autreBanque', null, ['placeholder' => 'Autre Banque', 'class' => 'form-control autreBanque','readonly']); ?>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('breadcrumb-plugins')
        <x-back route="{{ route('manager.traca.producteur.infos', encrypt($infosproducteur->producteur_id)) }}" />
    @endpush

    @push('script')
        
        <script type="text/javascript">
            $('#listecultures,#gardePapiersChamps,#numeroCompteMM,#listeactivites,#nomBanque,#autreBanque,#travailleurFamilial')
                .hide();
            $('.foretsjachere').change(function() {
                var foretsjachere = $('.foretsjachere').val();
                if (foretsjachere == 'oui') {
                    $('#superficie').show('slow');
                } else {
                    $('#superficie').hide('slow');
                }
            });
            if ($('.foretsjachere').val() == 'oui') {
                $('#superficie').show('slow');
            } else {
                $('#superficie').hide('slow');
            }
            $('.autresCultures').change(function() {
                var autresCultures = $('.autresCultures').val();
                if (autresCultures == 'oui') {
                    $('#listecultures').show('slow');
                } else {
                    $('#listecultures').hide('slow');
                    $('.listecultures').val('');
                }
            });
            if ($('.autresCultures').val() == 'oui') {
                $('#listecultures').show('slow');
            } else {
                $('#listecultures').hide('slow');
                $('.listecultures').val('');
            }
            $('.mainOeuvreFamilial').change(function() {
                var mainOeuvreFamilial = $('.mainOeuvreFamilial').val();
                if (mainOeuvreFamilial == 'oui') {
                    $('#travailleurFamilial').show('slow');
                    $('.travailleurFamilial').show('slow');
                } else {
                    $('#travailleurFamilial').hide('slow');
                    $('.travailleurFamilial').val('');
                }
            });
            $('.mainOeuvreNonFamilial').change(function() {
                var mainOeuvreNonFamilial = $('.mainOeuvreNonFamilial').val();
                if (mainOeuvreNonFamilial == 'oui') {
                    $('#travailleurNonFamilial').show('slow');
                    $('.travailleurNonFamilial').show('slow');
                } else {
                    $('#travailleurNonFamilial').hide('slow');
                    $('.travailleurNonFamilial').val('');
                }
            });

            $('.societeTravail').change(function() {
                var societeTravail = $('.societeTravail').val();
                if (societeTravail == 'oui') {
                    $('#societe').show('slow');
                    $('#nombrePersonne').prop('required', true);
                } else {
                    $('#societe').hide('slow');
                    $('#nombrePersonne').prop('required', false);
                    $('.nombrePersonne').val('');
                }
            });
            if ($('.societeTravail').val() == 'oui') {
                $('#societe').show('slow');
                $('#nombrePersonne').prop('required', true);
            } else {
                $('#societe').hide('slow');
                $('#nombrePersonne').prop('required', false);
                $('.nombrePersonne').val('');
            }

            $('.nomBanque').change(function() {
                var nomBanque = $('.nomBanque').val();
                if (nomBanque == 'Autre') {
                    $('#autreBanque').show('slow');
                    $('.autreBanque').show('slow');
                } else {
                    $('#autreBanque').hide('slow');
                    $('.autreBanque').val('');
                }
            });
            if ($('.nomBanque').val() == 'Autre') {
                $('#autreBanque').show('slow');
                $('.autreBanque').show('slow');
            } else {
                $('#autreBanque').hide('slow');
                $('.autreBanque').val('');
            }
            $('.compteBanque').change(function() {
                var compteBanque = $('.compteBanque').val();
                if (compteBanque == 'oui') {
                    $('#nomBanque').show('slow');
                    $('.nomBanque').show('slow');
                } else {
                    $('#nomBanque').hide('slow');
                    $('.nomBanque').val('');
                }
            });
            if ($('.compteBanque').val() == 'oui') {
                $('#nomBanque').show('slow');
                $('.nomBanque').show('slow');
            } else {
                $('#nomBanque').hide('slow');
                $('.nomBanque').val('');
            }
            $('.autreActivite').change(function() {
                var autreActivite = $('.autreActivite').val();
                if (autreActivite == 'oui') {
                    $('#listeactivites').show('slow');
                } else {
                    $('#listeactivites').hide('slow');
                    $('.listeactivites').val('');
                }
            });
            if ($('.autreActivite').val() == 'oui') {
                $('#listeactivites').show('slow');
            } else {
                $('#listeactivites').hide('slow');
                $('.listeactivites').val('');
            }

            $('.mobileMoney').change(function() {
                var mobileMoney = $('.mobileMoney').val();
                if (mobileMoney == 'oui') {
                    $('#numeroCompteMM').show('slow');
                    $('.numeroCompteMM').css('display', 'block');
                } else {
                    $('#numeroCompteMM').hide('slow');
                    $('.numeroCompteMM').val('');
                }
            });
            if ($('.mobileMoney').val() == 'oui') {
                $('#numeroCompteMM').show('slow');
                $('.numeroCompteMM').css('display', 'block');
            } else {
                $('#numeroCompteMM').hide('slow');
                $('.numeroCompteMM').val('');
            }
        </script>
    @endpush
