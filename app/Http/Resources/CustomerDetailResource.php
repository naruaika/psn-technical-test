<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'title' => $this->title,
            'gender' => $this->gender,
            'phone_number' => $this->phone_number,
            'avatar' => asset_storage($this->avatar),
            'email' => $this->email,
            'addresses' => AddressResource::collection($this->address),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
