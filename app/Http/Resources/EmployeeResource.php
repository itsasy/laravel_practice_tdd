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
        return [
            'id' => $this->id,
            'atributes' => [
                'name' => $this->name,
                'lastname' => $this->lastname,
                'phone' => $this->phone,
                'mail' => $this->mail,
                'slug' => $this->resource->slug,
            ],
            'links' => [
                'self' => route('employee.show', $this->resource),
                'company' => route('company.show', $this->resource->company_id)
            ]
        ];
    }
}
