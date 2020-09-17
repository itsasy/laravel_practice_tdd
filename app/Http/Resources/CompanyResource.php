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
        $url = $this->logo ? Storage::url($this->logo) : null;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'mail' => $this->mail,
            'website' => $this->website,
            'logo' => [
                'name' => $this->logo,
                'url' =>  $url
            ]
        ];
    }
}
