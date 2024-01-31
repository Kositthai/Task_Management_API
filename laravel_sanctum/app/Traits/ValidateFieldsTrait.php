<?php
namespace App\Traits;

use Illuminate\Http\Request;

trait ValidateFieldsTrait {

    /**
     * @param Request $request
     * @return $this|false|string
     */
    public function validationFields(Request $request, $allowedFields ) {

        # this code used to check if user trying to add unexpected fields when submit the form? 
        # the code used array_keys to get the keys of request body and using contains method to check if requestField is contains field (allowedFields) 
        # if found unexpected field inside requestField return true otherwise false 
        $requesFields = collect(array_keys($request->all()));
        $containsUnexpectedFields = $requesFields->contains(function($field) use ($allowedFields) {
            return !in_array($field, $allowedFields);
        });

        if( $containsUnexpectedFields ) {
            return response()->json(['error' => 'Unexpected fields detected.'], 422);
        }
        
        return null;
    }
}