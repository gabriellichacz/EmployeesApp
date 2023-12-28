<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Repositories\EmployeeRepository;
use App\Models\Department;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /** @var EmployeeRepository $status */
    protected EmployeeRepository $employeeRepository;

    public function welcome()
    {
        return view('auth.login');
    }

    public function index()
    {
        $departments = Department::getDepartmentEmployeeCount();
        return view('home')->with([
            'departments' => $departments,
            'allEmployeeCount' => array_sum($departments)
        ]);
    }
}
