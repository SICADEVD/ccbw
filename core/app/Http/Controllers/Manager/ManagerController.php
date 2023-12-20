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
use App\Charts\ProducteurChart;
use App\Rules\FileTypeValidate;
use App\Models\LivraisonPayment;
use App\Charts\MonthlyUsersChart;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;

class ManagerController extends Controller
{

    public function dashboard(MonthlyUsersChart $chart)
    {
        $manager            = auth()->user();
        $pageTitle          = "Manager Dashboard"; 
        
        $chart_options = [
            'chart_title' => 'Producteurs par Localite',
            'report_type' => 'group_by_relationship',
            'model' => 'App\Models\Producteur', 
            'relationship_name'=>'localite', 
            'group_by_field' => 'nom',
            'chart_type' => 'bar', 
            'chart_color'=>'120,169,2', 
        ];
        $chart1 = new LaravelChart($chart_options);

        $chart_options = [
            'chart_title' => 'Producteurs par Programme',
            'report_type' => 'group_by_relationship',
            'model' => 'App\Models\Producteur', 
            'relationship_name'=>'programme', 
            'group_by_field' => 'libelle',
            'chart_type' => 'pie',  
            
        ]; 
        $chart2 = new LaravelChart($chart_options);

        $chart_options  = [
            'chart_title' => 'Livraison par Date',
            'report_type' => 'group_by_date',
            'model' => 'App\Models\LivraisonInfo',   
            'group_by_field'=>'estimate_date',
            'chart_type' => 'line',  
            'group_by_period' => 'day',  
            
        ]; 
        $chart3 = new LaravelChart($chart_options);
        $chart_options = [
            'chart_title' => 'Produits par Livraison',
            'report_type' => 'group_by_date',
            'model' => 'App\Models\LivraisonProduct',   
            'group_by_field'=>'created_at',
            'filter_field' => 'created_at',
            'group_by_field_format' => 'Y-m-d',
            'chart_type' => 'bar',  
            'group_by_period' => 'day',  
        ];  
        $chart4 = new LaravelChart($chart_options);
        $totalproducteur = Producteur::count(); 
        $chart = $chart->build();
        return view('manager.dashboard', compact('pageTitle','chart', 'chart1', 'chart2','chart3','chart4','totalproducteur'));
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
