<?php

/*
|--------------------------------------------------------------------------
| Register Namespaces And Routes
|--------------------------------------------------------------------------
|
| When a module starting, this file will executed automatically. This helps
| to register some namespaces like translator or view. Also this file
| will load the routes file for each module. You may also modify
| this file as you want.
|
*/

use App\Models\User;
use App\Http\Helpers\Files;
use App\Http\Controllers\FileController;
use App\Models\Cooperative;
use App\Models\Currency;
use App\Models\Permission;
use App\Scopes\ActiveScope;
use Illuminate\Support\Str;
use App\Models\ThemeSetting;
use App\Scopes\CooperativeScope;
use App\Models\InvoiceSetting;
use App\Models\StorageSetting;
use App\Models\UserPermission;
use App\Models\QuickBooksSetting;
use Illuminate\Support\Facades\DB;
use App\Models\CurrencyFormatSetting;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use App\Models\SuperAdmin\GlobalCurrency;
use App\Models\SuperAdmin\GlobalInvoiceSetting;

if (!function_exists('user')) {

    /**
     * Return current logged in user
     */
    function user()
    {
        if (session()->has('user')) {
            return session('user');
        }
 
        if (auth()->user() !=null ) {

            if (session()->has('cooperative')) {
                $user = User::where('id', auth()->user()->id)->where('status', 1)->first();
            }
            else {
                $user = DB::table('users')->where('id', auth()->user()->id)->where('status', 1)->first();
            }

            if ($user) {
                session(['user' => $user]);
                return session('user');
            }
            else {
                return auth()->user()->id;
            }
        }

        return null;
    }

}

if (!function_exists('user_roles')) {

    /**
     * Return current logged in user
     */
    // @codingStandardsIgnoreLine
    function user_roles()
    {
        if (session()->has('user_roles')) {
            return session('user_roles');
        }

        $user = user();

        if ($user) {
            if (!isset(user()->roles)) {
                session(['user' => User::find(user()->id)]);
            }

            $roles = user()->roles;
            session(['user_roles' => $roles->pluck('name')->toArray()]);
            session(['user_role_ids' => $roles->pluck('id')->toArray()]);

            return session('user_roles');
        }

        return null;
    }

}

if (!function_exists('getSubdomainSchema')) {

    function getSubdomainSchema()
    {

        if (!session()->has('subdomain_schema')) {
            if (\Illuminate\Support\Facades\Schema::hasTable('sub_domain_module_settings')) {
                $data = \Illuminate\Support\Facades\DB::table('sub_domain_module_settings')->first();
            }

            session(['subdomain_schema' => isset($data->schema) ? $data->schema : 'http']);
        }

        return session('subdomain_schema');
    }

}

if (!function_exists('superadmin_theme')) {

    // @codingStandardsIgnoreLine
    function superadmin_theme()
    {
        if (!session()->has('superadmin_theme')) {
            session(['superadmin_theme' => \App\Models\ThemeSetting::withoutGlobalScope(CooperativeScope::class)->where('panel', 'superadmin')->first()]);
        }

        return session('superadmin_theme');
    }

}

if (!function_exists('admin_theme')) {

    // @codingStandardsIgnoreLine
    function admin_theme()
    {
        if (!session()->has('admin_theme')) {
            if (superadmin_theme()->restrict_admin_theme_change) {
                session(['admin_theme' => superadmin_theme()]);

            } else {
                session(['admin_theme' => ThemeSetting::where('panel', 'admin')->first()]);
            }

        }

        return session('admin_theme');
    }

}

if (!function_exists('employee_theme')) {

    // @codingStandardsIgnoreLine
    function employee_theme()
    {
        if (!session()->has('employee_theme')) {
            if (superadmin_theme()->restrict_admin_theme_change) {
                session(['employee_theme' => superadmin_theme()]);

            } else {
                session(['employee_theme' => ThemeSetting::where('panel', 'employee')->first()]);
            }
        }

        return session('employee_theme');
    }

}

if (!function_exists('client_theme')) {

    // @codingStandardsIgnoreLine
    function client_theme()
    {
        if (!session()->has('client_theme')) {
            if (superadmin_theme()->restrict_admin_theme_change) {
                session(['client_theme' => superadmin_theme()]);

            } else {
                session(['client_theme' => ThemeSetting::where('panel', 'client')->first()]);
            }
        }

        return session('client_theme');
    }

}

