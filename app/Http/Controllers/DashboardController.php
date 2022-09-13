<?php namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $request;

    public function __construct() {
        ini_set('max_execution_time', 300);
    }

    /**
     * Admin dashboard view
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        return view('dashboard', [
            'employees' => $this->basicTable(),
            'dep_names' => $this->depNames(),
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
            ->select('employees.emp_no', 'employees.first_name','employees.last_name', 'titles.title', 'departments.dept_name', 'm1.salary as salary')
            ->join('dept_emp', 'employees.emp_no', '=', 'dept_emp.emp_no')
            ->join('departments', 'dept_emp.dept_no', '=', 'departments.dept_no')
            ->join('titles', 'employees.emp_no', '=', 'titles.emp_no')
            ->join('salaries as m1','employees.emp_no','=','m1.emp_no')
            ->leftJoin('salaries as m2', function($join){
                $join->on('employees.emp_no', '=', 'm2.emp_no')
                    ->where(function ($query) {
                        $query->whereColumn('m1.to_date','<','m2.to_date')
                            ->orWhere(function ($query) {
                                $query->whereColumn('m1.to_date','=','m2.to_date')
                                    ->whereColumn('m1.emp_no','<','m2.emp_no');
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
        try {
            $dep = Department::select('dept_name')->distinct()->get()->toArray();

            // Converting names
            $dep = array_map(function($item){
                return [$item['dept_name']];
            }, $dep);
            
            // Converting to 1d array
            $dep = array_reduce($dep, function($carry, $array) {
                return array_merge($carry, $array);
            }, []);
        } catch (\Throwable $th) {
            $dep = 0;
        }
        
        return $dep;
    }

    /**
     * Returns view with filtered data
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function filtering(Request $request)
    {
        try {
            $max = intval($request->max_salary);
            $min = intval($request->min_salary);
        } catch (\Throwable $th) {
            $max = null;
            $min = null;
        }

        try {
            $gender =  $request->gender;
        } catch (\Throwable $th) {
            $gender = null;
        }

        try {
            $status =  $request->status;
        } catch (\Throwable $th) {
            $status = null;
        }

        try {
            $department =  $request->department;
        } catch (\Throwable $th) {
            $department = null;
        }

        try {
            $model = DB::table('employees')
                /* Where gender */
                ->when(($gender && $gender != null && $gender != 'A'), function ($query) use($gender) {
                    $query->where('employees.gender', $gender);
                })
                ->select('employees.emp_no', 'employees.first_name','employees.last_name', 'titles.title', 'departments.dept_name', 'm1.salary as salary')
                ->join('dept_emp', 'employees.emp_no', '=', 'dept_emp.emp_no')
                /* Where department */
                ->when(($department && $department != null && $department != 'all'), function ($query) use($department) {
                    $query->join('departments', function ($join) use($department) {
                        $join->on('dept_emp.dept_no', '=', 'departments.dept_no')
                            ->where('departments.dept_name', $department);
                    });
                }, function ($query) {
                    $query->join('departments', 'dept_emp.dept_no', '=', 'departments.dept_no');
                })
                ->join('titles', 'employees.emp_no', '=', 'titles.emp_no');

                /* Conditions related to salaries table */
                if ($max && $min && $max != null && $min != null) { /* Salary between */
                    $model = $model->join('salaries as m1', function ($join) use($min, $max) {
                        $join->on('employees.emp_no','=','m1.emp_no')
                            ->whereBetween('m1.salary', [$min, $max]);
                    });
                } else if ($status && $status != null && $status != 'all' && $status == 'working') { /* Current employees */
                    $model = $model->join('salaries as m1', function ($join) {
                        $join->on('employees.emp_no','=','m1.emp_no')
                            ->where('m1.to_date', '>=', date("Y-m-d"));
                    });
                } else if ($status && $status != null && $status != 'all' && $status = 'former') { /* Former employees */
                    $model = $model->join('salaries as m1', function ($join) {
                        $join->on('employees.emp_no','=','m1.emp_no')
                            ->where('m1.to_date', '<', date("Y-m-d"));
                    });
                } else {
                    $model = $model->join('salaries as m1','employees.emp_no','=','m1.emp_no');
                }

                /* Rest of the query */
                $model = $model->leftJoin('salaries as m2', function($join){
                    $join->on('employees.emp_no', '=', 'm2.emp_no')
                        ->where(function ($query) {
                            $query->whereColumn('m1.to_date','<','m2.to_date')
                                ->orWhere(function ($query) {
                                    $query->whereColumn('m1.to_date','=','m2.to_date')
                                        ->whereColumn('m1.emp_no','<','m2.emp_no');
                                });
                        });
                })
                ->whereNull('m2.emp_no')
                ->groupBy('employees.emp_no')
                ->paginate(15);
        } catch (\Throwable $th) {
            $model = 0;
        }

        return view('dashboard', [
            'employees' => $model,
            'dep_names' => $this->depNames(),
        ]);
    }

    /**
     * Exports selected data
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function export(Request $request)
    {
        try {
            $emp_ids = $request->checkboxExport;
        } catch (\Throwable $th) {
            $emp_ids = null;
            return response('Bad request', 404);
        }

        // First row for csv file
        $data[0] = array(
            "first_name",
            "last_name",
            "department",
            "title",
            "salary",
            "salary_sum"
        );
        
        // Populating array with data
        foreach ($emp_ids as $emp_id) {
            $employee = Employee::where('employees.emp_no', $emp_id)->first();
            $data[$emp_id+1] = array(
                $employee->first_name,
                $employee->last_name,
                $employee->departments()->first()->dept_name,
                $employee->currTitle(),
                $employee->currSalary(),
                intval($employee->salaries()->sum('salary'))
            );
        }

        $this->array_to_csv_download($data);
    }

    /**
     * Creates csv file and downloads it
     *
     * @param mixed $array data array to export
     * @param string $filename
     * @param string $delimiter csv file delimeter
     * @return void
     */
    function array_to_csv_download($array, $filename = "export.csv", $delimiter=";") {
        $f = fopen('php://memory', 'w'); // open raw memory as file
       
        foreach ($array as $line) { 
            fputcsv($f, $line, $delimiter); // generate csv lines from the inner arrays
        }
        
        fseek($f, 0); // reset the file pointer to the start of the file
        header('Content-Type: text/csv'); // tell the browser it's going to be a csv file
        header('Content-Disposition: attachment; filename="'.$filename.'";'); // tell the browser we want to save it instead of displaying it
        fpassthru($f); // make php send the generated csv lines to the browser
    }
}