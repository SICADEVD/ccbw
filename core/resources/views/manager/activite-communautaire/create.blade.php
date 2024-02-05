@extends('manager.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body">
                    {!! Form::open([
                        'route' => ['manager.communaute.activite.communautaire.store'],
                        'method' => 'POST',
                        'class' => 'form-horizontal',
                        'id' => 'flocal',
                        'enctype' => 'multipart/form-data',
                    ]) !!}
 
                    <div class="form-group row">
            <label class="col-sm-4 control-label" for="localite_projet">Localité:</label>
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
            <label class="col-sm-4 control-label" for="type_projet">Type de projet:</label>
            <div class="col-xs-12 col-sm-8">
        <select id="type_projet" class="form-control" name="type_projet" required>
            <option value="Autonomisation des femmes">Autonomisation des femmes</option>
            <option value="Nutrition familiale">Nutrition familiale</option>
            <option value="Diversification des sources de revenus">Diversification des sources de revenus</option>
            <option value="Education familiale">Education familiale</option>
            <option value="Santé">Santé</option>
            <option value="Education financière">Education financière</option>
        </select></div>
        </div>
        
        <div class="form-group row">
            <label class="col-sm-4 control-label" for="localite_projet">Localité:</label>
            <div class="col-xs-12 col-sm-8">
        <input type="text" id="localite_projet" class="form-control" name="localite_projet" required></div>
        </div>
        
        <div class="form-group row">
            <label class="col-sm-4 control-label" for="beneficiaires_projet">Bénéficiaires du Projet:</label>
            <div class="col-xs-12 col-sm-8">
        <select id="beneficiaires_projet" class="form-control" name="beneficiaires_projet" required>
            <option value="Membres">Membres</option>
            <option value="Non - Membres">Non - Membres</option>
        </select></div>
        </div>
        
        <div class="form-group row">
            <label class="col-sm-4 control-label" for="liste_beneficiaires">Liste des bénéficiaires:</label>
            <div class="col-xs-12 col-sm-8">
        <textarea id="liste_beneficiaires" class="form-control" name="liste_beneficiaires" rows="4"></textarea></div>
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
        <input type="text" id="cout_projet" class="form-control" name="cout_projet" required></div>
        </div>
        
        <div class="form-group row">
            <label class="col-sm-4 control-label" for="date_livraison">Date de la livraison:</label>
            <div class="col-xs-12 col-sm-8">
        <input type="date" id="date_livraison" class="form-control" name="date_livraison"></div>
        </div>
        
        <div class="form-group row">
            <label class="col-sm-4 control-label" for="photos">Photos:</label>
            <div class="col-xs-12 col-sm-8">
        <input type="file" id="photos" class="form-control dropify-fr" name="photos[]" accept="image/*" multiple></div>
        </div>
        
        <div class="form-group row">
            <label class="col-sm-4 control-label" for="documents_joints">Documents joints:</label>
            <div class="col-xs-12 col-sm-8">
        <input type="file" id="documents_joints" class="form-control dropify-fr" name="documents_joints[]" multiple></div>
        </div>
        
        <div class="form-group row">
            <label class="col-sm-4 control-label" for="commentaires">Commentaires:</label>
            <div class="col-xs-12 col-sm-8">
        <textarea id="commentaires" class="form-control" name="commentaires" rows="4"></textarea></div>
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
    <x-back route="{{ route('manager.communaute.activite.communautaire.index') }}" />
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

//     $('input:file').on('change', function(){
//     allFiles = $(this)[0].files;
//     for(var i = 0; allFiles.length > i; i++){
//         var eachFile = allFiles[i],
//         fileData = new FormData();
//         fileData.append('file', eachFile);
//         $.ajax({
//             url: link,
//             type: "POST",
//             datatype:'script',
//             data: fileData,
//             contentType: false,
//             processData:false,
//             success: function(result){
//                 console.log(result);
//             }
//         })
//     }
// })
    </script>
@endpush
