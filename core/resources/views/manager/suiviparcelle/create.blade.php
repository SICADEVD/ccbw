@extends('manager.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body">
                    {!! Form::open([
                        'route' => ['manager.suivi.parcelles.store'],
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

                    {{-- varieter arbre d'ombrage --}}

                    <div class="form-group row">
                        <label class="col-sm-4 control-label">@lang('Quelle variété d’arbre ombrage souhaiterais-tu avoir ?')</label>
                        <div class="col-xs-12 col-sm-8">
                            <select class="form-control select2-multi-select" name="arbre[]" id="arbre" multiple
                                required>
                                <option value="">@lang('Selectionner une option')</option>
                                @foreach ($arbres as $arbre)
                                    <option value="{{ $arbre->id }}" @selected(old('arbre'))>
                                        {{ $arbre->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- varieter arbre d'ombrage fin  --}}

                    <div class="form-group row">
                        <?php echo Form::label(__('Nombre de sauvageons observé dans la parcelle'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::number('nombreSauvageons', null, ['placeholder' => __('Nombre'), 'class' => 'form-control nombreSauvageons', 'min' => '0']); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <?php echo Form::label(__('As-tu bénéficié d’arbres agro-forestiers ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('arbresagroforestiers', ['non' => __('non'), 'oui' => __('oui')], null, ['class' => 'form-control arbresagroforestiers']); ?>
                        </div>
                    </div>

                    <div class="form-group row" id="recu">
                        <?php echo Form::label(__('Quand avez-vous recu ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('recuArbreAgroForestier', ['12 dernier mois' => __('12 dernier mois'), 'Il ya 2ans' => __(' Il ya 2ans'), 'Au dela de 02 ans' => 'Au dela de 02 ans'], null, ['class' => 'form-control recuArbreAgroForestier']); ?>
                        </div>
                    </div>
                    {{-- agroforestiersobtenus --}}

                    <div class="row mb-30" id="agroforestiersobtenus">
                        <div class="col-lg-12">
                            <div class="card border--primary mt-3">
                                <h5 class="card-header bg--primary text-white">@lang('Quels sont les arbres agro-forestiers obtenus ?')
                                    <button type="button" class="btn btn-sm btn-outline-light float-end addUserData"><i
                                            class="la la-fw la-plus"></i>@lang('Ajouter un arbre agro-forestier')
                                    </button>
                                </h5>
                                <div class="card-body">
                                    <div class="row" id="addedField">
                                        <?php $i = 0; ?>
                                        @if (old('items'))
                                            @foreach (old('items') as $item)
                                                <div class="row single-item gy-2">
                                                    <div class="col-md-3">
                                                        <select class="form-control selected_type"
                                                            name="items[{{ $loop->index }}][arbre]"
                                                            id='producteur-<?php echo $i; ?>'
                                                            onchange=getParcelle(<?php echo $i; ?>) required>
                                                            <option disabled selected value="">@lang('Abres d\'ombrages')
                                                            </option>
                                                            @foreach ($arbres as $arbre)
                                                                <option value="{{ $arbre->id }}"
                                                                    @selected($item['arbre'] == $arbre->id)>
                                                                    {{ __($arbre->nom) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="input-group mb-3">
                                                            <input type="number" class="form-control nombre"
                                                                value="{{ $item['nombre'] }}"
                                                                name="items[{{ $loop->index }}][nombre]" required>
                                                            <span class="input-group-text unit"><i
                                                                    class="las la-balance-scale"></i></span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-1">
                                                        <button class="btn btn--danger w-100 removeBtn w-100 h-45"
                                                            type="button">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- agroforestiersobtenus fin --}}

                    <div class="form-group row">
                        <?php echo Form::label(__('Activité de Taille dans la Parcelle'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('activiteTaille', ['Faible' => __('faible'), 'Moyen' => __('moyen'), 'Elevé' => __('elevé')], null, ['class' => 'form-control activiteTaille']); ?>
                        </div>
                    </div>


                    <div class="form-group row">
                        <?php echo Form::label(__('Activité d’Egourmandage dans la Parcelle'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('activiteEgourmandage', ['Faible' => __('faible'), 'Moyen' => __('moyen'), 'Elevé' => __('elevé')], null, ['class' => 'form-control activiteEgourmandage']); ?>
                        </div>
                    </div>


                    <div class="form-group row">
                        <?php echo Form::label(__('Activité de désherbage Manuel dans la Parcelle'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('activiteDesherbageManuel', ['Faible' => __('faible'), 'Moyen' => __('moyen'), 'Elevé' => __('elevé')], null, ['class' => 'form-control activiteDesherbageManuel']); ?>
                        </div>
                    </div>


                    <div class="form-group row">
                        <?php echo Form::label(__('Activité de Récolte Sanitaire dans la Parcelle'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('activiteRecolteSanitaire', ['Faible' => __('faible'), 'Moyen' => __('moyen'), 'Elevé' => __('elevé')], null, ['class' => 'form-control activiteRecolteSanitaire']); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <?php echo Form::label(__("Intrant NPK Utilisé l'année dernière"), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::label(__('Nombre de sacs utilisé de NPK'), null, ['class' => 'control-label']); ?>
                            <?php echo Form::hidden('intrantNPK', 'NPK', ['class' => 'form-control intrant']); ?>
                            <?php echo Form::number('nombresacsNPK', 0, ['placeholder' => 'Nombre de sacs utilisé...', 'class' => 'form-control', 'min' => '0']); ?>

                            <?php echo Form::label(__('Capacité'), null, ['class' => 'control-label']); ?>
                            <?php echo Form::number('capaciteNPK', 0, ['placeholder' => 'Capacité...', 'class' => 'form-control', 'min' => '0']); ?>
                            <?php echo Form::label(__('Type Conteneur'), null, ['class' => 'control-label']); ?>
                            <?php echo Form::text('conteneurNPK', null, ['placeholder' => 'Type conteneur...', 'class' => 'form-control']); ?>
                            <?php echo Form::label(__('Quantité'), null, ['class' => 'control-label']); ?>
                            <?php echo Form::select('qteNPK', ['Kg' => __('Kg'), 'L' => __('L')], null, ['class' => 'form-control']); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <?php echo Form::label(__("Intrant Dechets animaux Utilisé l'année dernière"), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::label(__('Nombre de sacs utilisé de dechets animaux'), null, ['class' => 'control-label']); ?>
                            <?php echo Form::hidden('intrantDechetsAnimaux', 'Dechets animaux', ['class' => 'form-control intrant']); ?>
                            <?php echo Form::number('nombreDechetsAnimaux', 0, ['placeholder' => 'Nombre de sacs utilisé...', 'class' => 'form-control', 'min' => '0']); ?>
                            <?php echo Form::label(__('Capacité'), null, ['class' => 'control-label']); ?>
                            <?php echo Form::number('capaciteDechetsAnimaux', 0, ['placeholder' => 'Capacité...', 'class' => 'form-control', 'min' => '0']); ?>
                            <?php echo Form::label(__('Type Conteneur'), null, ['class' => 'control-label']); ?>
                            <?php echo Form::text('conteneurDechetsAnimaux', null, ['placeholder' => 'Type conteneur...', 'class' => 'form-control']); ?>
                            <?php echo Form::label(__('Quantité'), null, ['class' => 'control-label']); ?>
                            <?php echo Form::select('qteDechetsAnimaux', ['Kg' => __('Kg'), 'L' => __('L')], null, ['class' => 'form-control']); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <?php echo Form::label(__("Intrant Fiente Utilisé l'année dernière"), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::label(__('Nombre de sacs utilisé de Fiente'), null, ['class' => 'control-label']); ?>
                            <?php echo Form::hidden('intrantFiente', 'Fiente', ['class' => 'form-control intrant']); ?>
                            <?php echo Form::number('nombresacsFiente', 0, ['placeholder' => 'Nombre de sacs utilisé...', 'class' => 'form-control', 'min' => '0']); ?>

                            <?php echo Form::label(__('Capacité'), null, ['class' => 'control-label']); ?>
                            <?php echo Form::number('capaciteFiente', 0, ['placeholder' => 'Capacité...', 'class' => 'form-control', 'min' => '0']); ?>

                            <?php echo Form::label(__('Type Conteneur'), null, ['class' => 'control-label']); ?>
                            <?php echo Form::text('conteneurFiente', null, ['placeholder' => 'Type conteneur...', 'class' => 'form-control']); ?>

                            <?php echo Form::label(__('Quantité'), null, ['class' => 'control-label']); ?>
                            <?php echo Form::select('qteFiente', ['Kg' => __('Kg'), 'L' => __('L')], null, ['class' => 'form-control']); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <?php echo Form::label(__("Intrant Composte Utilisé l'année dernière"), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::label(__('Nombre de sacs utilisé de Composte'), null, ['class' => 'control-label']); ?>
                            <?php echo Form::hidden('intrantComposte', 'Composte', ['class' => 'form-control intrant']); ?>
                            <?php echo Form::number('nombresacsComposte', 0, ['placeholder' => 'Nombre de sacs utilisé...', 'class' => 'form-control', 'min' => '0']); ?>
                            <?php echo Form::label(__('Capacité'), null, ['class' => 'control-label']); ?>
                            <?php echo Form::number('capaciteComposte', 0, ['placeholder' => 'Type conteneur...', 'class' => 'form-control', 'min' => '0']); ?>

                            <?php echo Form::label(__('Type Conteneur'), null, ['class' => 'control-label']); ?>
                            <?php echo Form::text('conteneurComposte', null, ['placeholder' => 'Type conteneur...', 'class' => 'form-control']); ?>
                            <?php echo Form::label(__('Quantité'), null, ['class' => 'control-label']); ?>
                            <?php echo Form::select('qteComposte', ['Kg' => __('Kg'), 'L' => __('L')], null, ['class' => 'form-control']); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <?php echo Form::label(__("Biofertilisant/Bio stimulant Utilisé l'année dernière"), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::label(__('Quantité utilisée'), null, ['class' => 'control-label']); ?>
                            <?php echo Form::number('qteBiofertilisant', 0, ['class' => 'form-control', 'min' => '0']); ?>


                            <?php echo Form::label(__('Unité'), null, ['class' => 'control-label']); ?>
                            <?php echo Form::select('uniteBioFertilisant', ['L' => __('L'), 'Kg' => __('Kg')], null, ['class' => 'form-control']); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <?php echo Form::label(__("Engrais organique préfabriqué Utilisé l'année dernière"), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::label(__('Quantité utilisée'), null, ['class' => 'control-label']); ?>
                            <?php echo Form::number('qteEngraisOrganique', 0, ['class' => 'form-control', 'min' => '0']); ?>


                            <?php echo Form::label(__('Unité'), null, ['class' => 'control-label']); ?>
                            <?php echo Form::select('uniteEngraisOrganique', ['L' => __('L'), 'Kg' => __('Kg')], null, ['class' => 'form-control']); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <?php echo Form::label('Frequence d\'utilisation du pesticide (combien de fois avez-vous appliqué sur la campagne)', null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::number('frequencePesticide', 0, ['placeholder' => 'fréquence...', 'class' => 'form-control', 'min' => '0']); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <?php echo Form::label(__('Pesticide utilisé l\'année derniere ( la campagne précédente)'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">

                            <select class="form-control select2-multi-select pesticideUtiliseAnne"
                                name="pesticideUtiliseAnne[]" multiple id="pesticideUtiliseAnne" required>
                                <option value="">@lang('Selectionner les options')</option>
                                <option value="Insecticide"
                                    {{ in_array('Insecticide', old('pesticideUtiliseAnne', [])) ? 'selected' : '' }}>
                                    Insecticide
                                </option>
                                <option value="Fongicide"
                                    {{ in_array('Fongicide', old('pesticideUtiliseAnne', [])) ? 'selected' : '' }}>
                                    Fongicide
                                </option>
                                <option value="Nematicide"
                                    {{ in_array('Nematicide', old('pesticideUtiliseAnne', [])) ? 'selected' : '' }}>
                                    Nematicide
                                </option>
                                <option value="Herbicide"
                                    {{ in_array('Herbicide', old('pesticideUtiliseAnne', [])) ? 'selected' : '' }}>
                                    Herbicide
                                </option>
                                <option value="Autre"
                                    {{ in_array('Autre', old('pesticideUtiliseAnne', [])) ? 'selected' : '' }}>
                                    Autre
                                </option>
                            </select>
                        </div>
                    </div>



                    <div class="form-group row" id="autrePesticides">
                        <?php echo Form::label(__('Autre Pesticide'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::text('autrePesticide', null, ['id' => 'autrePesticide', 'placeholder' => 'Autre...', 'class' => 'form-control autrePesticide']); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <?php echo Form::label(__('Présence de Pourriture Brune'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('presencePourritureBrune', ['Faible' => __('faible'), 'Moyen' => __('moyen'), 'Elevé ' => __('elevé'), 'inexistant' => __('Inexistant')], null, ['class' => 'form-control presencePourritureBrune']); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <?php echo Form::label(__('Présence de Swollen Shoot '), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('presenceSwollenShoot', ['Faible' => __('faible'), 'Moyen' => __('moyen'), 'Elevé ' => __('elevé'), 'inexistant' => __('Inexistant')], null, ['class' => 'form-control presenceSwollenShoot']); ?>
                        </div>
                    </div>

                    <hr class="panel-wide">

                    <div class="form-group row">
                        <?php echo Form::label(__('Présence d’insectes parasites ou ravageurs ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('presenceInsectesParasites', ['non' => __('non'), 'oui' => __('oui')], null, ['class' => 'form-control presenceInsectesParasites']); ?>
                        </div>
                    </div>

                    {{-- présence de autre ravageur  --}}
                    <div class="form-group row" id="presenceInsectesParasitesRavageurs">
                        <?php echo Form::label(__('Parasites ou ravageurs'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <table class="table table-striped table-bordered">
                                <tbody id="insectesParasites_area">

                                    <tr>
                                        <td class="row">
                                            <div class="col-xs-12 col-sm-12 bg-success">
                                                <badge class="btn  btn-outline--warning h-45 btn-sm">@lang('Insectes parasites ou ravageurs')
                                                </badge>
                                            </div>
                                            <div class="col-xs-12 col-sm-6">
                                                <div class="form-group row">
                                                    {{ Form::label(__('Nom'), null, ['class' => 'control-label']) }}
                                                    <select name="insectesParasites[]" id="insectesParasites-1" class="form-control">
                                                        <option value="Mirides">Mirides</option>
                                                        <option value="Fongicide">Fongicide</option>
                                                        <option value="Herbicide">Herbicide</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-6">
                                                <div class="form-group row">
                                                    {{ Form::label(__('Quantite'), null, ['class' => '']) }}
                                                    <?php echo Form::select('nombreinsectesParasites[]', ['Faible' => __('faible'), 'Moins' => __('Moins'),'Elevé' => __('elevé')], null, ['class' => 'form-control nombreinsectesParasites', 'id' => 'nombreinsectesParasites-1']); ?>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>

                                </tbody>
                                <tfoot style="background: #e3e3e3;">
                                    <tr>

                                        <td colspan="3">
                                            <button id="addRowinsectesParasites" type="button"
                                                class="btn btn-success btn-sm"><i class="fa fa-plus"></i></button>
                                        </td>
                                    <tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    {{-- présence de autre ravageur fin --}}

                    <div class="form-group row">
                        <?php echo Form::label(__('Présence de Bio-Agresseur'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('presenceBioAgresseur', ['Faible' => __('faible'), 'Moins' => __('moins'), 'Elevé' => __('elevé')], null, ['class' => 'form-control presenceBioAgresseur']); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <?php echo Form::label(__('Présence d’Insectes Ravageurs'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('presenceInsectesRavageurs', ['Faible' => __('faible'), 'Moins' => __('moins'), 'Elevé' => __('elevé')], null, ['class' => 'form-control presenceInsectesRavageurs']); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <?php echo Form::label(__('Présence de Fourmis Rouge'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('presenceFourmisRouge', ['Faible' => __('faible'), 'Moins' => __('moins'), 'Elevé' => __('elevé')], null, ['class' => 'form-control presenceFourmisRouge']); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <?php echo Form::label(__('Présence d’Araignée'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('presenceAraignee', ['Faible' => __('faible'), 'Moins' => __('moins'), 'Elevé' => __('elevé')], null, ['class' => 'form-control presenceAraignee']); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <?php echo Form::label(__('Présence de Ver de Terre'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('presenceVerTerre', ['Faible' => __('faible'), 'Moins' => __('moins'), 'Elevé' => __('elevé')], null, ['class' => 'form-control presenceVerTerre']); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <?php echo Form::label(__('Présence de Mente Religieuse'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('presenceMenteReligieuse', ['Faible' => __('faible'), 'Moins' => __('moins'), 'Elevé' => __('elevé')], null, ['class' => 'form-control presenceMenteReligieuse']); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <?php echo Form::label(__('Présence d’autres types d’insecte amis ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('presenceAutreTypeInsecteAmi', ['non' => __('non'), 'oui' => __('oui')], null, ['class' => 'form-control presenceAutreTypeInsecteAmi']); ?>
                        </div>
                    </div>
                    {{-- presenceAutreTypeInsecteAmi --}}
                    <div class="form-group row" id="autreInsectesAmis">

                        <?php echo Form::label(__('Autres insectes amis'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <table class="table table-striped table-bordered">
                                <tbody id="insectesAmis_area">

                                    <tr>
                                        <td class="row">
                                            <div class="col-xs-12 col-sm-12 bg-success">
                                                <badge class="btn  btn-outline--warning h-45 btn-sm">@lang('Insectes amis')
                                                </badge>
                                            </div>
                                            <div class="col-xs-12 col-sm-6">
                                                <div class="form-group row">
                                                    {{ Form::label(__('Nom'), null, ['class' => '']) }}
                                                    <input type="text" name="insectesAmis[]"
                                                        placeholder="Autre Insecte ami" id="insectesAmis-1"
                                                        class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-6">
                                                <div class="form-group row">
                                                    {{ Form::label(__('Quantite'), null, ['class' => '']) }}
                                                    <?php echo Form::select('nombreinsectesAmis[]', ['Faible' => __('faible'), 'Moins' => __('Moins'), 'Elevé'=>_('elevé')], null, ['class' => 'form-control nombreinsectesAmis', 'id' => 'nombreinsectesAmis-1']); ?>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>

                                </tbody>
                                <tfoot style="background: #e3e3e3;">
                                    <tr>

                                        <td colspan="3">
                                            <button id="addRowinsectesAmis" type="button"
                                                class="btn btn-success btn-sm"><i class="fa fa-plus"></i></button>
                                        </td>
                                    <tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    {{-- presenceAutreTypeInsecteAmi fin --}}

                    <hr class="panel-wide">
                    <div class="form-group row">
                        <?php echo Form::label('Les insecticides utilisés sur la parcelle', null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <table class="table table-striped table-bordered">
                                <tbody>

                                    <tr>
                                        <td class="row">
                                            <div class="col-xs-12 col-sm-12 bg-primary-800">
                                                <badge class="btn btn-primary btn-sm">@lang('Insecticide utilisé dans la parcelle')</badge>
                                            </div>
                                            <div class="col-xs-12 col-sm-6">
                                                <div class="form-group row">
                                                    {{ Form::label(__('Nom'), null, ['class' => '']) }}
                                                    <?php echo Form::text('nomInsecticide', null, ['class' => 'form-control']); ?>
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-6">
                                                <div class="form-group row">
                                                    {{ Form::label(__('Nombre de Boite'), null, ['class' => '']) }}
                                                    <?php echo Form::number('nombreInsecticide', null, ['class' => 'form-control', 'min' => '1']); ?>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="row">
                                            <div class="col-xs-12 col-sm-12 bg-primary-800">
                                                <badge class="btn btn-primary btn-sm">@lang('Fongicide Utilisé dans la parcelle')</badge>
                                            </div>
                                            <div class="col-xs-12 col-sm-4">
                                                <div class="form-group row">
                                                    {{ Form::label(__('Nom'), null, ['class' => '']) }}
                                                    <?php echo Form::text('nomFongicide', null, ['class' => 'form-control']); ?>
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-4">
                                                <div class="form-group row">
                                                    {{ Form::label(__('Quantité'), null, ['class' => '']) }}
                                                    <?php echo Form::number('qteFongicide', null, ['class' => 'form-control']); ?>
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-4">
                                                <div class="form-group row">
                                                    {{ Form::label(__('Unité'), null, ['class' => '']) }}
                                                    <?php echo Form::select('uniteFongicide', ['ml' => __('ml'), 'L' => __('L'), 'g' => __('g'), 'Kg' => __('Kg')], null, ['class' => 'form-control']); ?>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="row">
                                            <div class="col-xs-12 col-sm-12 bg-primary-800">
                                                <badge class="btn btn-primary btn-sm">@lang('Herbicide Utilisé dans la parcelle')</badge>
                                            </div>
                                            <div class="col-xs-12 col-sm-4">
                                                <div class="form-group row">
                                                    {{ Form::label(__('Nom'), null, ['class' => '']) }}
                                                    <?php echo Form::text('nomHerbicide', null, ['class' => 'form-control']); ?>
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-4">
                                                <div class="form-group row">
                                                    {{ Form::label(__('Quantité'), null, ['class' => '']) }}
                                                    <?php echo Form::number('qteHerbicide', null, ['class' => 'form-control']); ?>
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-4">
                                                <div class="form-group row">
                                                    {{ Form::label(__('Unité'), null, ['class' => '']) }}
                                                    <?php echo Form::select('uniteHerbicide', ['ml' => __('ml'), 'L' => __('L'), 'g' => __('g'), 'Kg' => __('Kg')], null, ['class' => 'form-control']); ?>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>

                                </tbody>

                            </table>
                        </div>
                    </div>
                    <div class="form-group row">
                        {{ Form::label(__("Nombre de désherbage manuel dans l'année"), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::number('nombreDesherbage', null, ['class' => 'form-control', 'min' => '1']); ?>
                        </div>
                    </div>
                    <hr class="panel-wide">

                    <div class="form-group row">
                        <?php echo Form::label(__('Présence de Fourmis Rouge'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('presenceFourmisRouge', ['Faible' => __('faible'), 'Moins' => __('moins'), 'Elevé' => __('elevé')], null, ['class' => 'form-control']); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <?php echo Form::label(__('Présence d’Araignée'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('presenceAraignee', ['Faible' => __('faible'), 'Moins' => __('moins'), 'Elevé' => __('elevé')], null, ['class' => 'form-control']); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <?php echo Form::label(__('Présence de Ver de Terre'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('presenceVerDeTerre', ['Faible' => __('faible'), 'Moins' => __('moins'), 'Elevé' => __('elevé')], null, ['class' => 'form-control']); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <?php echo Form::label(__('Présence de Mente  Religieuse'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('presenceMenteReligieuse', ['Faible' => __('faible'), 'Moins' => __('moins'), 'Elevé' => __('elevé')], null, ['class' => 'form-control']); ?>
                        </div>
                    </div>
                    <hr class="panel-wide">
                    <div class="form-group row">
                        {{ Form::label(__('Citez les animaux que vous rencontrez dans les champs'), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                            <table class="table table-striped table-bordered">
                                <tbody id="animauxRencontres_area">

                                    <tr>
                                        <td class="row">
                                            <div class="col-xs-12 col-sm-12 bg-success">
                                                <badge class="btn  btn-outline--warning h-45 btn-sm">@lang('Animal')
                                                </badge>
                                            </div>
                                            <div class="col-xs-12 col-sm-12">
                                                <div class="form-group row">
                                                    {{ Form::label(__('Nom'), null, ['class' => '']) }}
                                                    <input type="text" name="animauxRencontres[]" placeholder="..."
                                                        id="animauxRencontres-1" class="form-control">
                                                </div>
                                            </div>

                                        </td>
                                    </tr>

                                </tbody>
                                <tfoot style="background: #e3e3e3;">
                                    <tr>

                                        <td colspan="3">
                                            <button id="addRowanimauxRencontres" type="button"
                                                class="btn btn-success btn-sm"><i class="fa fa-plus"></i></button>
                                        </td>
                                    <tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <hr class="panel-wide">

                    <div class="form-group row">
                        {{ Form::label(__('Date de la visite'), null, ['class' => 'col-sm-4 control-label required']) }}
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::date('dateVisite', null, ['class' => 'form-control dateactivite required', 'required' => 'required']); ?>
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
    <x-back route="{{ route('manager.suivi.parcelles.index') }}" />
@endpush
@push('style')
    <style type="text/css">
        input:not([type="radio"]),
        textarea {
            padding: 0px;
        }
    </style>
@endpush
@push('script')
    <script type="text/javascript">
        $(document).ready(function() {

            var agroforestiersCount = $("#agroforestiers_area tr").length + 1;
            $(document).on('click', '#addRowagroforestiers', function() {

                //---> Start create table tr
                var html_table = '<tr>';
                html_table +=
                    '<td class="row"><div class="col-xs-12 col-sm-12 bg-success"><badge class="btn  btn-outline--warning h-45 btn-sm">Arbre agro-forestier ' +
                    agroforestiersCount +
                    '</badge></div><div class="col-xs-12 col-sm-6"><div class="form-group"><label for="agroforestiers" class="">Type</label><input placeholder="Type arbre..." class="form-control" id="agroforestiers-' +
                    agroforestiersCount +
                    '" name="agroforestiers[]" type="text"></div></div><div class="col-xs-12 col-sm-6"><div class="form-group"><label for="nombreagroforestiers" class="">Nombre</label><input type="number" name="nombreagroforestiers[]" placeholder="Nombre d\'arbre" id="nombreagroforestiers-' +
                    agroforestiersCount +
                    '" class="form-control " min="1" value=""></div></div><div class="col-xs-12 col-sm-8"><button type="button" id="' +
                    agroforestiersCount +
                    '" class="removeRowagroforestiers btn btn-danger btn-sm"><i class="fa fa-minus"></i></button></div></td>';

                html_table += '</tr>';
                //---> End create table tr

                agroforestiersCount = parseInt(agroforestiersCount) + 1;
                $('#agroforestiers_area').append(html_table);

            });

            $(document).on('click', '.removeRowagroforestiers', function() {
                var row_id = $(this).attr('id');
                if (row_id == $("#agroforestiers_area tr").length) {
                    $(this).parents('tr').remove();
                    agroforestiersCount = parseInt(agroforestiersCount) - 1;
                }
            });

            //insectes amis
            var insectesAmisCount = $("#insectesAmis_area tr").length + 1;
            $(document).on('click', '#addRowinsectesAmis', function() {

                //---> Start create table tr
                var html_table = '<tr>';
                html_table +=
                    '<td class="row"><div class="col-xs-12 col-sm-12 bg-success"><badge class="btn  btn-outline--warning h-45 btn-sm">Insectes amis ' +
                    insectesAmisCount +
                    '</badge></div><div class="col-xs-12 col-sm-6"><div class="form-group"><label for="insectesAmis" class="">Nom</label><input placeholder="Insecte amis..." class="form-control" id="insectesAmis-' +
                    insectesAmisCount +
                    '" name="insectesAmis[]" type="text"></div></div><div class="col-xs-12 col-sm-6"><div class="form-group"><label for="nombreinsectesAmis" class="">Quantite</label><select name="nombreinsectesAmis[]" class="form-control nombreinsectesParasites" d="nombreinsectesAmis-' +
                    insectesAmisCount +
                    '" ><option value="Faible">faible</option><option value="Moins">moins</option><option value="Elevé">elevé</option></select></div></div><div class="col-xs-12 col-sm-8"><button type="button" id="' +
                    insectesAmisCount +
                    '" class="removeRowinsectesAmis btn btn-danger btn-sm"><i class="fa fa-minus"></i></button></div></td>';

                html_table += '</tr>';
                //---> End create table tr

                insectesAmisCount = parseInt(insectesAmisCount) + 1;
                $('#insectesAmis_area').append(html_table);

            });

            $(document).on('click', '.removeRowinsectesAmis', function() {
                var row_id = $(this).attr('id');
                if (row_id == $("#insectesAmis_area tr").length) {
                    $(this).parents('tr').remove();
                    insectesAmisCount = parseInt(insectesAmisCount) - 1;
                }
            });
            //fin insectes amis

            var insectesParasitesCount = $("#insectesParasites_area tr").length + 1;
            $(document).on('click', '#addRowinsectesParasites', function() {

                //---> Start create table tr'Faible' => __('faible'), 'Moins' => __('Moins'),'Elevé' => __('elevé')

                var html_table = '<tr>';
                html_table +=
                    '<td class="row"><div class="col-xs-12 col-sm-12 bg-success"><badge class="btn  btn-outline--warning h-45 btn-sm">Insectes parasites ou ravageurs ' +
                    insectesParasitesCount +
                    '</badge></div><div class="col-xs-12 col-sm-6"><div class="form-group"><label for="insectesParasites" class="">Nom</label><select class="form-control" id="insectesParasites-' +
                    insectesParasitesCount +
                    '" name="insectesParasites[]"><option value="Mirides">Mirides</option> <option value="Fongicide">Fongicide</option> <option value="Herbicide">Herbicide</option></select></div></div><div class="col-xs-12 col-sm-6"><div class="form-group"><label for="nombreinsectesParasites" class="">Quantite</label><select name="nombreinsectesParasites[]" class="form-control nombreinsectesParasites" d="nombreinsectesParasites-' +
                    insectesParasitesCount +
                    '" ><option value="Faible">Faible</option><option value="Moins">Moins</option><option value="Elevé">elevé</option></select></div></div><div class="col-xs-12 col-sm-8"><button type="button" id="' +
                    insectesParasitesCount +
                    '" class="removeRowinsectesParasites btn btn-danger btn-sm"><i class="fa fa-minus"></i></button></div></td>';

                html_table += '</tr>';
                //---> End create table tr

                insectesParasitesCount = parseInt(insectesParasitesCount) + 1;
                $('#insectesParasites_area').append(html_table);

            });

            $(document).on('click', '.removeRowinsectesParasites', function() {
                var row_id = $(this).attr('id');
                if (row_id == $("#insectesParasites_area tr").length) {
                    $(this).parents('tr').remove();
                    insectesParasitesCount = parseInt(insectesParasitesCount) - 1;
                }
            });

            var animauxRencontresCount = $("#animauxRencontres_area tr").length + 1;
            $(document).on('click', '#addRowanimauxRencontres', function() {

                //---> Start create table tr
                var html_table = '<tr>';
                html_table +=
                    '<td class="row"><div class="col-xs-12 col-sm-12 bg-success"><badge class="btn  btn-outline--warning h-45 btn-sm">Animal ' +
                    animauxRencontresCount +
                    '</badge></div><div class="col-xs-12 col-sm-12"><div class="form-group"><label for="animauxRencontres" class="">Animal</label><input placeholder="Nom animal..." class="form-control" id="animauxRencontres-' +
                    animauxRencontresCount +
                    '" name="animauxRencontres[]" type="text"></div></div><div class="col-xs-12 col-sm-12"><button type="button" id="' +
                    animauxRencontresCount +
                    '" class="removeRowanimauxRencontres btn btn-danger btn-sm"><i class="fa fa-minus"></i></button></div></td>';

                html_table += '</tr>';
                //---> End create table tr

                animauxRencontresCount = parseInt(animauxRencontresCount) + 1;
                $('#animauxRencontres_area').append(html_table);

            });

            $(document).on('click', '.removeRowanimauxRencontres', function() {
                var row_id = $(this).attr('id');
                if (row_id == $("#animauxRencontres_area tr").length) {
                    $(this).parents('tr').remove();
                    animauxRencontresCount = parseInt(animauxRencontresCount) - 1;
                }
            });

            $('#courseaux,#agroforestiersobtenus,#presenceInsectesParasitesRavageurs,#recu,#autrePesticides,#autreInsectesAmis,#autrePresenceInsectesParasitesRavageurs')
                .hide();

            $('.arbresagroforestiers').change(function() {
                var arbresagroforestiers = $('.arbresagroforestiers').val();
                if (arbresagroforestiers == 'oui') {
                    $('#agroforestiersobtenus').show('slow');
                    $('#recu').show('slow');
                    $('.recuArbreAgroForestier').show('slow');
                    $('.recuArbreAgroForestier').attr('required', true);
                } else {
                    $('#agroforestiersobtenus').hide('slow');
                    $('#recu').hide('slow');
                    $('.recuArbreAgroForestier').hide('slow');
                    $('.recuArbreAgroForestier').attr('required', false);
                    $('.recuArbreAgroForestier').val('');
                }
            });

            $('.pesticideUtiliseAnne').change(function() {
                var pesticideUtiliseAnne = $('.pesticideUtiliseAnne').find(":selected").map((key, item) => {
                    return item.textContent.trim();
                }).get();
                if (pesticideUtiliseAnne.includes("Autre")) {
                    $('#autrePesticides').show('slow');
                    $('#autrePesticide').prop('required', true);
                    $('.autrePesticide').show('slow');

                } else {

                    $('#autrePesticides').hide('slow');
                    $('#autrePesticide').prop('required', false);
                    $('.autrePesticide').hide('slow');
                    $('.autrePesticide').val('');

                }
            });
            $('.presenceInsectesParasites').change(function() {
                var presenceInsectesParasites = $('.presenceInsectesParasites').val();
                if (presenceInsectesParasites == 'oui') {
                    $('#presenceInsectesParasitesRavageurs').show('slow');
                    $('.presenceInsectesParasitesRavageur').show('slow');
                } else {
                    $('#presenceInsectesParasitesRavageurs').hide('slow');
                    $('.presenceInsectesParasitesRavageur').val('');
                    $('#autrePresenceInsectesParasitesRavageurs').hide('slow');
                }
            });

            $('.presenceInsectesParasitesRavageur').change(function() {
                var presenceInsectesParasitesRavageur = $('.presenceInsectesParasitesRavageur').val();
                if (presenceInsectesParasitesRavageur == 'Autre') {
                    $('#autrePresenceInsectesParasitesRavageurs').show('slow');

                } else {
                    $('#autrePresenceInsectesParasitesRavageurs').hide('slow');
                    $('#autrePresenceInsectesParasitesRavageurs input').val('');
                }
            });
            $('.presenceAutreTypeInsecteAmi').change(function() {
                var presenceAutreTypeInsecteAmi = $('.presenceAutreTypeInsecteAmi').val();
                if (presenceAutreTypeInsecteAmi == 'oui') {

                    $('#autreInsectesAmis').show('slow');

                } else {
                    $('#autreInsectesAmis').hide('slow');
                    $('#autreInsectesAmis input').val('');
                }
            });

            $('#localite').chained("#section")
            $("#producteur").chained("#localite");
            $("#parcelle").chained("#producteur");
        });
    </script>

    <script>
        "use strict";

        (function($) {


            $('.addUserData').on('click', function() {

                let count = $("#addedField select").length;
                let length = $("#addedField").find('.single-item').length;

                let html = `
            <div class="row single-item gy-2">
                <div class="col-md-3">
                    <select class="form-control selected_type" name="items[${length}][arbre]" required id='arbre-${length}')>
                        <option disabled selected value="">@lang('Arbres agro-forestiers')</option>
                        @foreach ($arbres as $arbre)
                            <option value="{{ $arbre->id }}"  >{{ __($arbre->nom) }} </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3">
                    <div class="input-group mb-3">
                        <input type="number" class="form-control quantity" placeholder="@lang('Nombre')"  name="items[${length}][nombre]"  required>
                        <span class="input-group-text unit"><i class="las la-balance-scale"></i></span>
                    </div>
                </div>
                <div class="col-md-1">
                    <button class="btn btn--danger w-100 removeBtn w-100 h-45" type="button">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <br><hr class="panel-wide">
            </div>`;
                $('#addedField').append(html)
            });

            $('#addedField').on('change', '.selected_type', function(e) {
                let unit = $(this).find('option:selected').data('unit');
                let parent = $(this).closest('.single-item');
                $(parent).find('.quantity').attr('disabled', false);
                $(parent).find('.unit').html(`${unit || '<i class="las la-balance-scale"></i>'}`);
            });

            $('#addedField').on('click', '.removeBtn', function(e) {
                let length = $("#addedField").find('.single-item').length;
                if (length <= 1) {
                    notify('warning', "@lang('Au moins un élément est requis')");
                } else {
                    $(this).closest('.single-item').remove();
                }
            });

        })(jQuery);
    </script>
@endpush