if (!function_exists('global_setting')) {

    // @codingStandardsIgnoreLine
    function global_setting()
    {

        if (!cache()->has('global_setting')) {
            $setting = \App\Models\GlobalSetting::first();
            cache(['global_setting' => $setting]);

            return $setting;
        }

        return cache('global_setting');
    }

}

if (!function_exists('push_setting')) {

    // @codingStandardsIgnoreLine
    function push_setting()
    {
        if (!session()->has('push_setting')) {
            session(['push_setting' => \App\Models\PushNotificationSetting::first()]);
        }

        return session('push_setting');
    }

}

if (!function_exists('language_setting')) {

    // @codingStandardsIgnoreLine
    function language_setting()
    {
        if (!cache()->has('language_setting')) {
            cache(['language_setting' => \App\Models\LanguageSetting::where('status', 'enabled')->get()]);
        }

        return cache('language_setting');
    }

}

if (!function_exists('language_setting_locale')) {

    // @codingStandardsIgnoreLine
    function language_setting_locale($locale)
    {
        if (!cache()->has('language_setting_'.$locale)) {
            cache(['language_setting_'.$locale => \App\Models\LanguageSetting::where('language_code', $locale)->first()]);
        }

        return cache('language_setting_'.$locale);
    }

}

if (!function_exists('smtp_setting')) {

    // @codingStandardsIgnoreLine
    function smtp_setting()
    {
        if (!session()->has('smtp_setting')) {
            session(['smtp_setting' => \App\Models\SmtpSetting::first()]);
        }

        return session('smtp_setting');
    }

}

if (!function_exists('message_setting')) {

    // @codingStandardsIgnoreLine
    function message_setting()
    {
        if (!session()->has('message_setting')) {
            session(['message_setting' => \App\Models\MessageSetting::first()]);
        }

        return session('message_setting');
    }

}

if (!function_exists('storage_setting')) {

    // @codingStandardsIgnoreLine
    function storage_setting()
    {
        if (!session()->has('storage_setting')) {
            $setting = StorageSetting::where('status', 'enabled')->first();

            session(['storage_setting' => $setting]);
        }

        return session('storage_setting');
    }

}

if (!function_exists('email_notification_setting')) {

    // @codingStandardsIgnoreLine
    function email_notification_setting()
    {

        if (in_array('client', user_roles()) || in_array('employee', user_roles())) {
            if (!session()->has('email_notification_setting')) {
                session(['email_notification_setting' => \App\Models\EmailNotificationSetting::all()]);
            }
        }

        if (!session()->has('email_notification_setting')) {
            session(['email_notification_setting' => \App\Models\EmailNotificationSetting::all()]);
        }

        return session('email_notification_setting');
    }

}


if (!function_exists('asset_url')) {

    // @codingStandardsIgnoreLine
    function asset_url($path)
    {
        $path = \App\Helper\Files::UPLOAD_FOLDER . '/' . $path;
        $storageUrl = $path;

        if (!Str::startsWith($storageUrl, 'http')) {
            return url($storageUrl);
        }

        return $storageUrl;
    }

}

if (!function_exists('user_modules')) {

    // @codingStandardsIgnoreLine
    function user_modules()
    {
        $user = user();

        if (!$user) {
            return [];
        }

        // WORKSUITESAAS
        if (user()->is_superadmin) {
            return [];
        }

        if (cache()->has('user_modules_' . $user->id)) {
            return cache('user_modules_' . $user->id);
        }

        $module = \App\Models\ModuleSetting::where('is_allowed', 1);

        if (in_array('admin', user_roles())) {
            $module = $module->where('type', 'admin');

        }
        elseif (in_array('client', user_roles())) {
            $module = $module->where('type', 'client');

        }
        elseif (in_array('employee', user_roles())) {
            $module = $module->where('type', 'employee');
        }

        $module = $module->where('status', 'active');
        $module->select('module_name');

        $module = $module->get();
        $moduleArray = [];

        foreach ($module->toArray() as $item) {
            $moduleArray[] = array_values($item)[0];
        }

        cache()->put('user_modules_' . $user->id, $moduleArray);
        return $moduleArray;
    }

}

