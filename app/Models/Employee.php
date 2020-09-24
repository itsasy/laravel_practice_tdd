<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = ['name', 'lastname', 'company_id', 'mail', 'phone'];

    public function company(){
        return $this->belongsTo(Company::class);
    }
}
