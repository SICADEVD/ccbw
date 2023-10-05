@extends('manager.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body">
                    {!! Form::model($menage, [
                        'method' => 'POST',
                        'route' => ['manager.suivi.menage.store', $menage->id],
                        'class' => 'form-horizontal',
                        'id' => 'flocal',
                        'enctype' => 'multipart/form-data',
                    ]) !!}
                    <input type="hidden" name="id" value="{{ $menage->id }}">

                    <div class="form-group row">
                        <label class="col-sm-4 control-label">@lang('Selectionner une localite')</label>
                        <div class="col-xs-12 col-sm-8">
                            <select class="form-control" name="localite" id="localite" required>
                                <option value="">@lang('Selectionner une option')</option>
                                @foreach ($localites as $localite)
                                    <option value="{{ $localite->id }}" @selected($localite->id == $menage->producteur->localite->id)>
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
                                        @selected($producteur->id == $menage->producteur_id)>
                                        {{ $producteur->nom }} {{ $producteur->prenoms }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <hr class="panel-wide">
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
                            <?php echo Form::number('ageEnfant6A17', null, ['placeholder' => 'Nombre', 'class' => 'form-control', 'min' => '0', 'required']); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <?php echo Form::label(__('Nombre d’enfants de 6 à 17ans  présents dans le ménage ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::number('ageEnfant0A5', null, ['placeholder' => 'Nombre', 'class' => 'form-control', 'min' => '0', 'required']); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <?php echo Form::label(__('Parmi ces enfants, combien sont scolarisés ?'), null, ['class' => 'col-sm-4 control-label']); ?>
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
                    <hr class="panel-wide">

                    <div class="form-group row">
                        {{ Form::label(__('Source Energie du ménage'), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('sources_energies', ['Bois de chauffe' => 'Bois de chauffe', 'Charbon' => 'Charbon', 'Gaz' => 'Gaz', 'Four à pétrole' => 'Four à pétrole'], null, ['placeholder' => __('Selectionner une reponse'), 'class' => 'form-control sources_energies', 'id' => 'sources_energies', 'required']); ?>
                        </div>
                    </div>

                    <div class="form-group row" id="boisChauffe">
                        {{ Form::label(__('Combien de bois par semaine?'), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::number('boisChauffe', null, ['placeholder' => __('Quantité'), 'class' => 'form-control boisChauffe', 'min' => '1']); ?>
                        </div>
                    </div>


                    <div class="form-group row">
                        {{ Form::label(__('Comment gérez-vous les ordures ménagères ?'), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('ordures_menageres', ['Dépotoirs Publique' => 'Dépotoirs Publique', 'Poubelle de Maison' => 'Poubelle de Maison', 'Ramassage ordures organisé' => 'Ramassage ordures organisé', 'Aucun' => 'Aucun'], null, ['placeholder' => __('Selectionner une reponse'), 'class' => 'form-control', 'id' => 'ordures_menageres', 'required']); ?>
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
                            <?php echo Form::select('sources_eaux', ['Pompe Hydraulique' => 'Pompe Hydraulique', 'Marigot' => 'Marigot', 'Puits' => 'Puits', 'Eaux Courante nationale' => 'Courante nationale'], null, ['placeholder' => __('Selectionner une reponse'), 'class' => 'form-control', 'id' => 'sources_eaux', 'required']); ?>
                        </div>
                    </div>

                    <hr class="panel-wide">

                    <div class="form-group row">
                        <?php echo Form::label(__('Traitez toujours vous-même vos champs '), null, ['class' => 'col-sm-4 control-label', 'required']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('traitementChamps', ['oui' => __('oui'), 'non' => __('non')], null, ['class' => 'form-control traitementChamps', 'required']); ?>
                        </div>
                    </div>
                    <div class="form-group row" id="infosPersonneTraitant">
                        <div class="form-group row">
                            <?php echo Form::label(__('Donnez le nom de la personne qui traite vos champs'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::text('nomApplicateur', null, ['placeholder' => __('-----------'), 'class' => 'form-control nomApplicateur']); ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <?php echo Form::label(__('Donnez son numéro de téléphone'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::text('numeroApplicateur', null, ['placeholder' => __('-----------'), 'class' => 'form-control numeroApplicateur']); ?>
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
                        <div class="form-group row" id="etatatomiseur">
                            <?php echo Form::label(__("L'Atomiseur est-il en bon état?"), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::select('etatatomiseur', ['oui' => __('oui'), 'non' => __('non')], null, ['class' => 'form-control etatatomiseur']); ?>
                            </div>
                        </div>

                        <div class="form-group row" id="autreMachine">
                            <?php echo Form::label(__('Quel est son nom ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::text('autreMachine', null, ['class' => 'form-control autreMachine', 'placeholder' => __('Autre machine')]); ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            {{ Form::label(__('Où gardez-vous cette machine ?'), null, ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::select('garde_machines', ['Dans la maison' => 'Dans la maison', 'Dans un magasin à la maison' => 'Dans un magasin à la maison', 'Au Champs' => 'Au Champs', 'Autre' => 'Autre'], null, ['placeholder' => __('Selectionner une reponse'), 'class' => 'form-control garde_machines', 'id' => 'garde_machines']); ?>
                            </div>
                        </div>
                        <div class="form-group row" id="autreEndroit">
                            <?php echo Form::label(__('Quel est l\'endroit ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::text('autreEndroit', null, ['class' => 'form-control autreEndroit', 'placeholder' => __('Autre Endroit où la machine est gardée')]); ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <?php echo Form::label(__('Avez-vous des Equipements de Protection Individuel ?'), null, ['class' => 'col-sm-4 control-label']); ?>
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

                    <div class="form-group row" id="nomActiviteFemme">
                        <?php echo Form::label(__('Quelle Activité ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <input type="text" name="nomActiviteFemme" placeholder=""
                                class="form-control nomActiviteFemme" value="{{ old('nomActiviteFemme') }}">
                        </div>
                    </div>
                    <div class="form-group row" id="champFemme">
                        <?php echo Form::label(__('Es-tu prêt à donner une partie de ton champ à votre conjoint(e) ?'), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::select('champFemme', ['non' => __('non'), 'oui' => __('oui')], null, ['class' => 'form-control champFemme']); ?>
                        </div>
                    </div>

                    <div class="form-group row" id="nombreHectareFemme">
                        <?php echo Form::label(__("Combien d'hectare ?"), null, ['class' => 'col-sm-4 control-label']); ?>
                        <div class="col-xs-12 col-sm-8">
                            <input type="text" name="nombreHectareFemme" placeholder="Ex: 2 ha"
                                class="form-control nombreHectareFemme" value="{{ old('nombreHectareFemme') }}">
                        </div>
                    </div>

                    <hr class="panel-wide">
                    <hr class="panel-wide">


                    <div class="form-group">
                        <button type="submit" class="btn btn--primary btn-block h-45 w-100">@lang('Envoyer')</button>
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
        $("#producteur").chained("#localite");
        $(document).ready(function() {
            $('#avoirMachine,#boisChauffe,#etatatomiseur, #nomActiviteFemme,#nombreHectareFemme,#autreMachine,#autreEndroit,#infosPersonneTraitant')
                .hide();

            $('.traitementChamps').change(function() {
                var traitementChamps = $('.traitementChamps').val();
                if (traitementChamps == 'oui') {
                    $('#avoirMachine').show('slow');
                    $('#infosPersonneTraitant').hide('slow');
                } else {
                    $('#avoirMachine').hide('slow');
                    $('#infosPersonneTraitant').show('slow');
                }
            });
            $('.garde_machines').change(function() {
                var garde_machines = $('.garde_machines').val();
                if (garde_machines == 'Autre') {
                    $('#autreEndroit').show('slow');
                    $('.autreEndroit').show('slow');
                } else {
                    $('#autreEndroit').hide('slow');
                    $('.autreEndroit').val('');
                }
            });

            $('.type_machines').change(function() {
                var type_machines = $('.type_machines').val();
                if (type_machines == 'Atomiseur' || type_machines == 'Pulverisateur') {
                    $('#etatatomiseur').show('slow');
                    $('.etatatomiseur').show('slow');
                } else {
                    $('#etatatomiseur').hide('slow');
                    $('.etatatomiseur').val('');
                }
                if (type_machines == 'Autre') {
                    $('#autreMachine').show('slow');
                    $('.autreMachine').show('slow');
                } else {
                    $('#autreMachine').hide('slow');
                    $('.autreMachine').val('');
                }
            });


            $('.sources_energies').change(function() {
                var sources_energies = $('.sources_energies').val();
                if (sources_energies == 'Bois de chauffe') {
                    $('#boisChauffe').show('slow');
                    $('.boisChauffe').css('display', 'block');
                } else {
                    $('#boisChauffe').hide('slow');
                    $('.boisChauffe').val('');
                }
            });

            $('.activiteFemme').change(function() {
                var activiteFemme = $('.activiteFemme').val();
                if (activiteFemme == 'oui') {
                    $('#nomActiviteFemme').show('slow');
                    $('.nomActiviteFemme').css('display', 'block');

                    $('#champFemme').hide('slow');
                    $('.champFemme').val('');
                } else {
                    $('#nomActiviteFemme').hide('slow');
                    $('.nomActiviteFemme').val('');

                    $('#champFemme').show('slow');
                    $('.champFemme').show('slow');
                }
            });

            $('.champFemme').change(function() {
                var champFemme = $('.champFemme').val();
                if (champFemme == 'oui') {
                    $('#nombreHectareFemme').show('slow');
                    $('.nombreHectareFemme').css('display', 'block');

                } else {
                    $('#nombreHectareFemme').hide('slow');
                    $('.nombreHectareFemme').val('');
                }
            });

        });
    </script>
@endpush
