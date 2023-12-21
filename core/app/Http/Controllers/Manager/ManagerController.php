<?php

namespace App\Http\Controllers\Manager;

use App\Models\User;
use App\Models\Language;
use App\Constants\Status;
use App\Models\Producteur;
use App\Models\Cooperative;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\LivraisonInfo;
use App\Models\SupportMessage; 
use App\Rules\FileTypeValidate;
use App\Models\LivraisonPayment; 
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\FormationStaff;
use App\Models\Parcelle;
use App\Models\SuiviFormation;
use App\Models\TypeFormationTheme;
use ArielMejiaDev\LarapexCharts\Facades\LarapexChart;
use Illuminate\Support\Facades\Hash;   
use ArielMejiaDev\LarapexCharts\PieChart;
class ManagerController extends Controller
{

    public function dashboard()
    {
        $manager = auth()->user();
        $pageTitle = "Manager Dashboard";  
        $nbcoop = Cooperative::count();
        $nbparcelle = Parcelle::count();
        $genre = Producteur::select('sexe',DB::raw('count(id) as nombre'))->groupBy('sexe')->get();
        $parcelle = Parcelle::select('typedeclaration',DB::raw('count(id) as nombre'))->groupBy('typedeclaration')->get();
     

        $prodbysexe= LarapexChart::setType('donut')
                            ->setTitle('Producteurs par Genre')
                            ->setDataset(Arr::pluck($genre,'nombre'))
                            ->setLabels(Arr::pluck($genre,'sexe'))
                            ->setDatalabels();
       
       $mapping= LarapexChart::setType('pie')
                            ->setTitle('Mapping des parcelles')
                            ->setDataset(Arr::pluck($parcelle,'nombre'))
                            ->setLabels(Arr::pluck($parcelle,'typedeclaration'))
                            ->setDatalabels();

        $producteurbydays = Producteur::select(DB::raw('DATE_FORMAT(created_at,"%Y-%m-%d") as date'),DB::raw('count(id) as nombre'))->groupBy('date')->get(); 
        $producteurbydays = LarapexChart::setType('area')
                    ->setTitle('Producteurs par Date') 
                    ->setDataset([
                        [
                        'name'=>'Nombre de producteurs', 
                        'data'=> Arr::pluck($producteurbydays,'nombre')
                        ]
                        ]) 
                    ->setXAxis(Arr::pluck($producteurbydays,'date'))
                    ->setDatalabels();
        
        $formation = TypeFormationTheme::joinRelationship('typeFormation')->select('type_formations.nom',DB::raw('count(type_formation_themes.id) as nombre'))->groupBy('type_formation_id')->get();
        $formationbymodule = LarapexChart::setType('bar')
                    ->setTitle('Formations par Modules') 
                    ->setDataset([
                        [
                        'name'=>'Nombre de producteurs', 
                        'data'=> Arr::pluck($formation,'nombre')
                        ]
                        ])  
                    ->setXAxis(Arr::pluck($formation,'nom'))
                    ->setDatalabels();

        $parcelle = Parcelle::select('typedeclaration',DB::raw('count(id) as nombre'))->groupBy('typedeclaration')->get();

        
        return view('manager.dashboard', compact('pageTitle','nbcoop','nbparcelle','prodbysexe','mapping','producteurbydays','formationbymodule'));
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
