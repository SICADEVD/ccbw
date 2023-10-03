<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidTravailleurs implements Rule
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
        // Récupérez les valeurs des trois champs
        $travailleurs = request()->input('travailleurs');
        $travailleursPermanent = request()->input('travailleurspermanents');
        $travailleursNonPermanent = request()->input('travailleurstemporaires');

        // Assurez-vous que la somme des travailleurs permanents et non permanents n'est pas supérieure au nombre total de travailleurs
        return ($travailleursPermanent + $travailleursNonPermanent) <= $travailleurs;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'La somme des travailleurs permanents et non permanents ne doit pas dépasser le nombre total de travailleurs.';
    }
}
