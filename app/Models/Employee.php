<?php namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    /**
     * Corresponding table
     *
     * @var string table name
     */
    protected $table = 'employees';

    /**
     * Real primary key
     *
     * @var string table name
     */
    protected $primaryKey = 'emp_no';

    /**
     * Relation between employee and their salaries
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function salaries()
    {
        return $this->hasMany(Salary::class, 'emp_no', 'emp_no');
    }

    /**
     * Relation between employee and their titles
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function titles()
    {
        return $this->hasMany(Title::class, 'emp_no', 'emp_no');
    }

    /**
     * Relation between employee and their departments
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function departments()
    {
        return $this->hasManyThrough(Department::class, Dept_employee::class, 
            'emp_no', // Foreign key on the Dept_employee table
            'dept_no', // Local key on the Employee table
            'emp_no', // Local key on the Department table
            'dept_no' // Foreign key on the Dept_employee table
        );
    }

    /**
     * Get current salary of an employee
     *
     * @return string salary
     */
    public function currSalary()
    {
        return ($this->salaries()->orderBy('to_date','desc')->take(1)->select('salary')->get()->toArray()) [0]['salary'];
    }

    /**
     * Get current title of an employee
     *
     * @return string title
     */
    public function currTitle()
    {
        return ($this->titles()->orderBy('to_date','desc')->take(1)->select('title')->get()->toArray()) [0]['title'];
    } 
}