if (!function_exists('worksuite_plugins')) {

    // @codingStandardsIgnoreLine
    function worksuite_plugins()
    {

        if (!cache()->has('worksuite_plugins')) {
            $plugins = \Nwidart\Modules\Facades\Module::allEnabled();

            foreach ($plugins as $plugin) {
                Artisan::call('module:migrate', array($plugin, '--force' => true));
            }

            cache(['worksuite_plugins' => array_keys($plugins)]);
        }

        return cache('worksuite_plugins');
    }

}

if (!function_exists('pusher_settings')) {

    // @codingStandardsIgnoreLine
    function pusher_settings()
    {
        if (!session()->has('pusher_settings')) {
            session(['pusher_settings' => \App\Models\PusherSetting::first()]);
        }

        return session('pusher_settings');
    }

}


if (!function_exists('isSeedingData')) {

    /**
     * Check if app is seeding data
     * @return boolean
     */
    function isSeedingData()
    {
        // We set config(['app.seeding' => true]) at the beginning of each seeder. And check here
        return config('app.seeding');
    }

}

if (!function_exists('isRunningInConsoleOrSeeding')) {

    /**
     * Check if app is seeding data
     * @return boolean
     */
    function isRunningInConsoleOrSeeding()
    {
        // We set config(['app.seeding' => true]) at the beginning of each seeder. And check here
        return app()->runningInConsole() || isSeedingData();
    }

}

if (!function_exists('asset_url_local_s3')) {

    // @codingStandardsIgnoreLine
    function asset_url_local_s3($path, $appRoute = false, $type = 'file')
    {
        if (in_array(config('filesystems.default'), StorageSetting::S3_COMPATIBLE_STORAGE)) {
            if ($appRoute) {
                $filePath = FileController::encryptDecrypt($path);

                if ($type == 'image') {
                    $filePath = $filePath . '.png';
                }

                return route('file.getFile', ['type' => $type, 'path' => $filePath]);
            }

            return Storage::disk(config('filesystems.default'))->temporaryUrl($path, now()->addMinutes(StorageSetting::HASH_TEMP_FILE_TIME));
        }

        $path = Files::UPLOAD_FOLDER . '/' . $path;
        $storageUrl = $path;

        if (!Str::startsWith($storageUrl, 'http')) {
            return url($storageUrl);
        }

        return $storageUrl;
    }

}

if (!function_exists('download_local_s3')) {

    // @codingStandardsIgnoreLine
    function download_local_s3($file, $path)
    {

        if (in_array(config('filesystems.default'), StorageSetting::S3_COMPATIBLE_STORAGE)) {
            return Storage::disk(config('filesystems.default'))->download($path, basename($file->filename));
        }

        $path = Files::UPLOAD_FOLDER . '/' . $path;
        $ext = pathinfo($file->filename, PATHINFO_EXTENSION);

        $filename = $file->name ? $file->name . '.' . $ext : $file->filename;
        try {
            return response()->download($path, $filename);
        } catch (\Exception $e) {
            return response()->view('errors.file_not_found', ['message' => $e->getMessage()], 404);
        }

    }

}


if (!function_exists('gdpr_setting')) {

    // @codingStandardsIgnoreLine
    function gdpr_setting()
    {
        if (!session()->has('gdpr_setting')) {
            session(['gdpr_setting' => \App\Models\GdprSetting::first()]);
        }

        return session('gdpr_setting');
    }

}

if (!function_exists('social_auth_setting')) {

    // @codingStandardsIgnoreLine
    function social_auth_setting()
    {
        if (!cache()->has('social_auth_setting')) {
            cache(['social_auth_setting' => \App\Models\SocialAuthSetting::first()]);
        }

        return cache('social_auth_setting');
    }

}

if (!function_exists('invoice_setting')) {

    // @codingStandardsIgnoreLine
    function invoice_setting()
    {
        if (!session()->has('invoice_setting')) {
            return session(['invoice_setting' => InvoiceSetting::first()]);
        }

        return session('invoice_setting');
    }

    // @codingStandardsIgnoreLine

}

if (!function_exists('global_invoice_setting')) {

    // @codingStandardsIgnoreLine
    function global_invoice_setting()
    {
        if (!cache()->has('global_invoice_setting')) {
            cache(['global_invoice_setting' => GlobalInvoiceSetting::first()]);
        }

        return cache('global_invoice_setting');
    }

    // @codingStandardsIgnoreLine

}

