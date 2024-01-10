@extends('manager.layouts.app')
@section('panel')
<?php 
use Illuminate\Support\Arr; 
use App\Models\Certification;
use App\Models\Programme;

?>
    <div class="row mb-none-30">
        <div class="row">
            <div class="col-md-6">
            <h3>Evalution des membres</h3>
            
                @for($i=gmdate('Y');$i>=2023;$i--)

                <?php
                $date = "01/01/$i-12/31/$i"; 
        $certifications = Certification::get(); 
        $programmes = Programme::get();
        $producteurs = getproducteur($date);  
       $sexe = array_count_values(Arr::pluck($producteurs,'sexe'));
        
                ?>
                <table class="table table-striped table-bordered">
                    
                    <thead class="bg--primary ">
                        <tr>
                            <th></th>
                            <th class="text-white text-center">{{$i}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center" colspan="2">Membre-Adhérent</td>
                        </tr>
                        <tr>
                            <td>Homme</td>
                            <td  class="text-center">{{$sexe['Homme']}}</td>
                        </tr>
                        <tr>
                            <td>Femmes</td>
                            <td  class="text-center">{{ $sexe['Femme'] }}</td>
                        </tr>
                        <tr>
                            <td>Total</td>
                            <td  class="text-center">{{ array_sum(array_values($sexe))}}</td>
                        </tr>
                        <tr>
                            <td class="text-center" colspan="2">Répartition des membres par programme </td>
                        </tr> 
                        @foreach($programmes as $res)
                        <tr>
                            <td class="text-center bg--danger" colspan="2">{{ $res->libelle}}</td>
                        </tr>
                        @foreach($certifications as $data)
                        <?php
                        $programmecert = getProducteurProgramme($date, $data->nom, $res->id); 
                        ?>
                        @if($programmecert->count())
                        <?php $sexe = array_count_values(Arr::pluck($programmecert,'sexe')); ?>
                        <tr>
                            <td><span class="badge badge-info">Certifiés {{ $data->nom}}</span></td>
                            <td> </td>
                        </tr>
                        <tr>
                            <td>Homme</td>
                            <td class="text-center"> {{ @$sexe['Homme'] ? @$sexe['Homme'] : 0 }} </td>
                        </tr>
                        <tr>
                            <td>Femmes</td>
                            <td class="text-center"> {{ @$sexe['Femme'] ? @$sexe['Femme'] : 0 }} </td>
                        </tr> 
                        <tr>
                            <td>Total</td>
                            <td class="text-center"> {{ array_sum(array_values($sexe))}} </td>
                        </tr>
                        @endif
                        @endforeach
                        @endforeach
                         
                        <tr>
                            <td class="text-center" colspan="2">Membres Ordinaire</td>
                        </tr>
                        <?php 
                         $producteursOrdinaires =  getproducteurOrdinaire($date);
                         $sexe = array_count_values(Arr::pluck($producteursOrdinaires,'sexe'));
                        ?>
                         <tr>
                            <td>Homme</td>
                            <td>{{ @$sexe['Homme'] ? @$sexe['Homme'] : 0 }} </td>
                        </tr>
                        <tr>
                            <td>Femme</td>
                            <td>{{ @$sexe['Femme'] ? @$sexe['Femme'] : 0 }}</td>
                        </tr>
                        <tr>
                            <td>Total</td>
                            <td>{{ array_sum(array_values($sexe))}}</td>
                        </tr>
                    </tbody>
                </table>
                @endfor
            </div>
            <div class="col-md-6">
            <h3>Evolution de la production des membres</h3>
            @for($a=gmdate('Y');$a>=2023;$a--)
                
            <?php 
            $date = "01/01/$a-12/31/$a"; 
            $parcelle = getparcelle($date);  
            $production = getproduction($date); 
            $productionOrdinaire = getproductionOrdinaire($date);
            $productionProgramme =getproductionProgramme($date);
            $productionVente = getvente($date);
            $productionVenteOrdinaire =getventeOrdinaire($date);
            $productionVenteProgramme =getventeProgramme($date);
            ?>
                    
                    <table class="table table-striped table-bordered">
                    
                    <thead class="bg--primary">
                        <tr>
                            <th class="text-white">Cacao</th>
                            <th class="text-white text-right">{{$a}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Superficie(Ha)</td>
                            <td> {{ @$parcelle ? @$parcelle : 0 }} </td>
                        </tr>
                        <tr>
                            <td>Production (Kg)</td>
                            <td> {{ @$production ? @$production : 0 }} </td>
                        </tr>
                        @foreach($certifications as $data)
                        <?php
                        $productioncert = getproductionCertifie($date, $data->nom); 
                        ?>
                        @if($productioncert)
                        <tr>
                            <td class="text-right">Certifié {{$data->nom}}</td>
                            <td>{{$productioncert}}</td>
                        </tr>
                        @endif
                        @endforeach
                         
                        <tr>
                            <td class="text-right">Programme Durabilité </td>
                            <td>{{ @$productionProgramme ? @$productionProgramme : 0 }}</td>
                        </tr>
                        <tr>
                            <td class="text-right">Conventionnel/Ordinaire</td>
                            <td>{{ @$productionOrdinaire ? @$productionOrdinaire : 0 }}</td>
                        </tr>
                        <tr>
                            <td>Vente (Kg)</td>
                            <td>{{ @$productionVente ? @$productionVente : 0 }}</td>
                        </tr>
                        @foreach($certifications as $data)
                        <?php
                        $ventecert = getventeCertifie($date, $data->nom); 
                        ?>
                        @if($ventecert)
                        <tr>
                            <td class="text-right">Certifié {{$data->nom}}</td>
                            <td>{{$ventecert}}</td>
                        </tr>
                        @endif
                        @endforeach
                          
                        <tr>
                            <td>Programme Durabilité </td>
                            <td>{{ @$productionVenteProgramme  ? @$productionVenteProgramme : 0 }}</td>
                        </tr>
                        <tr>
                            <td>Conventionnel/Ordinaire</td>
                            <td>{{ @$productionVenteOrdinaire ? @$productionVenteOrdinaire : 0 }}</td>
                        </tr>
                    </tbody>
                </table>
                @endfor
            </div>
            
        </div>
        <div class="row mt-4">
            <div class="col-md-6">
                {{-- <table>
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
                </table> --}}
            </div>
            <div class="col-md-6">
                {{-- <table>
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
                    </tbody>
                </table> --}}
            </div>
        </div>
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

        caption {
            font-size: 1.5em;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
@endpush
