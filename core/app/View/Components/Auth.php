<?php

namespace App\View\Components;

use App\Models\GlobalSetting;
use App\Models\LanguageSetting;
use Illuminate\Support\Facades\App;
use Illuminate\View\Component;

class Auth extends Component
{

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        // WORKSUITESAAS
        if (module_enabled('Subdomain')) {
            $cooperative = getCooperativeBySubDomain();
            $globalSetting = $cooperative ?? GlobalSetting::first();
        }
        else {
            $globalSetting = global_setting();
        }

        $languages = language_setting();

        $appTheme = $globalSetting;

        App::setLocale(session('locale') ?? $globalSetting->locale);

        return view('components.auth', ['globalSetting' => $globalSetting, 'appTheme' => $appTheme, 'languages' => $languages]);
    }

}
