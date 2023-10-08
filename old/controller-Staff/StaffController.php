<?php

namespace App\Http\Controllers\Staff;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Cooperative;
use App\Models\LivraisonInfo;
use App\Models\LivraisonPayment;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{

    public function dashboard()
    {
        $user            = auth()->user();
        $pageTitle       = "Staff Dashboard";
        $cooperativeCount     = Cooperative::active()->count();
        $cashCollection  = LivraisonPayment::where('receiver_id', $user->id)->where('status', Status::PAYE)->sum('final_amount');
        $dispatchLivraison = LivraisonInfo::dispatched()->count();
        $sentInQueue     = LivraisonInfo::queue()->count();
        $deliveryInQueue = LivraisonInfo::deliveryQueue()->count();
        $upcomingLivraison = LivraisonInfo::upcoming()->count();
        $totalSent       = LivraisonInfo::where('sender_staff_id', $user->id)->whereIn('status', [Status::COURIER_DISPATCH, Status::COURIER_DELIVERYQUEUE, Status::COURIER_DELIVERED])->count();
        $totalDelivery   = LivraisonInfo::where('receiver_staff_id', $user->id)->where('status', Status::COURIER_DELIVERED)->count();

        $livraisonDelivery = LivraisonInfo::upcoming()->orderBy('id', 'DESC')->with('senderCooperative', 'receiverCooperative', 'senderStaff', 'receiverStaff', 'paymentInfo')->take(5)->get();
        $totalLivraison    = LivraisonInfo::where('sender_cooperative_id', $user->cooperative_id)->orWhere('receiver_cooperative_id', $user->cooperative_id)->count();
        return view('staff.dashboard', compact('pageTitle', 'cooperativeCount', 'deliveryInQueue', 'totalSent', 'upcomingLivraison', 'sentInQueue', 'dispatchLivraison', 'cashCollection', 'totalDelivery', 'livraisonDelivery', 'totalLivraison'));
    }

    public function cooperativeList()
    {
        $pageTitle = "Liste des coopératives";
        $manager   = auth()->user();
        $cooperatives  = Cooperative::active()->where('id',$manager->cooperative_id)->searchable(['name', 'email', 'address'])->active()->orderBy('name', 'ASC')->paginate(getPaginate());
        return view('staff.cooperative.index', compact('pageTitle', 'cooperatives'));
    }


    public function profile()
    {
        $pageTitle = "Staff Profile";
        $staff     = auth()->user();
        return view('staff.profile', compact('pageTitle', 'staff'));
    }

    public function profileUpdate(Request $request)
    {
        $user = auth()->user();
        $this->validate($request, [
            'firstname' => 'required|max:40',
            'lastname'  => 'required|max:40',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'image'     => ['nullable', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
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
        $notify[] = ['success', 'Your profile updated successfully.'];
        return redirect()->route('staff.profile')->withNotify($notify);
    }

    public function password()
    {
        $pageTitle = 'Paramétrage du mot de passe';
        $user      = auth()->user();
        return view('staff.password', compact('pageTitle', 'user'));
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
        $notify[] = ['success', 'Le mot de passe a été changé avec succès.'];
        return redirect()->route('staff.password')->withNotify($notify);
    }
}
