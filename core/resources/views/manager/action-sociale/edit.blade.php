@extends('manager.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body">
                    {!! Form::model($actionSociale, [
                        'method' => 'POST',
                        'route' => ['manager.communaute.action.sociale.store', $actionSociale->id],
                        'class' => 'form-horizontal',
                        'id' => 'flocal',
                        'enctype' => 'multipart/form-data',
                    ]) !!}
                    <input type="hidden" name="id" value="{{ $actionSociale->id }}">

                    <div class="form-group row">
                        <label class="col-sm-4 control-label" for="type_projet">Type de projet:</label>
                        <div class="col-xs-12 col-sm-8">
                            <select id="type_projet" class="form-control" name="type_projet" required>
                                <option value="Hydrolique villageoise">Hydrolique villageoise</option>
                                <option value="Education"
                                    {{ old('type_projet') == 'Hydrolique villageois' ? 'selected' : '' }}>Education</option>
                                <option value="Voirie" {{ old('type_projet') == 'Voirie' ? 'selected' : '' }}>Voirie
                                </option>
                                <option value="Electricité" {{ old('type_projet') == 'Electricité' ? 'selected' : '' }}>
                                    Electricité</option>
                                <option value="Santé" {{ old('type_projet') == 'Santé' ? 'selected' : '' }}>Santé</option>
                                <option value="Equipement rural"
                                    {{ old('type_projet') == 'Equipement rural' ? 'selected' : '' }}>Equipement rural
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 control-label" for="titre_projet">Titre du projet:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input type="text" id="titre_projet" class="form-control" name="titre_projet"
                                value="{{ $actionSociale->titre_projet }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 control-label" for="description_projet">Description du Projet:</label>
                        <div class="col-xs-12 col-sm-8">
                            <textarea id="description_projet" class="form-control" name="description_projet" rows="4" required>{{ $actionSociale->description_projet }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 control-label" for="beneficiaires_projet">Bénéficiaires du projet:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input type="text" id="beneficiaires_projet" class="form-control" name="beneficiaires_projet"
                                value="{{ $actionSociale->beneficiaires_projet }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 control-label" for="niveau_realisation">Niveau de réalisation:</label>
                        <div class="col-xs-12 col-sm-8">
                            <select id="niveau_realisation" class="form-control" name="niveau_realisation">
                                <option value="Non démarré"
                                    {{ old('niveau_realisation') == 'Non démarré' ? 'selected' : '' }}>Non démarré</option>
                                <option value="En Cours" {{ old('niveau_realisation') == 'En Cours' ? 'selected' : '' }}>En
                                    Cours</option>
                                <option value="Achevé" {{ old('niveau_realisation') == 'Achevé' ? 'selected' : '' }}>Achevé
                                </option>
                            </select>
                        </div>
                    </div>


                    <div id="date_demarrage_container" style="display:none;">
                        <div class="form-group row">
                            <label class="col-sm-4 control-label" for="date_demarrage">Date de démarrage du projet:</label>
                            <div class="col-xs-12 col-sm-8">
                                <input type="date" id="date_demarrage" class="form-control" name="date_demarrage"
                                    value="{{ $actionSociale->date_demarrage }}">
                            </div>
                        </div>
                    </div>

                    <div id="date_fin_projet_container" style="display:none;">
                        <div class="form-group row">
                            <label class="col-sm-4 control-label" for="date_fin_projet">Date de fin du projet:</label>
                            <div class="col-xs-12 col-sm-8">
                                <input type="date" id="date_fin_projet" class="form-control" name="date_fin_projet"
                                    value="{{ $actionSociale->date_fin_projet }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 control-label" for="cout_projet">Coûts du projet:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input type="text" id="cout_projet" class="form-control" name="cout_projet"
                                value="{{ $actionSociale->cout_projet }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-xs-12 col-sm-12">
                            <table class="table table-striped table-bordered">
                                <tbody id="partenaire_area">
                                    @if ($partenaires)
                                        @foreach ($partenaires as $index => $partenaire)
                                            <tr>
                                                <td class="row">
                                                    <div class="col-xs-12 col-sm-12 bg-success">
                                                        <badge class="btn  btn-outline--warning h-45 btn-sm">
                                                            @lang('Partenaire impliqué') {{ $index + 1 }}
                                                        </badge>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-4">
                                                        <div class="form-group row">
                                                            <label class="col-sm-4 control-label"
                                                                for="partenaire">Partenaire
                                                                impliqué:</label>
                                                            <input type="text" id="partenaire- {{ $index }}"
                                                                class="form-control"
                                                                name="partenaires[{{ $index }}][partenaire]"
                                                                placeholder="Partenaire impliqué"
                                                                value="{{ $partenaire['partenaire'] }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-4">
                                                        <div class="form-group row">
                                                            <label class="col-sm-4 control-label"
                                                                for="type_partenariat">Type de
                                                                partenariat:</label>
                                                            <select id="type_partenariat-{{ $index }}"
                                                                class="form-control"
                                                                name="partenaires[{{ $index }}][type_partenaire]">
                                                                <option value="">Selectionner une option</option>
                                                                <option value="Technique"
                                                                    {{ $partenaire['type_partenaire'] == 'Technique' ? 'selected' : '' }}>
                                                                    Technique</option>
                                                                <option value="Financier"
                                                                    {{ $partenaire['type_partenaire'] == 'Financier' ? 'selected' : '' }}>
                                                                    Financier</option>
                                                                <option value="Technique et Financier"
                                                                    {{ $partenaire['type_partenaire'] == 'Technique et Financier' ? 'selected' : '' }}>
                                                                    Technique et Financier</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-4">
                                                        <div class="form-group row">
                                                            <label class="col-sm-4 control-label"
                                                                for="montant_contribution-">Montant de la
                                                                contribution:</label>
                                                            <input type="text"
                                                                id="montant_contribution-{{ $index }}"
                                                                class="form-control"
                                                                name="partenaires[{{ $index }}][montant_contribution]"
                                                                value="{{ $partenaire['montant'] }}"
                                                                placeholder="Montant de la contribution">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-8">
                                                        <button type="button" id="{{ $index }}"
                                                            class="removeRowPartenaire btn btn-danger btn-sm"><i
                                                                class="fa fa-minus"></i></button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot style="background: #e3e3e3;">
                                    <tr>

                                        <td colspan="3">
                                            <button id="addRowPartenaire" type="button"
                                                class="btn btn-success btn-sm"><i class="fa fa-plus"></i></button>
                                        </td>
                                    <tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 control-label" for="photos">Photos:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input type="file" id="photos" class="form-control dropify-fr" name="photos[]"
                                accept="image/*" multiple>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 control-label" for="documents_joints">Documents joints:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input type="file" id="documents_joints" class="form-control dropify-fr"
                                name="documents_joints[]" multiple>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 control-label" for="commentaires">Commentaires:</label>
                        <div class="col-xs-12 col-sm-8">
                            <textarea id="commentaires" class="form-control" name="commentaires" rows="4"> {{ $actionSociale->commentaires }} </textarea>
                        </div>
                    </div>

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
    <x-back route="{{ route('manager.communaute.action.sociale.index') }}" />
@endpush

@push('script')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/daterangepicker.css') }}">
    <script src="{{ asset('assets/vendor/jquery/daterangepicker.min.js') }}"></script>
    <script type="text/javascript">
        document.getElementById('niveau_realisation').addEventListener('change', function() {
            var dateFinProjet = document.getElementById('date_fin_projet');
            var dateDemarrage = document.getElementById('date_demarrage');
            var dateFinProjetContainer = document.getElementById('date_fin_projet_container');
            var dateDemarrageContainer = document.getElementById('date_demarrage_container');

            if (this.value === 'En Cours' || this.value === 'Achevé') {
                dateFinProjetContainer.style.display = 'block';
                dateDemarrageContainer.style.display = 'block';
                dateFinProjet.required = true; // Rend le champ requis
                dateDemarrage.required = true; // Rend le champ requis
            } else {
                dateFinProjetContainer.style.display = 'none';
                dateDemarrageContainer.style.display = 'none';
                dateFinProjet.required = false; // Rend le champ non requis
                dateDemarrage.required = false; // Rend le champ non requis
            }
        });

        $(document).ready(function() {
            //intrants lannee derniere
            var partenairesCount = $("#partenaire_area tr").length;

            $(document).on('click', '#addRowPartenaire', function() {

                var html_table = '<tr>';
                html_table +=
                    '<td class="row"><div class="col-xs-12 col-sm-12 bg-success"><badge class="btn  btn-outline--warning h-45 btn-sm">Partenaire impliqué ' +
                    partenairesCount +
                    '</badge></div><div class="col-xs-12 col-sm-4 pr-0"><div class="form-group"><label class="col-sm-4 control-label" for="partenaire">Partenaire impliqué:</label><input type="text" id="partenaire-' +
                    partenairesCount + '" class="form-control"name="partenaires[' + partenairesCount +
                    '][partenaire]" placeholder="Partenaire impliqué"></div></div> <div class="col-xs-12 col-sm-4"><div class="form-group row pr-0"><label class="col-sm-4 control-label" for="type_partenariat">Type de partenariat:</label> <select id="type_partenariat-' +
                    partenairesCount + '" class="form-control" name="partenaires[' + partenairesCount +
                    '][type_partenaire]"><option value="">Selectionner une option</option><option value="Technique">Technique</option><option value="Financier">Financier</option><option value="Technique et Financier">Technique et Financier</option></select></div></div><div class="col-xs-12 col-sm-4 pr-0"><div class="form-group"> <label class="col-sm-4 control-label" for="montant_contribution-1">Montant de la contribution:</label><input type="text" id="montant_contribution-' +
                    partenairesCount + '" class="form-control" name="partenaires[' + partenairesCount +
                    '][montant_contribution]" placeholder="Montant de la contribution"></div></div><div class="col-xs-12 col-sm-8"><button type="button" id="' +
                    partenairesCount +
                    '" class="removeRowPartenaire btn btn-danger btn-sm"><i class="fa fa-minus"></i></button></td>';
                html_table += '</tr>';
                //---> End create table tr

                partenairesCount = parseInt(partenairesCount) + 1;
                $('#partenaire_area').append(html_table);
            });

            $(document).on('click', '.removeRowPartenaire', function() {
                var row_id = $(this).attr('id');
                if (row_id == $("#partenaire_area tr").length - 1) {
                    $(this).parents('tr').remove();
                    partenairesCount = parseInt(partenairesCount) - 1;
                }
            });

        });
    </script>
@endpush
