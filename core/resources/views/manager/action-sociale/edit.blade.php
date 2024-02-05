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
            <option value="Education">Education</option>
            <option value="Voirie">Voirie</option>
            <option value="Electricité">Electricité</option>
            <option value="Santé">Santé</option>
            <option value="Equipement rural">Equipement rural</option>
        </select></div>
                    </div> 
        
        <div class="form-group row">
            <label class="col-sm-4 control-label" for="titre_projet">Titre du projet:</label>
            <div class="col-xs-12 col-sm-8">
        <input type="text" id="titre_projet" class="form-control" name="titre_projet" required></div>
                    </div> 
        
        <div class="form-group row">
            <label class="col-sm-4 control-label" for="description_projet">Description du Projet:</label>
            <div class="col-xs-12 col-sm-8">
        <textarea id="description_projet" class="form-control" name="description_projet" rows="4" required></textarea></div>
                    </div> 
        
        <div class="form-group row">
            <label class="col-sm-4 control-label" for="beneficiaires_projet">Bénéficiaires du projet:</label>
            <div class="col-xs-12 col-sm-8">
        <input type="text" id="beneficiaires_projet" class="form-control" name="beneficiaires_projet" required></div>
                    </div> 
        
        <div class="form-group row">
            <label class="col-sm-4 control-label" for="niveau_realisation">Niveau de réalisation:</label>
            <div class="col-xs-12 col-sm-8">
        <select id="niveau_realisation" class="form-control" name="niveau_realisation" required>
            <option value="Non démarré">Non démarré</option>
            <option value="En Cours">En Cours</option>
            <option value="Achevé">Achevé</option>
        </select></div>
                    </div> 
        
        <div class="form-group row">
            <label class="col-sm-4 control-label" for="date_demarrage">Date de démarrage du projet:</label>
            <div class="col-xs-12 col-sm-8">
        <input type="date" id="date_demarrage" class="form-control" name="date_demarrage"></div>
                    </div> 
        
        <div id="date_fin_projet_container" style="display:none;">
            <div class="form-group row">
                <label class="col-sm-4 control-label" for="date_fin_projet">Date de fin du projet:</label>
                <div class="col-xs-12 col-sm-8">
            <input type="date" id="date_fin_projet" class="form-control" name="date_fin_projet"></div>
                    </div> 
        </div> 
        
        <div class="form-group row">
            <label class="col-sm-4 control-label" for="cout_projet">Coûts du projet:</label>
            <div class="col-xs-12 col-sm-8">
        <input type="text" id="cout_projet" class="form-control" name="cout_projet" required>
    </div>
                    </div> 
        
        <div class="form-group row">
            <label class="col-sm-4 control-label" for="partenaires_impliques">Partenaires impliquées:</label>
            <div class="col-xs-12 col-sm-8">
        <input type="text" id="partenaires_impliques" class="form-control" name="partenaires_impliques"></div>
                    </div> 
        
        <div class="form-group row">
            <label class="col-sm-4 control-label" for="type_partenariat">Type de partenariat:</label>
            <div class="col-xs-12 col-sm-8">
        <select id="type_partenariat" class="form-control" name="type_partenariat">
            <option value="Technique">Technique</option>
            <option value="Financier">Financier</option>
            <option value="Technique et Financier">Technique et Financier</option>
        </select></div>
                    </div> 
        
        <div class="form-group row">
            <label class="col-sm-4 control-label" for="montant_contribution">Montant de la contribution:</label>
            <div class="col-xs-12 col-sm-8">
        <input type="text" id="montant_contribution" class="form-control" name="montant_contribution"></div>
                    </div> 
        
        <div class="form-group row">
            <label class="col-sm-4 control-label" for="date_livraison">Date de la livraison:</label>
            <div class="col-xs-12 col-sm-8">
        <input type="date" id="date_livraison" class="form-control" name="date_livraison"></div>
                    </div> 
        
        <div class="form-group row">
            <label class="col-sm-4 control-label" for="photos">Photos:</label>
            <div class="col-xs-12 col-sm-8">
        <input type="file" id="photos" class="form-control" name="photos[]" accept="image/*" multiple></div>
                    </div> 
        
        <div class="form-group row">
            <label class="col-sm-4 control-label" for="documents_joints">Documents joints:</label>
            <div class="col-xs-12 col-sm-8">
        <input type="file" id="documents_joints" class="form-control" name="documents_joints[]" multiple></div>
                    </div> 
        
        <div class="form-group row">
            <label class="col-sm-4 control-label" for="commentaires">Commentaires:</label>
            <div class="col-xs-12 col-sm-8">
        <textarea id="commentaires" class="form-control" name="commentaires" rows="4"></textarea></div>
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
        var dateFinProjetContainer = document.getElementById('date_fin_projet_container');
        if (this.value === 'En Cours' || this.value === 'Achevé') {
            dateFinProjetContainer.style.display = 'block';
        } else {
            dateFinProjetContainer.style.display = 'none';
        }
    });
    </script>
@endpush
