<?php

namespace App\Http\Resources;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Resources\SurveyQuestionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SurveyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'title'=>$this->title,
            'image_url'=>$this->image ? URL::to($this->image) : null,
            'slug'=>$this->slug,
            'status'=>$this->status,
            'description'=>$this->description,
            'created_at'=>(new DateTime($this->created_at))->format('Y-m-d H:i:s'),
            'updated_at'=>(new DateTime($this->updated_at))->format('Y-m-d H:i:s'),
            'expiry_date'=>$this->expiry_date,
            'questions'=>SurveyQuestionResource::collection($this->questions)

        ];
    }
}
