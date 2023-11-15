<?php
namespace App\Http\Controllers\Manager;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\Cooperative;
use App\Models\Localite;
use App\Models\Producteur; 
use App\Models\Archivage;
use App\Models\TypeArchive;
use Maatwebsite\Excel\Facades\Excel; 
use Mpdf\Mpdf;
use Mpdf\Output\Destination; 
use Illuminate\Support\Facades\DB;

class ArchivageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
	
        $data=array();
        $manager   = auth()->user();
        
 
        $data['activePage'] ='archivages';
        $data['pageTitle'] = 'Gestion des Archives';
        $data['typearchives'] = TypeArchive::get();
        $data['archivages'] = Archivage::dateFilter()
            ->searchable(["titre", "resume", "document"])
            ->latest('id')
            ->joinRelationship('cooperative')
            ->where(function ($q) {
                if (request()->type_archive != null) {
                    $q->where('type_archive_id', request()->type_archive);
                } 
            })
            ->with('cooperative','typeArchive')
            ->where('archivages.cooperative_id', $manager->cooperative_id)
            ->paginate(getPaginate());

        return view('manager.archivages.index', $data);
    }

 
    public function export()
    {
      $filename='suivi-parcelles-'.gmdate('dmYhms').'.xlsx';

     return Excel::download(new SuiviParcelleExport, $filename);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
	
        $data=array(); 
        
            $data['type_archives'] = DB::table('type_archives')->pluck('nom','nom')->all();
        $data['activePage'] ='archivages';
        $data['pageTitle'] = "Création d'une archive";
        return view('manager.archivages.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
	
        $this->validate($request,[
            'document' => 'mimes:doc,docx,xlsx,xls,pdf,ppt,pptx|max:4048',
           ]);

        $input = $request->all();

        $titre = Str::slug($input['titre'],'-');
        if($request->document){
          $fileName = $titre.'.'.$request->document->extension();
          $document = $request->file('document')->move('storage/app/archivages',$fileName);

          $input['document'] = "archivages/$fileName";
        }

        if(isset($input['content'])){
           //create PDF
        $mpdf = new Mpdf();
 
        //write content
        $mpdf->WriteHTML($request->get('content'));

        //return the PDF for download
        $input['document'] = "archivages/$titre.pdf";
        $location = "storage/app/archivages/";
       $mpdf->Output($location.$titre . '.pdf', Destination::FILE);
        }

      $input['userid'] = Auth::user()->id;
        $archives = Archivage::create($input);
         return redirect()->route('manager.archivages.index')
                      ->with('success','Le fichier d\'archivages a été crée avec succès.');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
	
      $data=array();
      $data['archivages'] = Archivage::select('archivages.*','p.nom as nomProd','p.prenoms','p.codeProdapp','l.nom as nomLocal','l.codeLocal','c.nom as nomCoop','c.codeCoop')
                ->join('parcelles as pa','archivages.parcelles_id','=','pa.id')
                ->join('producteurs as p','pa.producteurs_id','=','p.id')
                ->join('localites as l','p.localites_id','=','l.id')
                ->join('cooperatives as c','l.cooperatives_id','=','c.id')
                ->find($id);
         $data['pageTitle'] = 'FieldConnect | Details de suivi des applications';
      return view('archivages.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
	
        $data=array();
        $data['archivages'] = Archivage::select('archivages.*')
            ->find($id);

        if(Auth::user()->cooperatives_id){

            $data['producteurs'] = Producteur::select('id','nom','prenoms','codeProd','localites_id')->where('localites_id',$data['archivages']->localites_id)->get();
            $rolelocalites = $this->getAllIdLocalitesUsers(Auth::user()->id);
            $rolelocalites=explode(',',$rolelocalites);
          $data['localites'] = Localite::whereIn('id', $rolelocalites)->pluck('nom','id')->all();
            $data['cooperatives'] = Cooperative::where('id', Auth::user()->cooperatives_id)->pluck('nom','id')->all();


            }else{
                $data['producteurs'] = Producteur::select('id','nom','prenoms','codeProdapp','localites_id')->where('localites_id',$data['archivages']->localites_id)->get();
                $data['localites'] = Localite::pluck('nom','id')->all();
                $data['cooperatives'] = Cooperative::pluck('nom','id')->all();
            }

            $data['type_programmes'] = DB::table('type_programmes')->pluck('nom','nom')->all();
            $data['type_archives'] = DB::table('type_archives')->pluck('nom','nom')->all();
            $data['domaine_archivages'] = DB::table('domaine_archivages')->pluck('nom','nom')->all();

            $data['activePage'] ='archivages';
            $data['pageTitle'] = 'FieldConnect | Modification de suivi des applications';
        return view('archivages.edit', $data);
    }

    public function getAllIdLocalitesUsers($id){

        $contents='';
        $coop = DB::table('roles_localites')->join('localites as l','roles_localites.localites_id','=','l.id')->select('l.id')->where('users_id',$id)->get();
            if(count($coop)){
              foreach($coop as $data){
                $nom[] = $data->id;
              }
                $contents = implode(',',$nom);
            }

        return $contents;
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
	
        $this->validate($request,[
            'document' => 'mimes:doc,docx,xlsx,xls,pdf,ppt,pptx|max:4048',
           ]);

        $input = $request->all();
        $archivages = Archivage::find($id);

        // if($request->document){
        //     $document = $request->file('document')->store('archivages');
        //     $input['document'] = $document;
        //   }
        $titre = Str::slug($input['titre'],'-');
        if($request->document){
          $fileName = $titre.'.'.$request->document->extension();
          $document = $request->file('document')->move('storage/app/archivages',$fileName);

          $input['document'] = "archivages/$fileName";
        }
        $archivages->update($input);
        return redirect()->route('archivages.index')
                        ->with('success','Le fichier d\'archivages a été mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
	
      Archivage::find($id)->delete();

        return redirect()->route('archivages.index')
                        ->with('success','Le fichier d\'archivages a été supprimée avec succès.');
    }

    public function destroyFinally($id)
    {
	

        DB::table("archivages")->where('id',$id)->delete();
        return redirect()->back()->with('success','Archivage a été supprimé définitivement avec succès.');
    }
    public function restore($id)
    {
	
      Archivage::withTrashed()->find($id)->restore();

        return redirect()->back();
    }

    public function restoreAll()
    {
	
      Archivage::onlyTrashed()->restore();

        return redirect()->back();
    }
}