if (!function_exists('time_log_setting')) {

    // @codingStandardsIgnoreLine
    function time_log_setting()
    {
        if (!session()->has('time_log_setting')) {
            session(['time_log_setting' => \App\Models\LogTimeFor::first()]);
        }

        return session('time_log_setting');
    }

}

if (!function_exists('check_migrate_status')) {

    // @codingStandardsIgnoreLine
    function check_migrate_status()
    {

        if (!session()->has('check_migrate_status')) {

            $status = Artisan::call('migrate:check');

            if ($status && !request()->ajax()) {
                Artisan::call('migrate', ['--force' => true, '--schema-path' => 'do not run schema path']); // Migrate database
                Artisan::call('optimize:clear');
            }

            session(['check_migrate_status' => 'Good']);
        }

        return session('check_migrate_status');
    }

}

if (!function_exists('countries')) {

    // @codingStandardsIgnoreLine
    function countries()
    {
        if (!cache()->has('countries')) {
            cache(['countries' => \App\Models\Country::all()]);
        }

        return cache('countries');
    }

}

if (!function_exists('module_enabled')) {

    // @codingStandardsIgnoreLine
    function module_enabled($moduleName)
    {
        return \Nwidart\Modules\Facades\Module::collections()->has($moduleName);
    }

}

if (!function_exists('currency_format_setting')) {

    // @codingStandardsIgnoreLine
    function currency_format_setting($currencyId = null)
    {
        if (!session()->has('currency_format_setting' . $currencyId)) {
            $setting = $currencyId == null ? Currency::first() : Currency::where('id', $currencyId)->first();
            session(['currency_format_setting' . $currencyId => $setting]);
        }

        return session('currency_format_setting' . $currencyId);
    }

}

if (!function_exists('currency_format')) {

    // @codingStandardsIgnoreLine
    function currency_format($amount, $currencyId = null, $showSymbol = true)
    {
        $formats = currency_format_setting($currencyId);

        if (!$showSymbol) {
            $currency_symbol = '';
        }
        else {
            $settings = $formats->cooperative ?? Cooperative::find($formats->cooperative_id);
            $currency_symbol = $currencyId == null ? $settings->currency->currency_symbol : $formats->currency_symbol;
        }

        $currency_position = $formats->currency_position;
        $no_of_decimal = !is_null($formats->no_of_decimal) ? $formats->no_of_decimal : '0';
        $thousand_separator = !is_null($formats->thousand_separator) ? $formats->thousand_separator : '';
        $decimal_separator = !is_null($formats->decimal_separator) ? $formats->decimal_separator : '0';

        $amount = number_format($amount, $no_of_decimal, $decimal_separator, $thousand_separator);

        $amount = match ($currency_position) {
            'right' => $amount . $currency_symbol,
            'left_with_space' => $currency_symbol . ' ' . $amount,
            'right_with_space' => $amount . ' ' . $currency_symbol,
            default => $currency_symbol . $amount,
        };

        return $amount;
    }

}

if (!function_exists('attendance_setting')) {

    // @codingStandardsIgnoreLine
    function attendance_setting()
    {
        if (!session()->has('attendance_setting')) {
            session(['attendance_setting' => \App\Models\AttendanceSetting::first()]);
        }

        return session('attendance_setting');
    }

}

if (!function_exists('add_project_permission')) {

    // @codingStandardsIgnoreLine
    function add_project_permission()
    {
        if (!session()->has('add_project_permission') && user()) {
            session(['add_project_permission' => user()->permission('add_projects')]);
        }

        return session('add_project_permission');
    }

}

if (!function_exists('add_tasks_permission')) {

    // @codingStandardsIgnoreLine
    function add_tasks_permission()
    {
        if (!session()->has('add_tasks_permission') && user()) {
            session(['add_tasks_permission' => user()->permission('add_tasks')]);
        }

        return session('add_tasks_permission');
    }

}

if (!function_exists('add_clients_permission')) {

    // @codingStandardsIgnoreLine
    function add_clients_permission()
    {
        if (!session()->has('add_clients_permission') && user()) {
            session(['add_clients_permission' => user()->permission('add_clients')]);
        }

        return session('add_clients_permission');
    }

}

if (!function_exists('add_employees_permission')) {

    // @codingStandardsIgnoreLine
    function add_employees_permission()
    {
        if (!session()->has('add_employees_permission') && user()) {
            session(['add_employees_permission' => user()->permission('add_employees')]);
        }

        return session('add_employees_permission');
    }

    // @codingStandardsIgnoreLine

}

