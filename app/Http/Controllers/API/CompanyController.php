<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\CompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::all();

        return response([
            'Companies' => CompanyResource::collection($companies),
            'message' => 'Retrieved successfully'
        ], 200);
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
        return response(
            [
                'Company' => new CompanyResource(
                    $this->create($request)
                ),
                'message' => 'Created successfully'
            ],
            201
        );
    }

    public function show(Company $company)
    {
        return response(
            [
                'Company' => new CompanyResource($company),
                'message' => 'Retrieved successfully'
            ],
            200
        );
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
