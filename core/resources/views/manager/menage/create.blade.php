@extends('manager.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body">
                    {!! Form::open([
                        'route' => ['manager.suivi.menage.store'],
                        'method' => 'POST',
                        'class' => 'form-horizontal',
                        'id' => 'flocal',
                        'enctype' => 'multipart/form-data',
                    ]) !!}

                    <div class="form-group row">
                        <label class="col-sm-4 control-label">@lang('Selectionner une section')</label>
                        <div class="col-xs-12 col-sm-8">
                            <select class="form-control" name="section" id="section" required>
                                <option value="">@lang('Selectionner une option')</option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}"
                                        {{ old('section') == $section->id ? 'selected' : '' }}>
                                        {{ $section->libelle }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 control-label">@lang('Selectionner une localite')</label>
                        <div class="col-xs-12 col-sm-8">
                            <select class="form-control" name="localite" id="localite" required>
                                <option value="">@lang('Selectionner une option')</option>
                                @foreach ($localites as $localite)
                                    <option value="{{ $localite->id }}" data-chained="{{ $localite->section->id }}"
                                        {{ old('localite') == $localite->id ? 'selected' : '' }}>
                                        {{ $localite->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-sm-4 control-label">@lang('Selectionner un producteur')</label>
                        <div class="col-xs-12 col-sm-8">
                            <select class="form-control" name="producteur_id" id="producteur" required>
                                <option value="">@lang('Selectionner une option')</option>
                                @foreach ($producteurs as $producteur)
                                    <option value="{{ $producteur->id }}" data-chained="{{ $producteur->localite->id }}"
                                        {{ old('producteur_id') == $producteur->id ? 'selected' : '' }}>
                                        {{ $producteur->nom }} {{ $producteur->prenoms }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ Form::label(__('Quartier'), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::text('quartier', null, ['placeholder' => '...', 'class' => 'form-control quartier', 'id' => 'quartier', 'required']); ?>
                        </div>
                    </div>
                    <hr class="panel-wide">
                    <div class="form-group row">
                        <?php echo Form::label(__('Nombre d’enfants de 0 à 5ans  présents dans le ménage ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::number('ageEnfant0A5', null, ['placeholder' => 'Nombre', 'class' => 'form-control', 'min' => '0', 'required']); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <?php echo Form::label(__('Nombre d’enfants de 6 à 17ans  présents dans le ménage ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::number('ageEnfant6A17', null, ['placeholder' => 'Nombre', 'class' => 'form-control', 'min' => '0', 'required']); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <?php echo Form::label(__('Parmi les enfants de 6 à 17, combien sont scolarisés ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::number('enfantscolarises', null, ['placeholder' => 'Nombre', 'class' => 'form-control', 'required', 'min' => '0']); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <?php echo Form::label(__('Parmi les enfants de 0 à 5ans, combien n’ont pas d’extrait de naissance ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::number('enfantsPasExtrait', null, ['placeholder' => 'Nombre', 'class' => 'form-control', 'min' => '0', 'required']); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <?php echo Form::label(__('Parmi les enfants de 6 à 17ans, combien n’ont pas d’extrait de naissance ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::number('enfantsPasExtrait6A17', null, ['placeholder' => 'Nombre', 'class' => 'form-control', 'min' => '0', 'required']); ?>
                        </div>
                    </div>
                    <hr class="panel-wide">

                    <div class="form-group row">
                        {{ Form::label(__('Source Energie du ménage'), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                            <select class="form-control select2-multi-select sources_energies" name="sourcesEnergie[]"
                                multiple id="sources_energies" required>
                                <option value="">@lang('Selectionner les options')</option>
                                <option value="Bois de chauffe"
                                    {{ in_array('Bois de chauffe', old('sourcesEnergie', [])) ? 'selected' : '' }}>
                                    Bois de chauffe
                                </option>
                                <option value="Charbon"
                                    {{ in_array('Charbon', old('sourcesEnergie', [])) ? 'selected' : '' }}>
                                    Charbon
                                </option>
                                <option value="Gaz" {{ in_array('Gaz', old('sourcesEnergie', [])) ? 'selected' : '' }}>
                                    Gaz
                                </option>
                                <option value="Four à pétrole"
                                    {{ in_array('Four à pétrole', old('sourcesEnergie', [])) ? 'selected' : '' }}>
                                    Four à pétrole
                                </option>
                            </select>
                        </div>
                    </div>


                    <div class="form-group row" id="boisChauffes">
                        {{ Form::label(__('Combien de bois par semaine?'), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::number('boisChauffe', null, ['id' => 'boisChauffe', 'placeholder' => __('Quantité'), 'class' => 'form-control boisChauffe', 'min' => '1']); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ Form::label(__('Comment gérez-vous les ordures ménagères ?'), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                            <select class="form-control select2-multi-select ordureMenagere" name="ordureMenageres[]"
                                multiple required>
                                <option value="">@lang('Selectionner les options')</option>
                                <option value="Dépotoirs Publique"
                                    {{ in_array('Dépotoirs Publique', old('ordureMenageres', [])) ? 'selected' : '' }}>
                                    Dépotoirs Publique
                                </option>
                                <option value="Poubelle de Maison"
                                    {{ in_array('Poubelle de Maison', old('ordureMenageres', [])) ? 'selected' : '' }}>
                                    Poubelle de Maison
                                </option>
                                <option value="Ramassage ordures organisé"
                                    {{ in_array('Ramassage ordures organisé', old('ordureMenageres', [])) ? 'selected' : '' }}>
                                    Ramassage ordures organisé
                                </option>
                                <option value="Aucun"
                                    {{ in_array('Aucun', old('ordureMenageres', [])) ? 'selected' : '' }}>
                                    Aucun
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <?php echo Form::label(__('Pratiquez-vous la séparation des déchets ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('separationMenage', ['non' => __('non'), 'oui' => __('oui')], null, ['class' => 'form-control separationMenage', 'required']); ?>
                        </div>
                    </div>


                    <div class="form-group row">
                        {{ Form::label(__("Comment gérez-vous l'eau de toilette ?"), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('eauxToillette', ['Air Libre' => 'Air Libre', 'Fosse Septique' => 'Fosse Septique'], null, ['placeholder' => __('Selectionner une reponse'), 'class' => 'form-control', 'id' => 'eauxToillette', 'required']); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ Form::label(__("Comment gérez-vous l'eau de Vaisselle ?"), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('eauxVaisselle', ['Air Libre' => 'Air Libre', 'Fosse Septique' => 'Fosse Septique'], null, ['placeholder' => __('Selectionner une reponse'), 'class' => 'form-control', 'id' => 'eauxVaisselle', 'required']); ?>
                        </div>
                    </div>


                    <div class="form-group row">
                        <?php echo Form::label(__('Existe-t-il un WC pour le ménage ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('wc', ['non' => __('non'), 'oui' => __('oui')], null, ['class' => 'form-control wc', 'required']); ?>
                        </div>
                    </div>


                    <div class="form-group row">
                        {{ Form::label(__("Où procurez-Vous l'eau potable ?"), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('sources_eaux', ['Pompe Hydraulique' => 'Pompe Hydraulique', 'Marigot' => 'Marigot', 'Puits' => 'Puits', 'Eaux Courante nationale' => 'Eaux Courante nationale', 'Autre' => 'Autre'], null, ['placeholder' => __('Selectionner une reponse'), 'class' => 'form-control sources_eaux', 'id' => 'sources_eaux', 'required']); ?>
                        </div>
                    </div>

                    <div class="form-group row" id="autreSourceEaux">
                        {{ Form::label(__(''), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::text('autreSourceEau', null, ['id' => 'autreSourceEau', 'placeholder' => __('Autre'), 'class' => 'form-control autreSourceEau']); ?>
                        </div>
                    </div>

                    <hr class="panel-wide">

                    <div class="form-group row">
                        <?php echo Form::label(__('Effectuez vous vous-même les traitement phyto sanitaire dans vos champs ?'), null, ['class' => 'col-sm-4 control-label', 'required']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('traitementChamps', ['non' => __('non'), 'oui' => __('oui')], null, ['class' => 'form-control traitementChamps', 'required']); ?>
                        </div>
                    </div>
                    <div class="form-group row" id="infosPersonneTraitant">
                        <div class="form-group row">
                            <?php echo Form::label(__('Donnez le nom de la personne qui éffectue les traitement phyto sanitaire dans vos champs'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::text('nomApplicateur', null, ['id' => 'nomApplicateur', 'class' => 'form-control nomApplicateur']); ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <?php echo Form::label(__('Donnez son numéro de téléphone'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::text('numeroApplicateur', null, ['id' => 'numeroApplicateur', 'class' => 'form-control numeroApplicateur']); ?>
                            </div>
                        </div>
                    </div>

                    <div id="avoirMachine">

                        <div class="form-group row">
                            {{ Form::label(__('Quel type de machine ?'), null, ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::select('type_machines', ['Pulverisateur' => 'Pulverisateur', 'Atomiseur' => 'Atomiseur', 'Autre' => 'Autre'], null, ['placeholder' => __('Selectionner une reponse'), 'class' => 'form-control type_machines', 'id' => 'type_machines']); ?>
                            </div>
                        </div>
                        <div class="form-group row" id="etatatomiseurs">
                            <?php echo Form::label(__('La machine est-elle en bon état?'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::select('etatatomiseur', ['' => null, 'oui' => __('oui'), 'non' => __('non')], null, ['id' => 'etatatomiseur', 'class' => 'form-control etatatomiseur']); ?>
                            </div>
                        </div>
                        <div class="form-group row" id="autreMachines">
                            <div class="form-group row">
                                <?php echo Form::label(__('Quel est son nom ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                                <div class="col-xs-12 col-sm-8">
                                    <?php echo Form::text('autreMachine', null, ['class' => 'form-control autreMachine', 'placeholder' => __('Autre machine')]); ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <?php echo Form::label(__('La machine est-elle en bon état?'), null, ['class' => 'col-sm-4 control-label']); ?>
                                <div class="col-xs-12 col-sm-8">
                                    <?php echo Form::select('etatAutreMachine', ['' => null, 'oui' => __('oui'), 'non' => __('non')], null, ['id' => 'etatAutreMachine', 'class' => 'form-control etatAutreMachine']); ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            {{ Form::label(__('Où gardez-vous cette machine ?'), null, ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::select('garde_machines', ['Dans la maison' => 'Dans la maison', 'Dans un magasin à la maison' => 'Dans un magasin à la maison', 'Au Champs' => 'Au Champs', 'Autre' => 'Autre'], null, ['placeholder' => __('Selectionner une reponse'), 'class' => 'form-control garde_machines', 'id' => 'garde_machines']); ?>
                            </div>
                        </div>
                        <div class="form-group row" id="autreEndroits">
                            <?php echo Form::label(__('Quel est l\'endroit ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::text('autreEndroit', null, ['id' => 'autreEndroit', 'class' => 'form-control autreEndroit', 'placeholder' => __('Autre Endroit où la machine est gardée')]); ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">

                        <?php echo Form::label(__('Avez-vous des équipement individuel (EPI) ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('equipements', ['non' => __('non'), 'oui' => __('oui')], null, ['class' => 'form-control equipements', 'required']); ?>
                        </div>
                    </div>

                    <hr class="panel-wide">


                    <div class="form-group row">
                        <?php echo Form::label(__("Votre conjoint(e) fait une activité qui produit de l'argent ?"), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('activiteFemme', ['non' => __('non'), 'oui' => __('oui')], null, ['class' => 'form-control activiteFemme', 'required']); ?>
                        </div>
                    </div>

                    <div class="form-group row" id="nomActiviteFemmes">
                        <?php echo Form::label(__('Quelle Activité ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <input type="text" id="nomActiviteFemme" name="nomActiviteFemme"
                                placeholder="Quelle Activité ?" class="form-control nomActiviteFemme"
                                value="{{ old('nomActiviteFemme') }}">
                        </div>
                    </div>
                    <div class="form-group row" id="champFemmes">
                        <?php echo Form::label(__('Es-tu prêt à donner une partie de ton champ à votre conjoint(e) ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('champFemme', ['non' => __('non'), 'oui' => __('oui')], null, ['id' => 'champFemme', 'class' => 'form-control champFemme']); ?>
                        </div>
                    </div>

                    <div class="form-group row" id="nombreHectareFemmes">
                        <?php echo Form::label(__("Combien d'hectare ?"), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <input type="text" name="nombreHectareFemme" id="nombreHectareFemme"
                                placeholder="Ex: 2 ha" class="form-control nombreHectareFemme"
                                value="{{ old('nombreHectareFemme') }}">
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
    <x-back route="{{ route('manager.suivi.menage.index') }}" />
@endpush

@push('script')
    <script type="text/javascript">
        $("#localite").chained("#section");
        $("#producteur").chained("#localite");
        $(document).ready(function() {
            $('#avoirMachine,#boisChauffes,#etatatomiseurs, #nomActiviteFemmes,#nombreHectareFemmes,#autreMachine,#autreEndroits,#autreSourceEaux')
                .hide();

            $('.traitementChamps').change(function() {
                var traitementChamps = $('.traitementChamps').val();
                if (traitementChamps == 'oui') {
                    $('#avoirMachine').show('slow');
                    $('#infosPersonneTraitant').hide('slow');
                    $('.nomApplicateur').hide('slow');
                    $('#autreMachines').hide('slow');
                    $('#nomApplicateur').prop('required', false);
                    $('.nomApplicateur').val('');
                    $('.numeroApplicateur').hide('slow');
                    $('#numeroApplicateur').prop('required', false);
                    $('.numeroApplicateur').val('');
                    $('#type_machines').prop('required', true);
                    $('#garde_machines').prop('required', true);
                } else {
                    $('#avoirMachine').hide('slow');
                    $('#type_machines').prop('required', false);
                    $('#garde_machines').prop('required', false);
                    $('#infosPersonneTraitant').show('slow');
                    $('.nomApplicateur').show('slow');
                    $('#nomApplicateur').prop('required', true);
                    $('.numeroApplicateur').show('slow');
                    $('#numeroApplicateur').prop('required', true);
                }
            });
            $('.garde_machines').change(function() {
                var garde_machines = $('.garde_machines').val();
                if (garde_machines == 'Autre') {
                    $('#autreEndroits').show('slow');
                    $('.autreEndroit').show('slow');
                    $('#autreEndroit').prop('required', true);
                } else {
                    $('#autreEndroits').hide('slow');
                    $('#autreEndroit').prop('required', false);
                    $('.autreEndroit').val('');
                }
            });

            $('.type_machines').change(function() {
                var type_machines = $('.type_machines').val();
                if (type_machines == 'Atomiseur' || type_machines == 'Pulverisateur') {
                    $('#etatatomiseurs').show('slow');
                    $('.etatatomiseur').show('slow');
                    $('#etatatomiseur').prop('required', true);
                } else {
                    $('#etatatomiseurs').hide('slow');
                    $('#etatatomiseur').hide('slow');
                    $('.etatatomiseur').val('');
                    $('#etatatomiseur').prop('required', false);
                }
                if (type_machines == 'Autre') {
                    $('#autreMachines').show('slow');
                    $('#autreMachine').show('slow');
                    $('.autreMachine').show('slow');
                } else {
                    $('#autreMachine').hide('slow');
                    $('.autreMachine').val('');
                    $('#autreMachines').hide('slow');
                }
            });

            $('.sources_energies').change(function() {
                var sources_energies = $('.sources_energies').find(":selected").map((key, item) => {
                    return item.textContent.trim();
                }).get();
                if (sources_energies.includes("Bois de chauffe")) {
                    $('#boisChauffes').show('slow');
                    $('.boisChauffe').show('slow');
                    $('#boisChauffe').prop('required', true);
                    $('.boisChauffe').css('display', 'block');
                } else {
                    $('#boisChauffes').hide('slow');
                    $('.boisChauffe').val('');
                    $('#boisChauffe').prop('required', false);
                }
            });

            $('.activiteFemme').change(function() {
                var activiteFemme = $('.activiteFemme').val();
                if (activiteFemme == 'oui') {
                    $('#nomActiviteFemmes').show('slow');
                    $('.nomActiviteFemme').show('slow');
                    $('#nomActiviteFemme').prop('required', true);
                    $('.nomActiviteFemme').css('display', 'block');

                    $('#champFemmes').hide('slow');
                    $('.champFemme').val('');
                    $('#champFemme').prop('required', false);
                } else {
                    $('#nomActiviteFemmes').hide('slow');
                    $('.nomActiviteFemme').val('');
                    $('#nomActiviteFemme').prop('required', false);
                    $('.nomActiviteFemme').hide('slow');

                    $('#champFemmes').show('slow');
                    $('.champFemme').show('slow');
                    $('#champFemme').prop('required', true);
                }
            });

            $('.champFemme').change(function() {
                var champFemme = $('.champFemme').val();
                if (champFemme == 'oui') {
                    $('#nombreHectareFemmes').show('slow');
                    $('.nombreHectareFemme').css('display', 'block');
                    $('.nombreHectareFemme').show('slow');
                    $('#nombreHectareFemme').prop('required', true);

                } else {
                    $('#nombreHectareFemmes').hide('slow');
                    $('.nombreHectareFemme').val('');
                    $('.nombreHectareFemme').hide('slow');
                    $('#nombreHectareFemme').prop('required', false);
                }
            });
            $('.sources_eaux').change(function() {
                var sources_eaux = $('.sources_eaux').val();
                if (sources_eaux == 'Autre') {
                    $('#autreSourceEaux').show('slow');
                    $('.autreSourceEau').show('slow');
                    $('#autreSourceEau').prop('required', true);
                } else {
                    $('#autreSourceEaux').hide('slow');
                    $('.autreSourceEau').val('');
                    $('#autreSourceEau').prop('required', false);
                }
            });

        });
    </script>
@endpush
