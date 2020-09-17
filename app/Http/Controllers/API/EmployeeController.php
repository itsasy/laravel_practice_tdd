<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\RegisterRequest;
use App\Http\Resources\EmployeeResource;
use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::all();

        return response([
            'Employees' => EmployeeResource::collection($employees),
            'message' => 'Retrieved successfully'
        ], 200);
    }

    public function create($request)
    {
        return Employee::create($request->all());
    }

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

    public function show(Employee $employee)
    {
        return response([
            'Employee' => new EmployeeResource($employee),
            'message' => 'Retrieved successfully'
        ], 200);
    }

    public function update(Request $request, Employee $employee)
    {
        $employee->update($request->all());

        return response([
            'Employee' => new EmployeeResource($employee),
            'message' => 'Updated successfully',
        ], 201);
    }

    public function destroy(Employee $employee)
    {
        $status = $employee->delete();

        return response([
            'message' => $status ? 'Deleted successfully' : 'Error'
        ], 204);
    }
}