if (!function_exists('add_payments_permission')) {

    // @codingStandardsIgnoreLine
    function add_payments_permission()
    {
        if (!session()->has('add_payments_permission') && user()) {
            session(['add_payments_permission' => user()->permission('add_payments')]);
        }

        return session('add_payments_permission');
    }

    // @codingStandardsIgnoreLine
}

if (!function_exists('add_tickets_permission')) {

    // @codingStandardsIgnoreLine
    function add_tickets_permission()
    {
        if (!session()->has('add_tickets_permission') && user()) {
            session(['add_tickets_permission' => user()->permission('add_tickets')]);
        }

        return session('add_tickets_permission');
    }

}

if (!function_exists('add_timelogs_permission')) {

    // @codingStandardsIgnoreLine
    function add_timelogs_permission()
    {
        if (!session()->has('add_timelogs_permission') && user()) {
            session(['add_timelogs_permission' => user()->permission('add_timelogs')]);
        }

        return session('add_timelogs_permission');
    }

}

if (!function_exists('manage_active_timelogs')) {

    // @codingStandardsIgnoreLine
    function manage_active_timelogs()
    {
        if (!session()->has('manage_active_timelogs') && user()) {
            session(['manage_active_timelogs' => user()->permission('manage_active_timelogs')]);
        }

        return session('manage_active_timelogs');
    }

}

if (!function_exists('slack_setting')) {

    // @codingStandardsIgnoreLine
    function slack_setting()
    {
        if (!session()->has('slack_setting')) {
            session(['slack_setting' => \App\Models\SlackSetting::first()]);
        }

        return session('slack_setting');
    }

}

if (!function_exists('default_address')) {

    // @codingStandardsIgnoreLine
    function default_address()
    {
        if (!session()->has('default_address')) {
            session(['default_address' => cooperative()->defaultAddress]);
        }

        return session('default_address');
    }

}

if (!function_exists('abort_403')) {

    // @codingStandardsIgnoreLine
    function abort_403($condition)
    {
        abort_if($condition, 403, __('messages.permissionDenied'));
    }

}

if (!function_exists('sidebar_user_perms')) {

    // @codingStandardsIgnoreLine
    function sidebar_user_perms()
    {
        if (!session()->has('sidebar_user_perms')) {

            $sidebarPermissionsArray = [
                'view_clients',
                'view_lead',
                'view_employees',
                'view_leave',
                'view_attendance',
                'view_holiday',
                'view_contract',
                'view_projects',
                'view_tasks',
                'view_timelogs',
                'view_estimates',
                'view_invoices',
                'view_payments',
                'view_expenses',
                'view_product',
                'view_order',
                'view_tickets',
                'view_events',
                'view_notice',
                'view_task_report',
                'view_time_log_report',
                'view_finance_report',
                'view_income_expense_report',
                'view_leave_report',
                'view_lead_proposals',
                'view_attendance_report',
                'manage_cooperative_setting',
                'add_employees',
                'view_knowledgebase',
                'view_shift_roster',
                'view_designation',
                'view_department',
                'view_overview_dashboard',
                'view_project_dashboard',
                'view_client_dashboard',
                'view_hr_dashboard',
                'view_ticket_dashboard',
                'view_finance_dashboard',
                'view_expense_report',
                'view_client_note',
                'view_bankaccount',
                'view_appreciation',
                'manage_award',
                'view_lead_report',
                'view_sales_report',
            ];


            $sidebarPermissions = Permission::whereIn('name', $sidebarPermissionsArray)->select('id', 'name')->orderBy('id', 'asc')->get();

            $sidebarPermissionsId = $sidebarPermissions->pluck('id')->toArray();

            $sidebarUserPermissionType = UserPermission::where('user_id', user()->id)
                ->whereIn('permission_id', $sidebarPermissionsId)
                ->join('permissions', 'permissions.id', '=', 'user_permissions.permission_id')
                ->orderBy('user_permissions.id')
                ->select('user_permissions.permission_type_id', 'permissions.name', 'permissions.id')
                ->groupBy(['user_id', 'permission_id', 'permission_type_id'])
                ->get()
                ->keyBy('name');

            $sidebarUserPermissions = array_combine($sidebarUserPermissionType->pluck('name')->toArray(), $sidebarUserPermissionType->pluck('permission_type_id')->toArray());

            $unassignedPermissions = array_diff($sidebarPermissionsId, $sidebarUserPermissionType->pluck('id')->toArray());

            $filteredPermissions = $sidebarPermissions->filter(function ($item) use ($unassignedPermissions) {
                return in_array($item->id, $unassignedPermissions);
            });

            foreach ($filteredPermissions as $item) {
                $sidebarUserPermissions[$item->name] = 5;
            }

            session(['sidebar_user_perms' => $sidebarUserPermissions]);
        }

        return session('sidebar_user_perms');

    }

}

