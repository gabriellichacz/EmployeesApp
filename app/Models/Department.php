<?php namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class Department extends Model
{
    use HasFactory;

    /**
     * Corresponding table
     *
     * @var string table name
     */
    protected $table = 'departments';

    /**
     * Real primary key
     *
     * @var string table name
     */
    protected $primaryKey = 'dept_no';

    /**
     * Get departments list
     * 
     * @return array
     */
    public static function getDepartmentList() : array
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

        return $dep;
    }

    /**
     * Get departments list and their employee count
     *
     * 
     */
    public static function getDepartmentEmployeeCount()
    {
        $departmentCountList = Cache::pull('departmentCountList');
        if (!empty($departmentCountList))
        {
            return $departmentCountList;
        }

        $departmentsList = self::getDepartmentList();
        $departmentCountList = [];
        foreach ($departmentsList as $department)
        {
            $departmentCountList[$department] = DB::table('employees')
                ->select('employees.emp_no', 'departments.dept_name')
                ->join('dept_emp', 'employees.emp_no', '=', 'dept_emp.emp_no')
                ->join('departments', 'dept_emp.dept_no', '=', 'departments.dept_no')
                ->where('dept_name', $department)
                ->groupBy('employees.emp_no')
                ->get()
                ->count();
        }
        Cache::put('departmentCountList', $departmentCountList);

        return $departmentCountList;
    }
}
