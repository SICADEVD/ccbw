<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class VlidateEnfantTotal implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        
        $pasEXtrait = request()->input('enfantsPasExtrait');
        $enfant6a17 = request()->input('ageEnfant6A17');
        $enfant0a5 = request()->input('ageEnfant0A5');

        return $pasEXtrait <= ($enfant6a17 + $enfant0a5);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Le nombre d'enfants n'ayant pas extrait ou inscrit ne doit pas être supérieur au nombre d'enfants de la famille.";
    }
}
