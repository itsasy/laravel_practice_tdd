<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\RegisterRequest;
use App\Http\Resources\EmployeeResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::all();

        return response([
            'Employees' => EmployeeResource::collection($employees),
            'message' => 'Retrieved sucessfully'
        ], 200);
    }

    public function create($request)
    {
        return Employee::create($request->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  RegisterRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RegisterRequest $request)
    {
        return response(
            [
                'Employee' => new EmployeeResource(
                    $this->create($request)
                ),
                'message' => 'Created successfully'
            ],
            201
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        return response([
            'Employee' => new EmployeeResource($employee),
            'message' => 'Retrieved sucessfully'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        $employee->update($request->all());

        return response([
            'Employee' => new EmployeeResource($employee),
            'message' => 'Updated sucessfully',
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        $status = $employee->delete();

        return response([
            'message' => $status ? 'Deleted succesfully' : 'Error'
        ], 204);
    }
}
