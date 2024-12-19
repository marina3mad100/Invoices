<?php

namespace App\Http\Resources;

use App\Http\Resources\UserProfileResource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
	

    /**
     * Transform the resource into an array
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $resource = [
            "id" => $this->id,
            "number" => $this->number,
            "title" => $this->title,
            "amount" => $this->amount,
            "date" => $this->date,
            "description" => $this->description,
            "payment_status" => $this->payment_status,
			"user" =>$this->when(
                $this->relationLoaded('user') &&
                $this->user->relationLoaded('userable'),
                function () {
                    return new UserProfileResource($this->user);
                }
            ),
            'created_at' => $this->created_at->format('Y-m-d\TH:i:s\Z'),
            'updated_at' => $this->updated_at->format('Y-m-d\TH:i:s\Z')
        ];

        return $resource;
    }
}
