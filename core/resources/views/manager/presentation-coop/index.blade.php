@extends('manager.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="row">
            <div class="col-md-6">
                <table>
                    <h3>Evalution des membres</h3>
                    <thead>
                        <tr>
                            <th></th>
                            <th>2023</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center" colspan="2">Membre-Adhérent</td>
                        </tr>
                        <tr>
                            <td>Homme</td>
                            <td>{{ $hommes }}</td>
                        </tr>
                        <tr>
                            <td>Femmes</td>
                            <td>{{ $femmes }}</td>
                        </tr>
                        <tr>
                            <td>Total</td>
                            <td>{{ $nombreProducteur }}</td>
                        </tr>
                        <tr>
                            <td class="text-center" colspan="2">Répartition des membres par programme </td>
                        </tr>
                        <tr>
                            <td>Membres Certifié RA </td>
                            <td> {{ $countProducteursRainforest }} </td>
                        </tr>
                        <tr>
                            <td>Homme</td>
                            <td> {{ $countProducteursRainforestHomme }} </td>
                        </tr>
                        <tr>
                            <td>Femmes</td>
                            <td> {{ $countProducteursRainforestFemme }} </td>
                        </tr>
                        <tr>
                            <td>Total</td>
                            <td> {{ $countProducteursRainforest }} </td>
                        </tr>
                        <tr>
                            <td>Membres Certifié FT </td>
                            <td> {{ $countProducteursFairtrade }} </td>
                        </tr>
                        <tr>
                            <td>Homme</td>
                            <td> {{ $countProducteursFairtradeHomme }} </td>

                        </tr>
                        <tr>
                            <td>Femmes</td>
                            <td> {{ $countProducteursFairtradeFemme }} </td>
                        </tr>
                        <tr>
                            <td>Total</td>
                            <td> {{ $countProducteursFairtrade }} </td>
                        </tr>
                        <tr>
                            <td>Membres Certifié BIO </td>
                            <td> {{ $countProducteursBio }} </td>
                        </tr>
                        <tr>
                            <td>Homme</td>
                            <td> {{ $countProducteursBioHomme }} </td>
                        </tr>
                        <tr>
                            <td>Femme</td>
                            <td> {{ $countProducteursBioFemme }} </td>
                        </tr>
                        <tr>
                            <td>Total</td>
                            <td>{{ $countProducteursBio }}</td>
                        </tr>
                        <tr>
                            <td class="text-center" colspan="2">Membres Autres Programmes Durabilité </td>
                        </tr>
                        <tr>
                            <td>Homme</td>
                            <td> {{ $hommesAutrePragramme }} </td>
                        </tr>
                        <tr>
                            <td>Femme</td>
                            <td> {{ $hommesAutrePragramme }} </td>
                        </tr>
                        <tr>
                            <td>Total</td>
                            <td> {{ $nombreProducteurAutreProgramme }} </td>
                        </tr>
                        <tr>
                            <td class="text-center" colspan="2">Membres Ordinaire</td>
                        </tr>
                        <tr>
                            <td>Homme</td>
                            <td>0</td>
                        </tr>
                        <tr>
                            <td>Femme</td>
                            <td>0</td>
                        </tr>
                        <tr>
                            <td>Total</td>
                            <td>0</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <table>
                    <h3>Evolution de la production des membres</h3>
                    <thead>
                        <tr>
                            <th>Cacao</th>
                            <th>2023</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Superficie(Ha)</td>
                            <td> {{ $sumSuperficie }} </td>
                        </tr>
                        <tr>
                            <td colspan="2">Production (Kg)</td>
                        </tr>
                        <tr>
                            <td>Certifié RA</td>
                            <td>Insertion des données ici</td>
                        </tr>
                        <tr>
                            <td>Certifié FT</td>
                            <td>Insertion des données ici</td>
                        </tr>
                        <tr>
                            <td>Certifié Bio</td>
                            <td>Insertion des données ici</td>
                        </tr>
                        <tr>
                            <td>Programme Durabilité </td>
                            <td>Insertion des données ici</td>
                        </tr>
                        <tr>
                            <td>Conventionnel/Ordinaire</td>
                            <td>Insertion des données ici</td>
                        </tr>
                        <tr>
                            <td colspan="3">Vente (Kg)</td>
                        </tr>
                        <tr>
                            <td>Certifié RA</td>
                            <td>Insertion des données ici</td>
                        </tr>
                        <tr>
                            <td>Certifié FT</td>
                            <td>Insertion des données ici</td>
                        </tr>
                        <tr>
                            <td>Certifié Bio</td>
                            <td>Insertion des données ici</td>
                        </tr>
                        <tr>
                            <td>Programme Durabilité </td>
                            <td>Insertion des données ici</td>
                        </tr>
                        <tr>
                            <td>Conventionnel/Ordinaire</td>
                            <td>Insertion des données ici</td>
                        </tr>
                        <tr>
                            <td>Chiffre d'affaire </td>
                            <td>Insertion des données ici</td>
                        </tr>
                    </tbody>
                </table>
                <table>
                    <h3>Evolution de la production des membres</h3>
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th colspan="3">2023</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Chiffre d'affaire(FCFA)</td>
                            <td class="noretourligne">Cacao</td>
                            <td>Insertion des données ici</td>
                        </tr>
                    </tbody>
                </table>
                <table>
                    <tbody>
                        <tr>
                            <td>Marge brute d'exploitation (FCFA)</td>
                            <td class="noretourligne">Cacao</td>
                            <td>Insertion des données ici</td>
                        </tr>
                    </tbody>
                </table>
                <table>
                    <tbody>
                        <tr>
                            <td>Résultat de l'exercice avant impôt (FCFA)</td>
                            <td class="noretourligne">Cacao</td>
                            <td>Insertion des données ici</td>
                        </tr>
                    </tbody>
                </table>
                <table>
                    <tbody>
                        <tr>
                            <td>Nombre de client</td>
                            <td>Insertion des données ici</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        {{-- <div class="row mt-4">
            <div class="col-md-6">
                <table>
                    <caption>Statistiques des Membres</caption>
                    <thead>
                        <tr>
                            <th></th>
                            <th>2023</th>
                            <th>2024</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Membres-Adhérent</td>
                            <td>Insertion des données ici</td>
                            <td>Insertion des données ici</td>
                        </tr>
                        <tr>
                            <td>Homme</td>
                            <td>Insertion des données ici</td>
                            <td>Insertion des données ici</td>
                        </tr>
                        <tr>
                            <td>Femme</td>
                            <td>Insertion des données ici</td>
                            <td>Insertion des données ici</td>
                        </tr>
                        <tr>
                            <td>Total</td>
                            <td>Insertion des données ici</td>
                            <td>Insertion des données ici</td>
                        </tr>
                        <tr>
                            <td>Répartition des membres par programme </td>
                            <td>Insertion d</td>
                            <td>Insertion des données ici</td>
                            <td>Insertion des données ici</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">

            </div>
        </div> --}}
        <x-confirmation-modal />
    </div>
@endsection

@push('style')
    <style type="text/css">
        table {
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #4caf50;
            color: #fff;
        }

        .noretourligne {
            white-space: nowrap;
        }
    </style>
@endpush
