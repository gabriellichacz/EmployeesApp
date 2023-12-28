<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Repositories\EmployeeRepository;
use App\Models\Salary;
use App\Models\Title;

class UpdateEmployees implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var string $gender */
    protected string $gender;

    /** @var string $department */
    protected string $department;

    /** @var int $min */
    protected int $min;

    /** @var int $max */
    protected int $max;

    /** @var string $status */
    protected string $status;

    /** @var array $updateRule */
    protected array $updateRule;

    /** @var EmployeeRepository $status */
    protected EmployeeRepository $employeeRepository;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(?string $gender, ?string $department, ?int $min, ?int $max, ?string $status, array $updateRule)
    {
        $this->employeeRepository = new EmployeeRepository;
        $this->onQueue('emp-updates');
        $this->initFilters($gender, $department, $min, $max, $status, $updateRule);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $query = $this->employeeRepository->getEmployeeTableQuery($this->gender, $this->department, $this->min, $this->max, $this->status);
        switch ($this->updateRule['rule'])
        {
            case 'raise_perc':
            case 'raise_const':
                $this->updateSalaries($query, $this->updateRule['rule'], $this->updateRule['value']);
                break;
            case 'job_title':
                $this->updateTitle($query, $this->updateRule['value']);
                break;
        }
    }

    /**
     * Init filters on construct
     * 
     * @return void
     */
    protected function initFilters(?string $gender, ?string $department, ?int $min, ?int $max, ?string $status, array $updateRule)
    {
        $this->gender = $gender;
        $this->department = $department;
        $this->min = $min;
        $this->max = $max;
        $this->status = $status;
        $this->updateRule = $updateRule;
    }

    /**
     * Update salaries
     * 
     * @param \Illuminate\Database\Query\Builder $query
     * @param string $version
     * @param int $raiseAmount
     * @return void
     */
    protected function updateSalaries(\Illuminate\Database\Query\Builder $query, string $version, int $raiseAmount)
    {
        $salaries = $query->pluck('salary', 'emp_no');
        $todayDate = date('Y-m-d');
        foreach ($salaries as $empNo => $salary)
        {
            Salary::where('emp_no', $empNo)->where('from_date', $todayDate)->delete();
            $newSalary = new Salary;
            $newSalary->emp_no = $empNo;
            $newSalary->salary = $this->calculateRaise($version, $raiseAmount, $salary);
            $newSalary->from_date = $todayDate;
            $newSalary->to_date = '9999-01-01';
            $newSalary->save();
        }
    }

    /**
     * Calculate raise of an individual employee 
     * 
     * @param string $raiseVersion
     * @param int $raiseAmount
     * @param int $currentSalary
     * @return int
     */
    private function calculateRaise(string $raiseVersion, int $raiseAmount, int $currentSalary)
    {
        switch ($raiseVersion)
        {
            case 'raise_const':
                $newSalary = $currentSalary + $raiseAmount;
                break;
            case 'raise_perc':
                $newSalary = $currentSalary + ($currentSalary * ($raiseAmount * 0.01));
                break;
        }
        return $newSalary;
    }

    /**
     * Update job titles
     * 
     * @param \Illuminate\Database\Query\Builder $query
     * @param string $title
     * @return void
     */
    protected function updateTitle(\Illuminate\Database\Query\Builder $query, string $title)
    {
        $ids = $query->pluck('emp_no');
        $todayDate = date('Y-m-d');
        foreach ($ids as $id)
        {
            Title::where('emp_no', $id)->where('from_date', $todayDate)->delete();
            $newTitle = new Title;
            $newTitle->emp_no = $id;
            $newTitle->title = $title;
            $newTitle->from_date = $todayDate;
            $newTitle->to_date = '9999-01-01';
            $newTitle->save();
        }
    }
}
