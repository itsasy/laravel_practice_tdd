<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\CompanyRequest;
use App\Http\Resources\CompanyCollection;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    public function index()
    {
        //-name
        $direction = 'asc';

        $sortField = request('sort');

        //Comprueba si inicia con el signo -
        if (Str::of($sortField)->startsWith('-')) {
            $direction = 'desc';
            $sortField = Str::of($sortField)->substr(1);
        }

        return CompanyCollection::make(
            Company::orderBy($sortField, $direction)->get()
        );
    }


    public function create($request)
    {
        $company =  Company::create($request->all());

        if ($request->logo) {
            $this->save_image($request->logo, $company);
        }

        return $company;
    }

    public function store(CompanyRequest $request)
    {
        return CompanyResource::make($this->create($request));
    }

    public function show(Company $company)
    {
        return CompanyResource::make($company);
    }

    public function update(Request $request, Company $company)
    {
        $company->update($request->all());

        if ($request->logo) {
            $this->save_image($request->logo, $company);
        }

        return response([
            'Company' => new CompanyResource($company),
            'message' => 'Update successfully'
        ], 201);
    }


    public function destroy(Company $company)
    {
        $status = $company->delete();

        return response([
            'message' => $status ? 'Deleted successfully' : 'Error',
        ], 204);
    }

    public function save_image($image, $company): void
    {
        $name = $image->getClientOriginalName();
        $image->storeAs('', $name);

        //$url = Storage::url($name);
        $company->update(['logo' => $name]);
    }
}
