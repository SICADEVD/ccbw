@extends('manager.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped table-bordered">

                        <tr>
                            <td>Section</td>
                            <td>
                                {{ @$section->libelle }}
                            </td>
                        </tr>

                        <tr>
                            <td>Localité</td>
                            <td>
                                {{ @$localite->nom }}

                            </td>
                        </tr>

                        <tr>
                            <td>Producteur</td>
                            <td>{{ @$producteur->nom }} {{ @$producteur->prenoms }}</td>
                        </tr>
                        <tr>
                            <td>Type de déclaration superficie</td>
                            <td>
                                {{ @$parcelle->typedeclaration }}
                            </td>
                        </tr>
                        <tr>
                            <td>Année de création de la parcelle </td>
                            <td>
                                {{ @$parcelle->anneeCreation }}
                            </td>
                        </tr>

                        <tr>
                            <td>L'âge moyen des cacaoyers</td>
                            <td>
                                {{ @$parcelle->ageMoyenCacao }}
                            </td>
                        </tr>
                        <tr>
                            <td>Est ce que la parcelle a été régenerée ?</td>
                            <td>
                                {{ @$parcelle->parcelleRegenerer }}
                            </td>
                        </tr>
                        @if (@$parcelle->parcelleRegenerer == 'oui')
                            <tr>
                                <td>Année de régénération</td>
                                <td>
                                    {{ @$parcelle->anneeRegenerer }}
                                </td>
                            </tr>
                            <tr>
                                <td>Superficie concernée</td>
                                <td>
                                    {{ @$parcelle->superficieConcerne }}
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td>Quel type de Document possèdes-tu ?</td>
                            <td>
                                {{ @$parcelle->typeDoc }}
                            </td>
                        </tr>
                        <tr>
                            <td>Ya-t-il Un Cour Ou Plan D’eau Dans La Parcelle ?</td>
                            <td>
                                {{ @$parcelle->presenceCourDeau }}
                            </td>
                        </tr>
                        @if (@$parcelle->presenceCourDeau == 'oui')
                            <tr>
                                <td>Quel est le cour ou plan d'eau</td>
                                <td>
                                    {{ @$parcelle->courDeau }}
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td>Est Ce qu'il existe des mésures de protection ?</td>
                            <td>
                                {{ @$parcelle->existeMesureProtection }}
                            </td>
                        </tr>
                        @if (@$parcelle->existeMesureProtection == 'oui')
                            <tr>
                                <td>Quelles sont les mesures de protection</td>
                                <td>{{ implode(' ,', @$parcelle->parcelleTypeProtections->pluck('typeProtection')->toArray()) }}
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td>Ya-t-il une pente dans la parcelle ?</td>
                            <td>
                                {{ @$parcelle->existePente }}
                            </td>
                        </tr>
                        @if (@$parcelle->existePente == 'oui')
                            <tr>
                                <td>Quel est le niveau de la pente ?</td>
                                <td>
                                    {{ @$parcelle->niveauPente }}
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td>Présence de signe d'érosion ?</td>
                            <td>
                                {{ @$parcelle->erosion }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: center;">Quels sont les arbres à Ombrages observés ?</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Arbre</th>
                                            <th>Nombre</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($arbres as $item)
                                            <tr>
                                                <td>{{ $item->agroespeceabre->nom }}</td>
                                                <td>{{ $item->nombre }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: center;">Information GPS de la parcelle</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Superficie</th>
                                            <th>Latitude</th>
                                            <th>Longitude</th>
                                            {{-- <th>Nombre de cacao moyen / parcelle</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{@$parcelle->superficie}}</td>
                                            <td>{{@$parcelle->latitude}}</td>
                                            <td>{{@$parcelle->longitude}}</td>
                                            {{-- <td>{{@$parcelle->superficie}}</td> --}}
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>Nombre De Cacao Moyen / Parcelle</td>
                            <td>{{@$parcelle->nbCacaoParHectare}}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('manager.traca.parcelle.index') }}" />
@endpush

@push('script')
@endpush