if (!function_exists('sidebar_superadmin_perms')) {

    // @codingStandardsIgnoreLine
    function sidebar_superadmin_perms()
    {
        session()->forget('sidebar_superadmin_perms');
        if (!session()->has('sidebar_superadmin_perms')) {

            $sidebarPermissionsArray = [
                'view_packages',
                'view_companies',
                'manage_billing',
                'view_request',
                'view_admin_faq',
                'view_superadmin',
                'view_superadmin_ticket',
                'manage_superadmin_front_settings',

            ];

            $superadminSidebarPermissions = Permission::whereIn('name', $sidebarPermissionsArray)
                ->whereHas('module', function ($query) {
                    $query->withoutGlobalScopes()->where('is_superadmin', '1');
                })->orderBy('id', 'asc')->get();

            $uperadminSidebarPermissionsId = $superadminSidebarPermissions->pluck('id')->toArray();

            $sidebarSuperadminPermissionType = UserPermission::where('user_id', user()->id)
                ->whereIn('permission_id', $uperadminSidebarPermissionsId)
                ->join('permissions', 'permissions.id', '=', 'user_permissions.permission_id')
                ->orderBy('user_permissions.id')
                ->select('user_permissions.permission_type_id', 'permissions.name', 'permissions.id')
                ->groupBy(['user_id', 'permission_id', 'permission_type_id'])
                ->get()
                ->keyBy('name');

            $sidebarSuperadminPermissions = array_combine($sidebarSuperadminPermissionType->pluck('name')->toArray(), $sidebarSuperadminPermissionType->pluck('permission_type_id')->toArray());

            $unassignedPermissions = array_diff($uperadminSidebarPermissionsId, $sidebarSuperadminPermissionType->pluck('id')->toArray());

            $filteredPermissions = $superadminSidebarPermissions->filter(function ($item) use ($unassignedPermissions) {
                return in_array($item->id, $unassignedPermissions);
            });

            foreach ($filteredPermissions as $item) {
                $sidebarSuperadminPermissions[$item->name] = 5;
            }

            session(['sidebar_superadmin_perms' => $sidebarSuperadminPermissions]);
        }

        return session('sidebar_superadmin_perms');

    }

}

if (!function_exists('mb_ucfirst')) {

    // @codingStandardsIgnoreLine
    function mb_ucfirst($string, $encoding = 'utf8')
    {
        $firstChar = mb_substr($string, 0, 1, $encoding);
        $then = mb_substr($string, 1, null, $encoding);

        return mb_strtoupper($firstChar, $encoding) . $then;
    }

}

if (!function_exists('mb_ucwords')) {

    // @codingStandardsIgnoreLine
    function mb_ucwords($string, $encoding = 'utf8')
    {
        return mb_convert_case($string, MB_CASE_TITLE, $encoding);
    }

}

if (!function_exists('minute_to_hour')) {

    // @codingStandardsIgnoreLine
    function minute_to_hour($totalMinutes)
    {
        return \Carbon\CarbonInterval::formatHuman($totalMinutes); /** @phpstan-ignore-line */
    }

}

if (!function_exists('can_upload')) {

    // @codingStandardsIgnoreLine
    function can_upload($size = 0)
    {
        if (!session()->has('client_cooperative')) {
            session()->forget(['cooperative_setting', 'cooperative']);
        }

        // Return true for unlimited file storage
        if (cooperative()->package->max_storage_size == -1) {
            return true;
        }

        // Total Space in package in MB
        $totalSpace = (cooperative()->package->storage_unit == 'mb') ? cooperative()->package->max_storage_size : cooperative()->package->max_storage_size * 1024;

        // Used space in mb
        $fileStorage = \App\Models\FileStorage::all();
        $usedSpace = $fileStorage->count() > 0 ? round($fileStorage->sum('size') / (1000 * 1024), 4) : 0;

        $remainingSpace = $totalSpace - $usedSpace;

        if ($usedSpace > $totalSpace || $size > $remainingSpace) {
            return false;
        }

        return true;
    }

}

