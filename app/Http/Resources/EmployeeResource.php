<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    public function toArray($request)
    {
        /* return parent::toArray($request); */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'lastname' => $this->lastname,
            'mail' => $this->mail,
            'phone' => $this->phone,
            'Company' => $this->company,
        ];
    }
}
