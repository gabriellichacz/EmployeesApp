<?php namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