if (!function_exists('isWorksuiteSaas')) {

    function isWorksuiteSaas()
    {
        return strtolower(config('app.app_name')) === 'worksuite-saas';
    }

}

if (!function_exists('isWorksuite')) {

    function isWorksuite()
    {
        return strtolower(config('app.app_name')) === 'worksuite';
    }

}

if (!function_exists('showId')) {

    function showId()
    {
        return isWorksuite();
    }

}

if (!function_exists('getDomainSpecificUrl')) {

    function getDomainSpecificUrl($url, $cooperative = null)
    {
        // Check if Subdomain module exist
        if (!module_enabled('Subdomain')) {
            return $url;
        }

        // If cooperative specific
        if ($cooperative) {

            if (Str::contains($url, $cooperative->sub_domain)) {
                return $url;
            }

            $url = str_replace(request()->getHost(), $cooperative->sub_domain, $url);
            $url = str_replace('www.', '', $url);

            // Replace https to http for sub-domain to
            if (!\config('app.redirect_https')) {
                return str_replace('https', 'http', $url);
            }

            return $url;
        }

        // If there is no cooperative and url has login means
        // New superadmin is created
        return str_replace('login', 'super-admin-login', $url);
    }

}

if (!function_exists('getSubdomainSchema')) {

    function getSubdomainSchema()
    {

        if (!session()->has('subdomain_schema')) {
            if (\Illuminate\Support\Facades\Schema::hasTable('sub_domain_module_settings')) {
                $data = \Illuminate\Support\Facades\DB::table('sub_domain_module_settings')->first();
            }

            session(['subdomain_schema' => isset($data->schema) ? $data->schema : 'http']);
        }

        return session('subdomain_schema');
    }

}

if (!function_exists('getDomain')) {

    function getDomain($host = false)
    {
        
        if (!$host) {
            $host = $_SERVER['SERVER_NAME'] ?? 'worksuitesaas.test';
        }

        $shortDomain = config('app.short_domain_name');
        $dotCount = ($shortDomain === true) ? 2 : 3;

        $myHost = strtolower(trim($host));
        $count = substr_count($myHost, '.');

        if ($count === 2) {
            if (strlen(explode('.', $myHost)[1]) >= $dotCount) {
                $myHost = explode('.', $myHost, 2)[1];
            }
        }
        else if ($count > 2) {
            $myHost = getDomain(explode('.', $myHost, 2)[1]);
        }

        return $myHost;
    }

}

if (!function_exists('cooperative')) {

    function cooperative()
    {

        if (session()->has('cooperative')) {
            return session('cooperative');
        }


        if (user()) {
            if (user()->cooperative_id) {
                $cooperative = Cooperative::find(user()->cooperative_id);
                session(['cooperative' => $cooperative]);

                return $cooperative;
            }

            return session('cooperative');
        }

        return false;
    }

}

if (!function_exists('cooperativeOrGlobalSetting')) {

    function cooperativeOrGlobalSetting()
    {
        if (user()) {

            if (user()->cooperative_id) {
                return cooperative();
            }
        }

        return global_setting();
    }

}

if (!function_exists('trim_editor')) {

    // @codingStandardsIgnoreLine
    function trim_editor($text)
    {
        return trim(str_replace('<p><br></p>', '', trim($text)));
    }

}

if (!function_exists('quickbooks_setting')) {

    // @codingStandardsIgnoreLine
    function quickbooks_setting()
    {
        if (!session()->has('quickbooks_setting')) {
            $qbSetting = QuickBooksSetting::first();
            session(['quickbooks_setting' => $qbSetting]);

            return $qbSetting;
        }

        return session('quickbooks_setting');
    }

    // @codingStandardsIgnoreLine

}

if (!function_exists('user_role_ids')) {

    /**
     * Return current logged in user
     */
    // @codingStandardsIgnoreLine
    function user_role_ids()
    {
        if (session()->has('user_role_ids')) {
            return session('user_role_ids');
        }

        return null;
    }

}

