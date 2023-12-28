<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /** @var array $filters */
    protected array $filters;

    /** @var Employee $employeeModel */
    protected Employee $employeeModel;

    /**
     * Construct function
     */
    public function __construct()
    {
        ini_set('max_execution_time', 300);
        $this->filters = $this->initFilters();
        $this->employeeModel = new Employee;
    }

    /**
     * Admin dashboard view
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() : \Illuminate\Contracts\Support\Renderable
    {
        return view('employees.dashboard', [
            'employees' => $this->basicTable(),
            'dep_names' => $this->depNames(),
            'filters' => $this->filters
        ]);
    }

    /**
     * Basic table with all data
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function basicTable() : \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->employeeModel->getEmployeeTable();
    }

    /**
     * List of departments
     *
     * @return array
     */
    public function depNames() : array
    {
        return Department::getDepartmentList();
    }

    /**
     * Returns view with filtered data
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function filtering(Request $request) : \Illuminate\Contracts\Support\Renderable
    {
        $max = null;
        $min = null;
        $gender = null;
        $status = null;
        $department = null;

        if ($request->has('max_salary'))
        {
            $max = intval($request->max_salary);
            $this->filters['max_salary'] = $max;
        }

        if ($request->has('min_salary'))
        {
            $min = intval($request->min_salary);
            $this->filters['min_salary'] = $min;
        }

        if ($request->has('gender'))
        {
            $gender = $request->gender;
            $this->filters['gender'] = $gender;
        }

        if ($request->has('status'))
        {
            $status = $request->status;
            $this->filters['status'] = $status;
        }

        if ($request->has('department'))
        {
            $department = $request->department;
            $this->filters['department'] = $department;
        }

        $model = $this->employeeModel->getEmployeeTableFiltered($gender, $department, $min, $max, $status);

        return view('employees.dashboard', [
            'employees' => $model,
            'dep_names' => $this->depNames(),
            'filters' => $this->filters
        ]);
    }

    /**
     * Init filters on construct
     * 
     * @return array
     */
    protected function initFilters() : array
    {
        $filters = [];
        $filters['max_salary'] = null;
        $filters['min_salary'] = null;
        $filters['gender'] = null;
        $filters['status'] = null;
        $filters['department'] = null;
        return $filters;
    }

    /**
     * Exports selected data
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function export(Request $request)
    {
        try
        {
            $emp_ids = $request->checkboxExport;
        }
        catch (\Exception $e)
        {
            $emp_ids = null;
            return response('Bad request', 404);
        }

        $data[0] = [
            "first_name",
            "last_name",
            "department",
            "title",
            "salary",
            "salary_sum"
        ];

        foreach ($emp_ids as $emp_id)
        {
            $employee = Employee::where('employees.emp_no', $emp_id)->first();

            $data[$emp_id + 1] = [
                $employee->first_name,
                $employee->last_name,
                $employee->departments()->first()->dept_name,
                $employee->currTitle(),
                $employee->currSalary(),
                intval($employee->salaries()->sum('salary'))
            ];
        }

        $this->arrayToCsvDownload($data);
    }

    /**
     * Creates csv file and downloads it
     *
     * @param array $array
     * @param string $filename
     * @param string $delimiter
     * @return void
     */
    function arrayToCsvDownload(array $array, string $filename = "export.csv", string $delimiter = ";")
    {
        $f = fopen('php://memory', 'w');

        foreach ($array as $line)
        {
            fputcsv($f, $line, $delimiter);
        }

        fseek($f, 0);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');
        fpassthru($f);
    }
}
