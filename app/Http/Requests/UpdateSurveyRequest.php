<?php

namespace App\Http\Requests;

use App\Models\Survey;
use Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSurveyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    
     public function authorize(): bool
    {
        
        return true;
    }
     
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            
            'title'=> 'required|string|max:1000',
            'image'=>'nullable|string',
            
            'status'=>'required|boolean',
            'description'=>'nullable|string',
            'expiry_date'=>'nullable|date|after:tomorrow',
           
        ];
    }
}