if (!function_exists('global_currency_format_setting')) {

    // @codingStandardsIgnoreLine
    function global_currency_format_setting($currencyId = null)
    {
        if (!cache()->has('global_currency_format_setting' . $currencyId)) {
            $setting = $currencyId == null ? GlobalCurrency::first() : GlobalCurrency::withTrashed()->where('id', $currencyId)->first();
            cache(['global_currency_format_setting' . $currencyId => $setting]);
        }

        return cache('global_currency_format_setting' . $currencyId);
    }

}

if (!function_exists('global_currency_format')) {

    // @codingStandardsIgnoreLine
    function global_currency_format($amount, $currencyId = null, $showSymbol = true)
    {
        $globalformat = global_currency_format_setting($currencyId);
        $settings = cooperativeOrGlobalSetting();

        if($showSymbol == false){
            $currency_symbol = '';
        }
        else {
            $currency_symbol = $currencyId == null && $settings->currency ? $settings->currency->currency_symbol : $globalformat->currency_symbol;
        }

        $currency_position = $globalformat->currency_position;
        $no_of_decimal = !is_null($globalformat->no_of_decimal) ? $globalformat->no_of_decimal : '0';
        $thousand_separator = !is_null($globalformat->thousand_separator) ? $globalformat->thousand_separator : '';
        $decimal_separator = !is_null($globalformat->decimal_separator) ? $globalformat->decimal_separator : '0';

        $amount = number_format($amount, $no_of_decimal, $decimal_separator, $thousand_separator);

        $amount = match ($currency_position) {
            'right' => $amount . $currency_symbol,
            'left_with_space' => $currency_symbol . ' ' . $amount,
            'right_with_space' => $amount . ' ' . $currency_symbol,
            default => $currency_symbol . $amount,
        };

        return $amount;
    }

}

if (!function_exists('user_companies')) {

    // @codingStandardsIgnoreLine
    function user_companies($user)
    {

        if (!session()->has('user_companies')) {
            $userCompanies = User::withoutGlobalScope(CooperativeScope::class)
                ->where('email', $user->email)
                ->where('login', 'enable')
                ->whereHas('approvedCooperative')
                ->with('cooperative')
                ->withOut('clientDetails', 'role', 'employeeDetail')
                ->select('id', 'cooperative_id', 'status')
                ->get();

                session(['user_companies' => $userCompanies]);

                return $userCompanies;
        }

        return session('user_companies');
    }

}


if (!function_exists('flushCooperativeSpecificSessions')) {

    function flushCooperativeSpecificSessions()
    {
        session()->forget([
            'user_roles',
            'admin_theme',
            'employee_theme',
            'client_theme',
            'message_setting',
            'email_notification_setting',
            'invoice_setting',
            'time_log_setting',
            'currency_format_setting',
            'attendance_setting',
            'add_project_permission',
            'add_tasks_permission',
            'add_clients_permission',
            'add_employees_permission',
            'add_payments_permission',
            'add_tickets_permission',
            'add_timelogs_permission',
            'manage_active_timelogs',
            'slack_setting',
            'default_address',
            'sidebar_user_perms',
            'quickbooks_setting',
            'user_permissions',
        ]);

    }

}

if (!function_exists('checkCooperativePackageIsValid')) {

    function checkCooperativePackageIsValid($cooperativeId)
    {

        if (is_null($cooperativeId)) {
            return true;
        }

        return cache()->rememberForever('cooperative_' . $cooperativeId . '_valid_package', function () use ($cooperativeId) {
            $cooperative = Cooperative::with('package')->withCount('employees')->find($cooperativeId);
            return $cooperative->employees_count <= $cooperative->package->max_employees;
        });

    }

}

if (!function_exists('checkCooperativeCanAddMoreEmployees')) {

    function checkCooperativeCanAddMoreEmployees($cooperativeId)
    {

        if (is_null($cooperativeId)) {
            return true;
        }

        return cache()->rememberForever('cooperative_' . $cooperativeId . '_can_add_more_employees', function () use ($cooperativeId) {
            $cooperative = Cooperative::with('package')->withCount('employees')->find($cooperativeId);
            return $cooperative->employees_count < $cooperative->package->max_employees;
        });

    }

}

if (!function_exists('clearCooperativeValidPackageCache')) {

    function clearCooperativeValidPackageCache($cooperativeId)
    {

        if (is_null($cooperativeId)) {
            return true;
        }

        cache()->forget('cooperative_' . $cooperativeId . '_valid_package');
        cache()->forget('cooperative_' . $cooperativeId . '_can_add_more_employees');
    }

}


