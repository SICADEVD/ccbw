@extends('manager.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body">
                    {!! Form::model($suiviparcelle, [
                        'method' => 'POST',
                        'route' => ['manager.suivi.parcelles.store', $suiviparcelle->id],
                        'class' => 'form-horizontal',
                        'id' => 'flocal',
                        'enctype' => 'multipart/form-data',
                    ]) !!}
                    <input type="hidden" name="id" value="{{ $suiviparcelle->id }}">

                    <div class="fieldset-like">
                        <legend class="legend-center">
                            <h5 class="font-weight-bold text-decoration-underline">Informations sur la parcelle</h5>
                        </legend>

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
                                        <option value="{{ $section->id }}" @selected($section->id == $suiviparcelle->parcelle->producteur->localite->section->id)>
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
                                        <option value="{{ $localite->id }}" data-chained="{{ $localite->section->id }}"
                                            @selected($localite->id == $suiviparcelle->parcelle->producteur->localite->id)>
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
                                        <option value="{{ $producteur->id }}" data-chained="{{ $producteur->localite->id }}"
                                            @selected($producteur->id == $suiviparcelle->parcelle->producteur->id)>
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
                                        <option value="{{ $parcelle->id }}" data-chained="{{ $parcelle->producteur->id }}"
                                            @selected($parcelle->id == $suiviparcelle->parcelle->id)>
                                            {{ __('Parcelle') }} {{ $parcelle->codeParc }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 control-label">@lang('Quelle variété d’arbre ombrage souhaiterais-tu avoir
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ?')</label>
                            <div class="col-xs-12 col-sm-8">
                                <select class="form-control select2-multi-select" name="arbre[]" id="arbre" multiple
                                    required>
                                    <option value="">@lang('Selectionner une option')</option>
                                    @foreach ($arbres as $arbre)
                                        <option value="{{ $arbre->id }}" @selected(in_array($arbre->id, $arbreOmbrages))>
                                            {{ $arbre->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <?php echo Form::label(__('Nombre de sauvageons observé dans la parcelle'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::number('nombreSauvageons', null, ['placeholder' => __('Nombre'), 'class' => 'form-control nombreSauvageons', 'min' => '0']); ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <?php echo Form::label(__('As-tu bénéficié d’arbres agro-forestiers ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::select('arbresagroforestiers', ['' => 'Selectionner une option', 'non' => __('non'), 'oui' => __('oui')], null, ['class' => 'form-control arbresagroforestiers', 'required']); ?>
                            </div>
                        </div>

                        <div class="form-group row" id="recu">
                            <?php echo Form::label(__('Quand avez-vous recu ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::select('recuArbreAgroForestier', ['12 dernier mois' => __('12 dernier mois'), 'Il ya 2ans' => __(' Il ya 2ans'), 'Au dela de 02 ans' => 'Au dela de 02 ans'], null, ['class' => 'form-control']); ?>
                            </div>
                        </div>
                        <div class="row mb-30" id="agroforestiersobtenus">
                            <div class="col-lg-12">
                                <div class="card border--primary mt-3">
                                    <h5 class="card-header bg--primary text-white">@lang('Quels sont les arbres agro-forestiers
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            obtenus ?')
                                        <button type="button" class="btn btn-sm btn-outline-light float-end addUserData"><i
                                                class="la la-fw la-plus"></i>@lang('Ajouter un arbre agro-forestier')
                                        </button>
                                    </h5>
                                    <div class="card-body">
                                        <div class="row" id="addedField">
                                            <?php $i = 0; ?>
                                            @foreach ($arbreAgroForestiers as $item)
                                                <div class="row single-item gy-2">
                                                    <div class="col-md-3">
                                                        <select class="form-control selected_type"
                                                            name="items[{{ $loop->index }}][arbre]"
                                                            id='producteur-<?php echo $i; ?>'
                                                            onchange=getParcelle(<?php echo $i;
                                                            ?>) required>
                                                            <option disabled selected value="">@lang('Abres d\'ombrages')
                                                            </option>
                                                            @foreach ($arbres as $arbre)
                                                                <option value="{{ $arbre->id }}"
                                                                    @selected($item->agroespeceabre_id == $arbre->id)>
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
                                                <?php $i++; ?>
                                            @endforeach

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="fieldset-like">
                        <legend class="legend-center">
                            <h5 class="font-weight-bold text-decoration-underline">Informations sur la campagne précédente
                            </h5>
                        </legend>
                        <div class="form-group row">
                            <?php echo Form::label(__('Quels sont les Pesticides utilisés l’année dernière'), null, ['class' => 'col-sm-12 control-label pt-3']); ?>
                            <div class="col-xs-12 col-sm-12">
                                <table class="table table-striped table-bordered">
                                    <tbody id="pesticidesAnneDerniere_area">
                                        @if ($pesticidesAnneDerniere)
                                            @foreach ($pesticidesAnneDerniere as $index => $pesticide)
                                                <tr>
                                                    <td class="row">
                                                        <div class="col-xs-12 col-sm-12 bg-success">
                                                            <badge class="btn  btn-outline--warning h-45 btn-sm">
                                                                @lang('Pesticide') {{ $index + 1 }}
                                                            </badge>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-4">
                                                            <div class="form-group row">
                                                                <label class="control-label">Nom</label>
                                                                <select
                                                                    name="pesticidesAnneDerniere[{{ $index }}][nom]"
                                                                    id="pesticidesAnneDerniere-{{ $index }}"
                                                                    class="form-control">
                                                                    <option value="">Selectionner une option</option>
                                                                    <option value="Herbicides"
                                                                        {{ $pesticide['nom'] == 'Herbicides' ? 'selected' : '' }}>
                                                                        Herbicides</option>
                                                                    <option value="Fongicides"
                                                                        {{ $pesticide['nom'] == 'Fongicides' ? 'selected' : '' }}>
                                                                        Fongicides</option>
                                                                    <option value="Insecticides"
                                                                        {{ $pesticide['nom'] == 'Insecticides' ? 'selected' : '' }}>
                                                                        Insecticides</option>
                                                                    <option value="Nematicides"
                                                                        {{ $pesticide['nom'] == 'Nematicides' ? 'selected' : '' }}>
                                                                        Nematicides</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-2">
                                                            <div class="form-group row">
                                                                <label class="control-label">Unité</label>
                                                                <select class="form-control unite"
                                                                    name="pesticidesAnneDerniere[{{ $index }}][unite]"
                                                                    id="unite-{{ $index }}">
                                                                    <option value="">Selectionner une option</option>
                                                                    <option value="Kg"
                                                                        {{ $pesticide['unite'] == 'Kg' ? 'selected' : '' }}>
                                                                        Kg</option>
                                                                    <option value="L"
                                                                        {{ $pesticide['unite'] == 'L' ? 'selected' : '' }}>
                                                                        L</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-2">
                                                            <div class="form-group row">
                                                                <label class="control-label">Quantité</label>
                                                                <input type="number"
                                                                    name="pesticidesAnneDerniere[{{ $index }}][quantite]"
                                                                    id="quantite-{{ $index }}"
                                                                    class="form-control quantite" placeholder="Quantité"
                                                                    value="{{ $pesticide['quantite'] }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-2">
                                                            <div class="form-group row">
                                                                {{ Form::label(__('Type de contenant'), null, ['class' => '']) }}
                                                                <select class="form-control contenant"
                                                                    name="pesticidesAnneDerniere[{{ $index }}][contenant]"
                                                                    id="contenant-{{ $index }}">
                                                                    <option value="">Selectionner une option</option>
                                                                    <option value="Sac"
                                                                        {{ $pesticide['contenant'] == 'Sac' ? 'selected' : '' }}>
                                                                        Sac</option>
                                                                    <option value="Sachet"
                                                                        {{ $pesticide['contenant'] == 'Sachet' ? 'selected' : '' }}>
                                                                        Sachet</option>
                                                                    <option value="Boîte"
                                                                        {{ $pesticide['contenant'] == 'Boîte' ? 'selected' : '' }}>
                                                                        Boîte</option>
                                                                    <option value="Pot"
                                                                        {{ $pesticide['contenant'] == 'Pot' ? 'selected' : '' }}>
                                                                        Pot</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-2">
                                                            <div class="form-group row">
                                                                {{ Form::label(__('Fréquence'), null, ['class' => '']) }}
                                                                <input type="number"
                                                                    name="pesticidesAnneDerniere[{{ $index }}][frequence]"
                                                                    id="frequence-{{ $index }}"
                                                                    class="form-control frequence" placeholder="Fréquence"
                                                                    value="{{ $pesticide['frequence'] }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-8">
                                                            <button type="button" id="{{ $index }}"
                                                                class="removeRowPesticidesAnneDerniere btn btn-danger btn-sm"><i
                                                                    class="fa fa-minus"></i></button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td class="row">
                                                    <div class="col-xs-12 col-sm-12 bg-success">
                                                        <badge class="btn  btn-outline--warning h-45 btn-sm">
                                                            @lang('Pesticide')
                                                        </badge>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-4">
                                                        <div class="form-group row">
                                                            <label class="control-label">Nom</label>
                                                            <select name="pesticidesAnneDerniere[0][nom]"
                                                                id="pesticidesAnneDerniere-1" class="form-control">
                                                                <option value="">Selectionner une option</option>
                                                                <option value="Herbicides">Herbicides</option>
                                                                <option value="Fongicides">Fongicides</option>
                                                                <option value="Nematicide">Nematicide</option>
                                                                <option value="Insecticides">Insecticides</option>
                                                                <option value="Acaricides">Acaricides</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12 col-sm-2">
                                                        <div class="form-group row">
                                                            <label class="control-label">Unité</label>
                                                            <select class="form-control unite"
                                                                name="pesticidesAnneDerniere[0][unite]" id="unite-1">
                                                                <option value="">Selectionner une option</option>
                                                                <option value="Kg">Kg</option>
                                                                <option value="L">L</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12 col-sm-2">
                                                        <div class="form-group row">
                                                            <label class="control-label">Quantité</label>

                                                            <input type="number"
                                                                name="pesticidesAnneDerniere[0][quantite]" id="quantite-1"
                                                                class="form-control quantite" placeholder="Quantité">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-2">
                                                        <div class="form-group row">
                                                            {{ Form::label(__('Type de contenant'), null, ['class' => '']) }}
                                                            <select class="form-control contenant"
                                                                name="pesticidesAnneDerniere[0][contenant]"
                                                                id="contenant-1">
                                                                <option value="">Selectionner une option</option>
                                                                <option value="Sac">Sac</option>
                                                                <option value="Sachet">Sachet</option>
                                                                <option value="Boîte">Boîte</option>
                                                                <option value="Pot">Pot</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-2">
                                                        <div class="form-group row">
                                                            {{ Form::label(__('Fréquence'), null, ['class' => '']) }}
                                                            <input type="number"
                                                                name="pesticidesAnneDerniere[0][frequence]"
                                                                id="frequence-1" class="form-control frequence"
                                                                placeholder="Fréquence">
                                                        </div>
                                                    </div>

                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                    <tfoot style="background: #e3e3e3;">
                                        <tr>

                                            <td colspan="3">
                                                <button id="addRowPesticidesAnneDerniere" type="button"
                                                    class="btn btn-success btn-sm"><i class="fa fa-plus"></i></button>
                                            </td>
                                        <tr>
                                    </tfoot>
                                </table>
                            </div>

                        </div>
                        <div class="form-group row">
                            <?php echo Form::label(__('Quels sont les Intrants (fertilisant, biofertilisant) utilisés l’année dernière'), null, ['class' => 'col-sm-12 control-label pt-3']); ?>

                            {{-- NPK   Compost   Biofertilisant/Bio stimulant Engrais organique préfabriqué --}}
                            <div class="col-xs-12 col-sm-12">
                                <table class="table table-striped table-bordered">
                                    <tbody id="intrantsAnneDerniere_area">
                                        <tr>
                                            <td class="row">
                                                <div class="col-xs-12 col-sm-12 bg-success">
                                                    <badge class="btn  btn-outline--warning h-45 btn-sm">
                                                        @lang('Intrant')
                                                    </badge>
                                                </div>
                                                <div class="col-xs-12 col-sm-4">
                                                    <div class="form-group row">
                                                        <label class="control-label">Nom</label>
                                                        <select name="intrantsAnneDerniere[0][nom]"
                                                            id="intrantsAnneDerniere-1" class="form-control">
                                                            <option value="">Selectionner une option</option>
                                                            <option value="Dechets animaux">Dechets animaux
                                                            </option>
                                                            <option value="NPK">NPK</option>
                                                            <option value="Compost">Compost</option>
                                                            <option value="Biofertilisant/Bio stimulant">
                                                                Biofertilisant/Bio stimulant</option>
                                                            <option value="Engrais organique préfabriqué">Engrais
                                                                organique préfabriqué</option>
                                                            <option value="Engrais organique préfabriqué">Engrais
                                                                foliaire</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-2">
                                                    <div class="form-group row">
                                                        <label class="control-label">Unité</label>
                                                        <select class="form-control unite"
                                                            name="intrantsAnneDerniere[0][unite]" id="unite-1">
                                                            <option value="">Selectionner une option</option>
                                                            <option value="Kg">Kg</option>
                                                            <option value="L">L</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-2">
                                                    <div class="form-group row">
                                                        <label class="control-label">Quantité</label>

                                                        <input type="number" name="intrantsAnneDerniere[0][quantite]"
                                                            id="quantite-1" class="form-control quantite"
                                                            placeholder="Quantité">
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-2">
                                                    <div class="form-group row">
                                                        {{ Form::label(__('Type de contenant'), null, ['class' => '']) }}
                                                        <select class="form-control contenant"
                                                            name="intrantsAnneDerniere[0][contenant]" id="contenant-1">
                                                            <option value="">Selectionner une option</option>
                                                            <option value="Sac">Sac</option>
                                                            <option value="Sachet">Sachet</option>
                                                            <option value="Boîte">Boîte</option>
                                                            <option value="Pot">Pot</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-2">
                                                    <div class="form-group row">
                                                        {{ Form::label(__('Fréquence'), null, ['class' => '']) }}
                                                        <input type="number" name="intrantsAnneDerniere[0][frequence]"
                                                            id="frequence-1" class="form-control frequence"
                                                            placeholder="Fréquence">
                                                    </div>
                                                </div>

                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot style="background: #e3e3e3;">
                                        <tr>

                                            <td colspan="3">
                                                <button id="addRowIntrantsAnneDerniere" type="button"
                                                    class="btn btn-success btn-sm"><i class="fa fa-plus"></i></button>
                                            </td>
                                        <tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="fieldset-like">
                        <legend class="legend-center">
                            <h5 class="font-weight-bold text-decoration-underline">Technique culturale</h5>
                        </legend>
                        <div class="form-group row">
                            <?php echo Form::label(__('Activité de Taille dans la Parcelle'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::select('activiteTaille', ['Faible' => __('Faible'), 'Moyen' => __('Moyen'), 'Elevé' => __('Elevé')], null, ['class' => 'form-control activiteTaille']); ?>
                            </div>
                        </div>


                        <div class="form-group row">
                            <?php echo Form::label(__('Activité d’Egourmandage dans la Parcelle'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::select('activiteEgourmandage', ['Faible' => __('Faible'), 'Moyen' => __('Moyen'), 'Elevé' => __('Elevé')], null, ['class' => 'form-control activiteEgourmandage']); ?>
                            </div>
                        </div>


                        <div class="form-group row">
                            <?php echo Form::label(__('Activité de désherbage Manuel dans la Parcelle'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::select('activiteDesherbageManuel', ['Faible' => __('Faible'), 'Moyen' => __('Moyen'), 'Elevé' => __('Elevé')], null, ['class' => 'form-control activiteDesherbageManuel']); ?>
                            </div>
                        </div>


                        <div class="form-group row">
                            <?php echo Form::label(__('Activité de Récolte Sanitaire dans la Parcelle'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::select('activiteRecolteSanitaire', ['Faible' => __('Faible'), 'Moyen' => __('Moyen'), 'Elevé' => __('Elevé')], null, ['class' => 'form-control activiteRecolteSanitaire']); ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            {{ Form::label(__("Nombre de désherbage manuel dans l'année"), null, ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::number('nombreDesherbage', null, ['class' => 'form-control', 'min' => '1', 'required']); ?>
                            </div>
                        </div>
                    </div>

                    <div class="fieldset-like">
                        <legend class="legend-center">
                            <h5 class="font-weight-bold text-decoration-underline">Etat sanitaire de la parcelle</h5>
                        </legend>

                        <div class="form-group row">
                            <?php echo Form::label(__('Présence de Pourriture Brune'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::select('presencePourritureBrune', ['Faible' => __('Faible'), 'Moyen' => __('Moyen'), 'Elevé ' => __('Elevé'), 'inexistant' => __('Inexistant')], null, ['class' => 'form-control presencePourritureBrune']); ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <?php echo Form::label(__('Présence de Swollen Shoot '), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::select('presenceSwollenShoot', ['Faible' => __('Faible'), 'Moyen' => __('Moyen'), 'Elevé ' => __('Elevé'), 'inexistant' => __('Inexistant')], null, ['class' => 'form-control presenceSwollenShoot']); ?>
                            </div>
                        </div>
                    </div>
                    <hr class="panel-wide">

                    <div class="form-group row">
                        <?php echo Form::label(__('Présence d’insectes parasites ou ravageurs ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('presenceInsectesParasites', ['non' => __('non'), 'oui' => __('oui')], null, ['class' => 'form-control presenceInsectesParasites']); ?>
                        </div>
                    </div>
                    <div class="form-group row" id="presenceInsectesParasitesRavageurs">
                        <?php echo Form::label(__('Parasites ou Ravageurs'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('presenceInsectesParasitesRavageur', ['Mirides' => __('Mirides'), 'Punaise' => __('Punaise'), 'Foreurs' => __('Foreurs'), 'Chenilles' => __('Chenilles'), 'Autre' => __('Autre')], null, ['class' => 'form-control presenceInsectesParasitesRavageur']); ?>
                        </div>
                    </div>
                    {{-- présence de autre ravageur --}}
                    <div class="form-group row" id="autrePresenceInsectesParasitesRavageurs">
                        <?php echo Form::label(__('Autres des insectes parasites ou ravageurs'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <table class="table table-striped table-bordered">
                                <tbody id="insectesParasites_area">
                                    <?php
        if($suiviparcelle->parasite)
        { 
        $i=0;
        $a=1;
        foreach($suiviparcelle->parasite as $data) {
           ?>
                                    <tr>
                                        <td class="row">
                                            <div class="col-xs-12 col-sm-12 bg-success">
                                                <badge class="btn  btn-outline--warning h-45 btn-sm">@lang('Insectes
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                parasites ou ravageurs')
                                                    <?php echo $a; ?>
                                                </badge>
                                            </div>
                                            <div class="col-xs-12 col-sm-6">
                                                <div class="form-group row">
                                                    {{ Form::label(__('Nom'), null, ['class' => '']) }}
                                                    <input type="text" name="insectesParasites[]" placeholder="..."
                                                        id="insectesParasites-<?php echo $a; ?>" class="form-control"
                                                        value="<?php echo $data->parasite; ?>">
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-6">
                                                <div class="form-group row">
                                                    {{ Form::label(__('Quantite'), null, ['class' => '']) }}
                                                    <select name="nombreinsectesParasites[]"
                                                        id="nombreinsectesParasites-<?php echo $a; ?>"
                                                        class="form-control nombreinsectesParasites">
                                                        <option value="Assez" <?php if ('Assez' == $data->nombre) {
                                                            echo 'selected';
                                                        } ?>>@lang('Assez')
                                                        </option>
                                                        <option value="Moins" <?php if ('Moins' == $data->nombre) {
                                                            echo 'selected';
                                                        } ?>>@lang('Moins')
                                                        </option>

                                                    </select>
                                                </div>
                                            </div>
                                            <?php if($a>1):?>
                                            <div class="col-xs-12 col-sm-8"><button type="button"
                                                    id="<?php echo $a; ?>"
                                                    class="removeRowinsectesParasites btn btn-danger btn-sm"><i
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
                                                <badge class="btn  btn-outline--warning h-45 btn-sm">@lang('Insectes
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                parasites ou ravageurs')
                                                </badge>
                                            </div>
                                            <div class="col-xs-12 col-sm-6">
                                                <div class="form-group row">
                                                    {{ Form::label(__('Nom'), null, ['class' => '']) }}
                                                    <input type="text" name="insectesParasites[]" placeholder="..."
                                                        id="insectesParasites-1" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-6">
                                                <div class="form-group row">
                                                    {{ Form::label(__('Quantite'), null, ['class' => '']) }}
                                                    <?php echo Form::select('nombreinsectesParasites[]', ['Assez' => __('Assez'), 'Moins' => __('Moins')], null, ['class' => 'form-control nombreinsectesParasites', 'id' => 'nombreinsectesParasites-1']); ?>
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
                                            <button id="addRowinsectesParasites" type="button"
                                                class="btn btn-success btn-sm"><i class="fa fa-plus"></i></button>
                                        </td>
                                    <tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    {{-- présence de autre ravageur fin --}}


                    <div class="form-group row" id="autreInsectesAmis">
                        <?php echo Form::label(__('Autres insectes amis'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <table class="table table-striped table-bordered">
                                <tbody id="insectesAmis_area">
                                    <?php
        if($suiviparcelle->insectes)
        { 
        $i=0;
        $a=1;
        foreach($suiviparcelle->insectes as $data) {
           ?>
                                    <tr>
                                        <td class="row">
                                            <div class="col-xs-12 col-sm-12 bg-success">
                                                <badge class="btn  btn-outline--warning h-45 btn-sm">@lang('Insectes amis')
                                                    <?php echo $a; ?>
                                                </badge>
                                            </div>
                                            <div class="col-xs-12 col-sm-6">
                                                <div class="form-group row">
                                                    {{ Form::label(__('Nom'), null, ['class' => '']) }}
                                                    <input type="text" name="insectesAmis[]" placeholder="..."
                                                        id="insectesAmis-<?php echo $a; ?>" class="form-control"
                                                        value="<?php echo $data->insecte; ?>">
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-6">
                                                <div class="form-group row">
                                                    {{ Form::label(__('Quantite'), null, ['class' => '']) }}
                                                    <select name="nombreinsectesAmis[]"
                                                        id="nombreinsectesAmis-<?php echo $a; ?>"
                                                        class="form-control nombreinsectesAmis">
                                                        <option value="Assez" <?php if ('Assez' == $data->nombre) {
                                                            echo 'selected';
                                                        } ?>>@lang('Assez')
                                                        </option>
                                                        <option value="Moins" <?php if ('Moins' == $data->nombre) {
                                                            echo 'selected';
                                                        } ?>>@lang('Moins')
                                                        </option>

                                                    </select>
                                                </div>
                                            </div>
                                            <?php if($a>1):?>
                                            <div class="col-xs-12 col-sm-8"><button type="button"
                                                    id="<?php echo $a; ?>"
                                                    class="removeRowinsectesAmis btn btn-danger btn-sm"><i
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
                                                    <?php echo Form::select('nombreinsectesAmis[]', ['Assez' => __('Assez'), 'Moins' => __('Moins')], null, ['class' => 'form-control nombreinsectesAmis', 'id' => 'nombreinsectesAmis-1']); ?>
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
                                            <button id="addRowinsectesAmis" type="button"
                                                class="btn btn-success btn-sm"><i class="fa fa-plus"></i></button>
                                        </td>
                                    <tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    {{-- présence de autre ravageur fin --}}
                    <hr class="panel-wide">


                    <hr class="panel-wide">
                    <div class="form-group row">
                        {{ Form::label(__('Citez les animaux que vous rencontrez dans les champs'), null, [
                            'class' => 'col-sm-4 control-label',
                        ]) }}
                        <div class="col-xs-12 col-sm-8">
                            <table class="table table-striped table-bordered">
                                <tbody id="animauxRencontres_area">
                                    <?php
        if($suiviparcelle->animal)
        {
        $i=0;
        $a=1;
        foreach ($suiviparcelle->animal as $data) {
           ?>
                                    <tr>
                                        <td class="row">
                                            <div class="col-xs-12 col-sm-12 bg-success">
                                                <badge class="btn  btn-outline--warning h-45 btn-sm">@lang('Animal')
                                                    <?php echo $a; ?>
                                                </badge>
                                            </div>
                                            <div class="col-xs-12 col-sm-12">
                                                <div class="form-group row">
                                                    {{ Form::label(__('Nom'), null, ['class' => '']) }}
                                                    <input type="text" name="animauxRencontres[]"
                                                        placeholder="Nom animal"
                                                        id="animauxRencontres-<?php echo $a; ?>" class="form-control"
                                                        value="<?php echo $data->animal; ?>">
                                                </div>
                                            </div>
                                            <?php if($a>1):?>
                                            <div class="col-xs-12 col-sm-8"><button type="button"
                                                    id="<?php echo $a; ?>"
                                                    class="removeRowanimauxRencontres btn btn-danger btn-sm"><i
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
                                    <?php
        }
        ?>


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
                        <button type="submit" class="btn btn--primary btn-block h-45 w-100">@lang('Envoyer')</button>
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
        /* Styles CSS personnalisés */
        .fieldset-like {
            border: 1px solid #ccc;
            /* Ajoutez une bordure */
            border-radius: 5px;
            /* Ajoutez des coins arrondis */
            padding: 10px;
            /* Ajoutez de l'espace intérieur */
            margin-bottom: 20px;
            /* Ajoutez une marge en bas */
            text-align: left;
            /* Alignez le contenu du div à gauche */
        }

        .legend-center {
            text-align: center;
            /* Centrez horizontalement la légende */
            margin-bottom: 10px;
            /* Ajoutez de l'espace sous la légende */
        }
    </style>
@endpush
@push('script')
    <script type="text/javascript">
        $(document).ready(function() {

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
                    '" ><option value="Assez">Assez</option><option value="Moins">Moins</option></select></div></div><div class="col-xs-12 col-sm-8"><button type="button" id="' +
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



            var productCount = $("#product_area tr").length + 1;
            $(document).on('click', '#addRow', function() {

                //---> Start create table tr
                var html_table = '<tr>';
                html_table +=
                    '<td class="row"><div class="col-xs-12 col-sm-8 bg-success"><badge class="btn  btn-outline--warning h-45 btn-sm">Information Arbre à Ombrage ' +
                    productCount +
                    '</badge></div><div class="col-xs-12 col-sm-8"><div class="form-group row"><label for="varietesOmbrage" class="col-sm-4 control-label">Variété</label><input placeholder="Variété arbre..." class="form-control" id="varietesOmbrage-' +
                    productCount +
                    '" name="varietesOmbrage[]" type="text"></div></div><div class="col-xs-12 col-sm-8"><div class="form-group row"><label for="nombreOmbrage" class="col-sm-4 control-label">Nombre</label><input type="number" min="1" name="nombreOmbrage[]" placeholder="Nombre d\'arbre" id="nombreOmbrage-' +
                    productCount +
                    '" class="form-control " value=""></div></div><div class="col-xs-12 col-sm-8"><button type="button" id="' +
                    productCount +
                    '" class="removeRow btn btn-danger btn-sm"><i class="fa fa-minus"></i></button></div></td>';

                html_table += '</tr>';
                //---> End create table tr

                productCount = parseInt(productCount) + 1;
                $('#product_area').append(html_table);

            });

            $(document).on('click', '.removeRow', function() {
                var row_id = $(this).attr('id');
                // delete only last row id
                if (row_id == $("#product_area tr").length) {
                    $(this).parents('tr').remove();
                    productCount = parseInt(productCount) - 1;

                }
            });

            var agroforestiersCount = $("#agroforestiers_area tr").length + 1;
            $(document).on('click', '#addRowagroforestiers', function() {

                //---> Start create table tr
                var html_table = '<tr>';
                html_table +=
                    '<td class="row"><div class="col-xs-12 col-sm-12 bg-success"><badge class="btn  btn-outline--warning h-45 btn-sm">Arbre agro-forestier ' +
                    agroforestiersCount +
                    '</badge></div><div class="col-xs-12 col-sm-6"><div class="form-group row"><label for="agroforestiers" class="">Type</label><input placeholder="Type arbre..." class="form-control" id="agroforestiers-' +
                    agroforestiersCount +
                    '" name="agroforestiers[]" type="text"></div></div><div class="col-xs-12 col-sm-6"><div class="form-group row"><label for="nombreagroforestiers" class="">Nombre</label><input type="number" name="nombreagroforestiers[]" min="1" placeholder="Nombre d\'arbre" id="nombreagroforestiers-' +
                    agroforestiersCount +
                    '" class="form-control " value=""></div></div><div class="col-xs-12 col-sm-8"><button type="button" id="' +
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

            var insectesParasitesCount = $("#insectesParasites_area tr").length + 1;
            $(document).on('click', '#addRowinsectesParasites', function() {

                //---> Start create table tr
                var html_table = '<tr>';
                html_table +=
                    '<td class="row"><div class="col-xs-12 col-sm-12 bg-success"><badge class="btn  btn-outline--warning h-45 btn-sm">Insectes parasites ou ravageurs ' +
                    insectesParasitesCount +
                    '</badge></div><div class="col-xs-12 col-sm-6"><div class="form-group row"><label for="insectesParasites" class="">Nom</label><input placeholder="Nom..." class="form-control" id="insectesParasites-' +
                    insectesParasitesCount +
                    '" name="insectesParasites[]" type="text"></div></div><div class="col-xs-12 col-sm-6"><div class="form-group row"><label for="nombreinsectesParasites" class="">Quantite</label><select name="nombreinsectesParasites[]" class="form-control nombreinsectesParasites" d="nombreinsectesParasites-' +
                    insectesParasitesCount +
                    '" ><option value="Assez">Assez</option><option value="Moins">Moins</option></select></div></div><div class="col-xs-12 col-sm-8"><button type="button" id="' +
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
                    '</badge></div><div class="col-xs-12 col-sm-6"><div class="form-group row"><label for="animauxRencontres" class="">Animal</label><input placeholder="Nom animal..." class="form-control" id="animauxRencontres-' +
                    animauxRencontresCount +
                    '" name="animauxRencontres[]" type="text"></div></div><div class="col-xs-12 col-sm-8"><button type="button" id="' +
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


            var pesticidesCount = $("#pesticidesAnneDerniere_area tr").length;
            $(document).on('click', '#addRowPesticidesAnneDerniere', function() {

                var html_table = '<tr>';
                html_table +=
                    '<td class="row"><div class="col-xs-12 col-sm-12 bg-success"><badge class="btn  btn-outline--warning h-45 btn-sm">Pesticide ' +
                    pesticidesCount +
                    '</badge></div><div class="col-xs-12 col-sm-4 pr-0"><div class="form-group"><label for="" class="">Nom</label><select class="form-control" id="pesticidesAnneDerniere-' +
                    pesticidesCount +
                    '" name="pesticidesAnneDerniere[' + pesticidesCount +
                    '][nom]"><option value="Herbicides">Herbicides</option><option value="Fongicides">Fongicides</option><option value="Nematicide">Nematicide</option><option value="Insecticide">Insecticide</option><option value="Acaricides">Acaricides</option><option value="Pesticides">Pesticides</option></select></div></div><div class="col-xs-12 col-sm-2"><div class="form-group row"><label>Unité</label><select class="form-control unite" name="pesticidesAnneDerniere[' +
                    pesticidesCount + '][unite]" id="unite-' +
                    pesticidesCount +
                    '"><option value="Kg">Kg</option><option value="L">L</option></select></div></div> <div class="col-xs-12 col-sm-2"><div class="form-group row"><label for="" class="">Quantité</label><input type="number" name ="pesticidesAnneDerniere[' +
                    pesticidesCount + '][quantite]" id="quantite-' +
                    pesticidesCount +
                    '" class="form-control quantite" placeholder="Quantité"></div></div><div class="col-xs-12 col-sm-2"><div class="form-group row"><label>Type contenant</label><select class="form-control contenant" name="pesticidesAnneDerniere[' +
                    pesticidesCount + '][contenant]" id="contenant-' +
                    pesticidesCount +
                    '"><option value="Sac">Sac</option><option value="Sachet">Sachet</option><option value="Boîte">Boîte</option><option value="Pot">Pot</option></select></div></div> <div class="col-xs-12 col-sm-2"><div class="form-group row"><label for="" class="">Fréquence</label><input type="number" name="pesticidesAnneDerniere[' +
                    pesticidesCount + '][frequence]" id="frequence-' +
                    pesticidesCount +
                    '" class="form-control frequence" placeholder="Fréquence"></div></div><div class="col-xs-12 col-sm-8"><button type="button" id="' +
                    pesticidesCount +
                    '" class="removeRowPesticidesAnneDerniere btn btn-danger btn-sm"><i class="fa fa-minus"></i></button></div></td>';
                html_table += '</tr>';
                //---> End create table tr

                pesticidesCount = parseInt(pesticidesCount) + 1;
                $('#pesticidesAnneDerniere_area').append(html_table);

            });

            $(document).on('click', '.removeRowPesticidesAnneDerniere', function() {
                var row_id = $(this).attr('id');
                if (row_id == $("#pesticidesAnneDerniere_area tr").length - 1) {
                    $(this).parents('tr').remove();
                    pesticidesCount = parseInt(pesticidesCount) - 1;
                }
            });
            //fin pesticide lanne derniere

            //intrants lannee derniere
            var intrantsCount = $("#intrantsAnneDerniere_area tr").length;
            $(document).on('click', '#addRowIntrantsAnneDerniere', function() {

                var html_table = '<tr>';
                html_table +=
                    '<td class="row"><div class="col-xs-12 col-sm-12 bg-success"><badge class="btn  btn-outline--warning h-45 btn-sm">Intrant ' +
                    intrantsCount +
                    '</badge></div><div class="col-xs-12 col-sm-4 pr-0"><div class="form-group"><label for="" class="">Nom</label><select class="form-control" id="intrantsAnneDerniere-' +
                    intrantsCount +
                    '" name="intrantsAnneDerniere[' + intrantsCount +
                    '][nom]"><option value="Dechets animaux">Dechets animaux</option><option value="Fongicides">Fongicides</option><option value="NPK">NPK</option><option value="Compost">Compost</option><option value="Biofertilisant/Bio stimulant">Biofertilisant/Bio stimulant</option><option value="Engrais organique préfabriqué">Engrais organique préfabriqué</option><option value="Engrais foliaire">Engrais foliaire</option></select></div></div><div class="col-xs-12 col-sm-2"><div class="form-group row"><label>Unité</label><select class="form-control unite" name="intrantsAnneDerniere[' +
                    intrantsCount + '][unite]" id="unite-' +
                    intrantsCount +
                    '"><option value="Kg">Kg</option><option value="L">L</option></select></div></div> <div class="col-xs-12 col-sm-2"><div class="form-group row"><label for="" class="">Quantité</label><input type="number" name ="intrantsAnneDerniere[' +
                    intrantsCount + '][quantite]" id="quantite-' +
                    intrantsCount +
                    '" class="form-control quantite" placeholder="Quantité"></div></div><div class="col-xs-12 col-sm-2"><div class="form-group row"><label>Type contenant</label><select class="form-control contenant" name="intrantsAnneDerniere[' +
                    intrantsCount + '][contenant]" id="contenant-' +
                    intrantsCount +
                    '"><option value="Sac">Sac</option><option value="Sachet">Sachet</option><option value="Boîte">Boîte</option><option value="Pot">Pot</option></select></div></div> <div class="col-xs-12 col-sm-2"><div class="form-group row"><label for="" class="">Fréquence</label><input type="number" name="intrantsAnneDerniere[' +
                    intrantsCount + '][frequence]" id="frequence-' +
                    intrantsCount +
                    '" class="form-control frequence" placeholder="Fréquence"></div></div><div class="col-xs-12 col-sm-8"><button type="button" id="' +
                    intrantsCount +
                    '" class="removeRowIntrantsAnneDerniere btn btn-danger btn-sm"><i class="fa fa-minus"></i></button></div></td>';
                html_table += '</tr>';
                //---> End create table tr

                intrantsCount = parseInt(intrantsCount) + 1;
                $('#intrantsAnneDerniere_area').append(html_table);
            });

            $(document).on('click', '.removeRowIntrantsAnneDerniere', function() {
                var row_id = $(this).attr('id');
                if (row_id == $("#intrantsAnneDerniere_area tr").length - 1) {
                    $(this).parents('tr').remove();
                    intrantsCount = parseInt(intrantsCount) - 1;
                }
            });
            //fin intrants lanne derniere
        });

        $('#courseaux,#agroforestiersobtenus,#presenceInsectesParasitesRavageurs,#recu,#autrePesticides,#autreInsectesAmis,#autrePresenceInsectesParasitesRavageurs,#traite,#presenceAutreInsecte')
            .hide();

        $('#localite').chained("#section")
        $("#producteur").chained("#localite");
        $("#parcelle").chained("#producteur");


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
        if ($('.arbresagroforestiers').val() == 'oui') {
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
