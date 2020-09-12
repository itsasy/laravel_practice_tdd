<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /* return parent::toArray($request); */

        return [
            'id' => $this->id,
            'name' => $this->name,
            'lastname' => $this->lastname,
            'company' => $this->company,
            'mail' => $this->mail,
            'phone' => $this->phone
        ];
    }
}
