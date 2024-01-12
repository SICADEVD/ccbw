<?php

namespace App\Http\Controllers\Manager;

use App\Models\User;
use App\Models\Language;
use App\Models\Parcelle;
use App\Constants\Status;
use App\Models\Producteur;
use App\Models\Cooperative;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\LivraisonInfo;
use App\Models\TypeFormation;
use App\Models\FormationStaff;
use App\Models\SuiviFormation;
use App\Models\SupportMessage; 
use App\Rules\FileTypeValidate;
use App\Models\LivraisonPayment; 
use App\Models\TypeFormationTheme;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;   
use App\Models\SuiviFormationProducteur;
use ArielMejiaDev\LarapexCharts\PieChart;
use ArielMejiaDev\LarapexCharts\Facades\LarapexChart;

class ManagerController extends Controller
{

    public function dashboard()
    {
        $manager = auth()->user();
        $pageTitle = "Manager Dashboard";  
        $nbcoop = Cooperative::count();
        $nbparcelle = Parcelle::count(); 
        //Producteurs par Genre
        $genre = Producteur::select('sexe',DB::raw('count(id) as nombre'))->groupBy('sexe')->get();
        
    
//Mapping des parcelles
    $parcelle = Parcelle::select('typedeclaration',DB::raw('count(id) as nombre'))->groupBy('typedeclaration')->get(); 

//Producteurs inscrits par Date
        $producteurbydays = Producteur::select(DB::raw('DATE_FORMAT(created_at,"%Y-%m-%d") as date'),DB::raw('count(id) as nombre'))->groupBy('date')->get();  

        // Formations par Modules
        $formation = TypeFormationTheme::joinRelationship('typeFormation')->select('type_formations.nom',DB::raw('count(type_formation_themes.id) as nombre'))->groupBy('type_formation_id')->get();
 
 
//Producteurs formés par Module
        $modules = DB::select('SELECT 
        tyf.nom AS module,
        p.sexe AS sexe_producteur,
        COUNT(sf.producteur_id) AS nombre_producteurs
    FROM 
        suivi_formation_producteurs sf
    INNER JOIN 
        suivi_formations s
        ON sf.suivi_formation_id = s.id
    INNER JOIN 
        type_formation_themes tf
        ON s.id = tf.suivi_formation_id
    INNER JOIN 
        type_formations tyf
        ON tyf.id = tf.type_formation_id
    INNER JOIN 
    producteurs p
    ON sf.producteur_id = p.id
    GROUP BY 
        tf.type_formation_id, p.sexe');
   
        // Nombre de parcelles
        $parcelles = Parcelle::select(DB::raw('DATE_FORMAT(created_at,"%Y-%m-%d") as date'),DB::raw('count(id) as nombre'))->groupBy('date')->get();
 
        return view('manager.dashboard', compact('pageTitle','nbcoop','nbparcelle','genre','parcelle','formation','modules'));
    }

    public function changeLanguage($lang = null)
    {
        $language = Language::where('code', $lang)->first();
        if (!$language) {
            $lang = 'fr';
        } 

        session()->put('lang', $lang);
     
        return back();
    }
    protected function livraisons($scope = null)
    {
        $user     = auth()->user();
        $livraisons = LivraisonInfo::where(function ($query) use ($user) {
            $query->where('sender_cooperative_id', $user->cooperative_id)->orWhere('receiver_cooperative_id', $user->cooperative_id);
        });
        if ($scope) {
            $livraisons = $livraisons->$scope();
        }
        $livraisons = $livraisons->dateFilter()->searchable(['code'])->with('cooperative','senderCooperative', 'receiverCooperative', 'senderStaff', 'receiverStaff', 'paymentInfo')->paginate(getPaginate());
        return $livraisons;
    }

    public function cooperativeList()
    {
        $pageTitle = "Liste des Cooperatives";
        $manager   = auth()->user();
        $cooperatives  = Cooperative::active()->where('id',$manager->cooperative_id)->searchable(['name', 'email', 'address'])->orderBy('name')->paginate(getPaginate());
        return view('manager.cooperative.index', compact('pageTitle', 'cooperatives'));
    }

    public function profile()
    {
        $pageTitle = "Manager Profile";
        $manager   = auth()->user();
        return view('manager.profile', compact('pageTitle', 'manager'));
    }

    public function ticketDelete($id)
    {
        $message = SupportMessage::findOrFail($id);
        $path    = getFilePath('ticket');

        if ($message->attachments()->count() > 0) {

            foreach ($message->attachments as $attachment) {
                fileManager()->removeFile($path . '/' . $attachment->attachment);
                $attachment->delete();
            }
        }

        $message->delete();
        $notify[] = ['success', "Support ticket deleted successfully"];
        return back()->withNotify($notify);
    }

    public function profileUpdate(Request $request)
    {
        $user = auth()->user();
        $this->validate($request, [
            'firstname' => 'required|max:40',
            'lastname'  => 'required|max:40',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'image'     => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ]);

        if ($request->hasFile('image')) {
            try {
                $old         = $user->image ?: null;
                $user->image = fileUploader($request->image, getFilePath('userProfile'), getFileSize('userProfile'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Impossible de télécharger votre image'];
                return back()->withNotify($notify);
            }
        }

        $user->firstname = $request->firstname;
        $user->lastname  = $request->lastname;
        $user->email     = $request->email;
        $user->save();
        $notify[] = ['success', 'Your profile added successfully'];
        return redirect()->route('manager.profile')->withNotify($notify);
    }

    public function password()
    {
        $pageTitle = 'Paramétrage du mot de passe';
        $user      = auth()->user();
        return view('manager.password', compact('pageTitle', 'user'));
    }

    public function passwordUpdate(Request $request)
    {

        $this->validate($request, [
            'old_password' => 'required',
            'password'     => 'required|min:5|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->old_password, $user->password)) {
            $notify[] = ['error', 'Password do not match !!'];
            return back()->withNotify($notify);
        }

        $user->password = Hash::make($request->password);
        $user->save();
        $notify[] = ['success', 'Le mot de passe a été changé avec succès'];
        return redirect()->route('manager.password')->withNotify($notify);
    }

    public function cooperativeIncome()
    {
        $user          = auth()->user();
        $pageTitle     = "Cooperative Income";
        $cooperativeIncomes = LivraisonPayment::where('cooperative_id', $user->cooperative_id)
            ->select(DB::raw("*,SUM(final_amount) as totalAmount"))
            ->groupBy('date')->orderby('id', 'DESC')->paginate(getPaginate());
        return view('manager.livraison.income', compact('pageTitle', 'cooperativeIncomes'));
    }
}
