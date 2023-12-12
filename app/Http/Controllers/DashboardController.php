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

    /**
     * Construct function
     */
    public function __construct()
    {
        ini_set('max_execution_time', 300);
        $this->filters = $this->initFilters();
    }

    /**
     * Admin dashboard view
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
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
     * @return \Illuminate\Database\Query\Builder
     */
    public function basicTable()
    {
        return DB::table('employees')
            ->select('employees.emp_no', 'employees.first_name', 'employees.last_name', 'titles.title', 'departments.dept_name', 'm1.salary as salary')
            ->join('dept_emp', 'employees.emp_no', '=', 'dept_emp.emp_no')
            ->join('departments', 'dept_emp.dept_no', '=', 'departments.dept_no')
            ->join('titles', 'employees.emp_no', '=', 'titles.emp_no')
            ->join('salaries as m1', 'employees.emp_no', '=', 'm1.emp_no')
            ->leftJoin('salaries as m2', function ($join) {
                $join->on('employees.emp_no', '=', 'm2.emp_no')
                    ->where(function ($query) {
                        $query->whereColumn('m1.to_date', '<', 'm2.to_date')
                            ->orWhere(function ($query) {
                                $query->whereColumn('m1.to_date', '=', 'm2.to_date')
                                    ->whereColumn('m1.emp_no', '<', 'm2.emp_no');
                            });
                    });
            })
            ->whereNull('m2.emp_no')
            ->groupBy('employees.emp_no')
            ->paginate(15);
    }

    /**
     * List of departments
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function depNames()
    {
        try
        {
            $dep = Department::select('dept_name')
                ->distinct()
                ->get()
                ->toArray();

            $dep = array_map(function ($item) {
                return [$item['dept_name']];
            }, $dep);

            $dep = array_reduce($dep, function ($carry, $array) {
                return array_merge($carry, $array);
            }, []);
        }
        catch (\Exception $e)
        {
            $dep = 0;
        }

        return $dep;
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

        try
        {
            $model = DB::table('employees')
                // Where gender
                ->when(($gender && $gender != null && $gender != 'A'), function ($query) use ($gender) {
                    $query->where('employees.gender', $gender);
                })
                ->select('employees.emp_no', 'employees.first_name', 'employees.last_name', 'titles.title', 'departments.dept_name', 'm1.salary as salary')
                ->join('dept_emp', 'employees.emp_no', '=', 'dept_emp.emp_no')
                // Where department
                ->when(($department && $department != null && $department != 'all'), function ($query) use ($department) {
                    $query->join('departments', function ($join) use ($department) {
                        $join->on('dept_emp.dept_no', '=', 'departments.dept_no')
                            ->where('departments.dept_name', $department);
                    });
                }, function ($query) {
                    $query->join('departments', 'dept_emp.dept_no', '=', 'departments.dept_no');
                })
                ->join('titles', 'employees.emp_no', '=', 'titles.emp_no');

            // Conditions related to salaries table
            if ($max && $min && $max != null && $min != null) // Salary between
            {
                $model = $model->join('salaries as m1', function ($join) use ($min, $max) {
                    $join->on('employees.emp_no', '=', 'm1.emp_no')
                        ->whereBetween('m1.salary', [$min, $max]);
                });
            }
            else if ($status && $status != null && $status != 'all' && $status == 'working') // Current employees
            {
                $model = $model->join('salaries as m1', function ($join) {
                    $join->on('employees.emp_no', '=', 'm1.emp_no')
                        ->where('m1.to_date', '>=', date("Y-m-d"));
                });
            }
            else if ($status && $status != null && $status != 'all' && $status = 'former') // Former employees
            {
                $model = $model->join('salaries as m1', function ($join) {
                    $join->on('employees.emp_no', '=', 'm1.emp_no')
                        ->where('m1.to_date', '<', date("Y-m-d"));
                });
            }
            else
            {
                $model = $model->join('salaries as m1', 'employees.emp_no', '=', 'm1.emp_no');
            }

            // Rest of the query
            $model = $model
                ->leftJoin('salaries as m2', function ($join) {
                    $join->on('employees.emp_no', '=', 'm2.emp_no')
                    ->where(function ($query) {
                        $query->whereColumn('m1.to_date', '<', 'm2.to_date')
                        ->orWhere(function ($query) {
                            $query->whereColumn('m1.to_date', '=', 'm2.to_date')
                            ->whereColumn('m1.emp_no', '<', 'm2.emp_no');
                        });
                    });
                })
                ->whereNull('m2.emp_no')
                ->groupBy('employees.emp_no')
                ->paginate(15);
        }
        catch (\Exception $e)
        {
            $model = 0;
        }

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
