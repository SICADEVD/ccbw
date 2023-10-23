<?php
namespace App\Http\Controllers\Manager;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AccountBaseController;

use App\Http\Helpers\Reply;
use App\Models\Department;
use App\Models\Designation;
use App\Models\LeaveSetting;
use App\Models\LeaveType;
use App\Models\Team;
use Illuminate\Http\Request;

class LeaveSettingController extends AccountBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.leaveTypeSettings';
        $this->activeSettingMenu = 'leave_settings'; 
    }

    public function index()
    {
        $this->leaveTypes = LeaveType::all();

        $tab = request('tab');

        switch ($tab) {
        case 'general': 
            $this->view = 'leave-settings.ajax.general';
                break;
        default:
            $this->departments = Department::all();
            $this->designations = Designation::all();
            $this->view = 'leave-settings.ajax.type';
                break;
        }

        $this->activeTab = $tab ?: 'type';

        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle, 'activeTab' => $this->activeTab]);
        }

        return view('leave-settings.index', $this->data);
    }

    public function store(Request $request)
    {
        $setting = cooperative();
        $setting->leaves_start_from = $request->leaveCountFrom;
        $setting->year_starts_from = $request->yearStartFrom;
        $setting->save();

        return Reply::success(__('messages.updateSuccess'));
    }
 

}
