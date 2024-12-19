<?php

namespace App\Http\Resources;

use App\Http\Resources\InvoiceResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
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
            "first_name" => $this->userable->first_name,
            "last_name" => $this->userable->last_name,
            "user_name" => $this->userable->user_name,
            "image" => $this->userable->image,
            "email" => $this->userable->email,
            "address" => $this->userable->address,
            "mobile_phone" => $this->userable->mobile_phone,
            "office_phone" => $this->userable->office_phone,
            "status" => $this->userable->status
        ];
      
        if(isset($this->user_type)){
            $resource['user_type'] = $this->user_type;
        }        
        if(isset($this->access_token)){
            $resource['access_token'] = $this->access_token;
        }

       // if(isset($this->permissions) && $this->permissions->count() > 0){
          //  $resource['permissions'] = $this->permissions->pluck('name')->all();

            $resource['permissions'] = $this->whenLoaded('permissions', function () {
                return $this->permissions->pluck('name')->all();
            });

       // }     
        $resource['invoices'] = $this->whenLoaded('invoices', function () {
                return InvoiceResource::collection($this->invoices);
        });
        
        return $resource;
    }
}
