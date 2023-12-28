<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class EmployeeRepository
{
    /**
     * Get basic data table for dashboard paginated
     * 
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getEmployeeTable() : \Illuminate\Contracts\Pagination\LengthAwarePaginator
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
     * Get filtered data query
     * 
     *  @return \Illuminate\Database\Query\Builder
     */
    public function getEmployeeTableQuery(?string $gender, ?string $department, ?int $min, ?int $max, ?string $status) : \Illuminate\Database\Query\Builder
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
            ->groupBy('employees.emp_no');

        return $model;
    }

    /**
     * Get filtered data table for dashboard paginated
     * 
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getEmployeeTableFiltered(?string $gender, ?string $department, ?int $min, ?int $max, ?string $status) : \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $model = $this->getEmployeeTableQuery($gender, $department, $min, $max, $status);
        return $model->paginate(15);
    }
}
