<?php

namespace App\Http\Controllers\Admin;

use App\Models\Localite;
use App\Models\Section;
use App\Models\Parcelle;
use App\Models\Producteur;
use App\Models\Cooperative;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ParcelleController extends Controller
{

    public function mapping()
    {
        $pageTitle      = "Gestion de mapping des parcelles";

        $cooperatives = Cooperative::get();

        $sections = Section::when(request()->cooperative, function ($query, $cooperative) {
                            $query->where('cooperative_id', $cooperative);
                        })
                        ->with('cooperative')
                        ->get();

        $localites = Localite::joinRelationship('section')
            ->when(request()->cooperative, function ($query, $cooperative) {
                $query->where('cooperative_id', $cooperative);
            })
            ->when(request()->section, function ($query, $section) {
                $query->where('section_id', $section);
            })
            ->get();
        $producteurs = Producteur::joinRelationship('localite.section')
            ->when(request()->cooperative, function ($query, $cooperative) {
                $query->where('cooperative_id', $cooperative);
            })
            ->when(request()->localite, function ($query, $localite) {
                $query->where('localite_id', $localite);
            })
            ->get();
        $parcelles = Parcelle::dateFilter()->latest('id')
            ->joinRelationship('producteur.localite.section')
            ->when(request()->cooperative, function ($query, $cooperative) {
                $query->where('cooperative_id', $cooperative);
            })
            ->when(request()->section, function ($query, $section) {
                $query->where('section_id', $section);
            })
            ->when(request()->localite, function ($query, $localite) {
                $query->where('localite_id', $localite);
            })
            ->when(request()->producteur, function ($query, $producteur) {
                $query->where('producteur_id', $producteur);
            })
            ->with(['producteur.localite.section']) // Charger les relations nécessaires
            ->get();

        return view('admin.parcelle.mapping', compact('pageTitle','cooperatives', 'sections', 'parcelles', 'localites', 'producteurs'));
    }
    public function mappingPolygone(Request $request)
    {

        $cooperatives = Cooperative::get();

        $sections = Section::when(request()->cooperative, function ($query, $cooperative) {
            $query->where('cooperative_id', $cooperative);
        })->with('cooperative')->get();

        $localites = Localite::joinRelationship('section')
            ->when(request()->cooperative, function ($query, $cooperative) {
                $query->where('cooperative_id', $cooperative);
            })
            ->when(request()->section, function ($query, $section) {
                $query->where('section_id', $section);
            })
            ->get();
        $producteurs = Producteur::joinRelationship('localite.section')
            ->when(request()->cooperative, function ($query, $cooperative) {
                $query->where('cooperative_id', $cooperative);
            })
            ->when(request()->localite, function ($query, $localite) {
                $query->where('localite_id', $localite);
            })
            ->get();
        $parcelles = Parcelle::dateFilter()->latest('id')
            ->joinRelationship('producteur.localite.section')
            ->when(request()->cooperative, function ($query, $cooperative) {
                $query->where('cooperative_id', $cooperative);
            })
            ->whereNotNull('waypoints')
            ->when(request()->section, function ($query, $section) {
                $query->where('section_id', $section);
            })
            ->when(request()->localite, function ($query, $localite) {
                $query->where('localite_id', $localite);
            })
            ->when(request()->producteur, function ($query, $producteur) {
                $query->where('producteur_id', $producteur);
            })
            ->with(['producteur.localite.section'])
            ->get();
        $total = count($parcelles);
        $pageTitle  = "Gestion de mapping des parcelles($total)";
                    // Téléchargement du fichier KML

                    if($request->has('download')) {
                        $datakml = '';
                        if ($request->download == 'kml') {
                            if ($total>=1) {
                                $kml_file_path =  base_path('mappingparcelle.kml');
                                if (file_exists($kml_file_path)) {
                                    unlink($kml_file_path);
                                }

                                $datakml .= '<?xml version="1.0" encoding="utf-8" ?>
        <kml xmlns="http://www.opengis.net/kml/2.2">
        <Document id="root_doc">
        <Schema name="programmeband_1" id="scpcct_1">
            <SimpleField name="N°" type="float"></SimpleField>
            <SimpleField name="Cooperative" type="string"></SimpleField>
            <SimpleField name="Code_CCC" type="string"></SimpleField>
            <SimpleField name="Code_Producteur" type="string"></SimpleField>
            <SimpleField name="Code_Parcelle" type="string"></SimpleField>
            <SimpleField name="Section" type="string"></SimpleField>
            <SimpleField name="Localite" type="string"></SimpleField>
            <SimpleField name="Sous-Prefecture" type="string"></SimpleField>
            <SimpleField name="Departement" type="string"></SimpleField>
            <SimpleField name="Region " type="string"></SimpleField>
            <SimpleField name="Nom" type="string"></SimpleField>
            <SimpleField name="Prenoms" type="string"></SimpleField>
            <SimpleField name="Genre" type="string"></SimpleField>
            <SimpleField name="Certification" type="string"></SimpleField>
            <SimpleField name="Programme" type="string"></SimpleField>
            <SimpleField name="Statut" type="string"></SimpleField>
            <SimpleField name="Field17" type="string"></SimpleField>
        </Schema>
        <Folder><name>programmeband_1</name>
        ';
        $i=1;
        foreach($parcelles as $data)
        {
            $lat = isset($data->latitude) ? htmlentities($data->latitude, ENT_QUOTES | ENT_IGNORE, "UTF-8") : 'Non Disponible';
            $long= isset($data->longitude) ? htmlentities($data->longitude, ENT_QUOTES | ENT_IGNORE, "UTF-8") : 'Non Disponible';
            $producteur = isset($data->producteur->nom) ? htmlentities(stripslashes($data->producteur->nom), ENT_QUOTES | ENT_IGNORE, "UTF-8").' '.htmlentities(stripslashes($data->producteur->prenoms), ENT_QUOTES | ENT_IGNORE, "UTF-8") : 'Non Disponible';
            $code= isset($data->producteur->codeProd) ? htmlentities($data->producteur->codeProd, ENT_QUOTES | ENT_IGNORE, "UTF-8") : 'Non defini';
            $parcelle = isset($data->codeParc) ? htmlentities($data->codeParc, ENT_QUOTES | ENT_IGNORE, "UTF-8") : 'Non Disponible';
            $localite=isset($data->producteur->localite->nom) ? htmlentities($data->producteur->localite->nom, ENT_QUOTES | ENT_IGNORE, "UTF-8") : 'Non Disponible';
            $section=isset($data->producteur->localite->section->libelle) ? htmlentities($data->producteur->localite->section->libelle, ENT_QUOTES | ENT_IGNORE, "UTF-8") : 'Non Disponible';
            $cooperative=isset($data->producteur->localite->section->cooperative->name) ? htmlentities($data->producteur->localite->section->cooperative->name, ENT_QUOTES | ENT_IGNORE, "UTF-8") : 'Non Disponible';
            $annee = isset($data->anneeCreation) ? htmlentities($data->anneeCreation, ENT_QUOTES | ENT_IGNORE, "UTF-8") : 'Non Disponible';
            $culture= isset($data->culture) ? htmlentities($data->culture, ENT_QUOTES | ENT_IGNORE, "UTF-8") : 'Non Disponible';
            $superficie= isset($data->superficie) ? htmlentities($data->superficie, ENT_QUOTES | ENT_IGNORE, "UTF-8") : 'Non Disponible';
            $proprietaire = "Coopérative: $cooperative \n\n Section: $section \n\n Localite: $localite \n\n Producteur : $producteur \n\n Code producteur: $code \n\n Code Parcelle: $parcelle \n\n Année creation: $annee \n\n Latitude: $lat \n\n Longitude: $long \n\n Superficie: $superficie ha";

            $datakml .= '<Placemark>
            <name>'.$proprietaire.'</name>
            <Style><LineStyle><color>ff0000ff</color></LineStyle><PolyStyle><fill>0</fill></PolyStyle></Style>
            <ExtendedData><SchemaData schemaUrl="#programmeband_1">
                <SimpleData name="N°">'.$i.'</SimpleData>
                <SimpleData name="Cooperative">'.$data->producteur->localite->section->cooperative->name.'</SimpleData>
                <SimpleData name="Code_CCC">'.$data->producteur->localite->section->cooperative->codeCoop.'</SimpleData>
                <SimpleData name="Code_Producteur">'.$data->producteur->codeProd.'</SimpleData>
                <SimpleData name="Code_Parcelle">'.$data->codeParc.'</SimpleData>
                <SimpleData name="Section">'.$data->producteur->localite->section->libelle.'</SimpleData>
                <SimpleData name="Localite">'.$data->producteur->localite->nom.'</SimpleData>
                <SimpleData name="Sous-Prefecture">'.$data->producteur->localite->section->sousPrefecture.'</SimpleData>
                <SimpleData name="Region ">'.$data->producteur->localite->section->region.'</SimpleData>
                <SimpleData name="Nom">'.$data->producteur->nom.'</SimpleData>
                <SimpleData name="Prenoms">'.$data->producteur->prenoms.'</SimpleData>
                <SimpleData name="Genre">'.$data->producteur->sexe.'</SimpleData>
                <SimpleData name="Programme">'.$data->producteur->programme->libelle.'</SimpleData>
                <SimpleData name="Departement">'.$data->producteur->localite->section->departement.'</SimpleData>
                <SimpleData name="Statut">'.$data->producteur->statut.'</SimpleData>
            </SchemaData>
            </ExtendedData>
              <MultiGeometry>
                <Polygon>
                    <outerBoundaryIs>
                        <LinearRing>
                            <coordinates>'.$data->waypoints.'</coordinates>
                        </LinearRing>
                    </outerBoundaryIs>
                </Polygon>
            </MultiGeometry>
          </Placemark>';
          $i++;
        }
        $datakml .= '</Folder>
        </Document></kml>';
                                file_put_contents($kml_file_path, $datakml);
                                $headers = ['Content-Type: application/kml'];
                                $fileName = time() . '.kml';
                                return response()->download($kml_file_path, $fileName, $headers);
                            }
                        }

                    }
        return view('admin.parcelle.mapping-trace', compact('pageTitle','cooperatives', 'sections', 'parcelles', 'localites', 'producteurs'));
    }

}
