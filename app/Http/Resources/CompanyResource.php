<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $logo_url = $this->resource->logo ? Storage::url($this->resource->logo) : null;

        return [
            'id' => $this->resource->getRouteKey(),
            'atributes' => [
                'name' => $this->resource->name,
                'slug' => $this->resource->slug,
                'mail' => $this->resource->mail,
            ],
            'links' => [
                'self' => route('company.show', $this->resource),
                'logo' => $logo_url,
                'website' => $this->resource->website,
            ]
        ];
    }
}
