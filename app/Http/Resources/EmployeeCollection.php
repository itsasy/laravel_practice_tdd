<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class EmployeeCollection extends ResourceCollection
{
    public $collects = EmployeeResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /* dd($this->collection->pluck('company')->flatten()); */
        return [
            'data' => $this->collection,
            /* 'links' => [
                'self' => route('company.show', $this->collection->pluck('id')),
            ], */
            'meta' => [
                'employees_count' => $this->collection->count()

            ]
        ];
    }
}
