<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Employee;

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

    /** @var Employee $status */
    protected Employee $employeeModel;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(?string $gender, ?string $department, ?int $min, ?int $max, ?string $status)
    {
        $this->employeeModel = new Employee;
        $this->onQueue('emp-updates');
        $this->initFilters($gender, $department, $min, $max, $status);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->employeeModel
            ->getEmployeeTableQuery($this->gender, $this->department, $this->min, $this->max, $this->status)
            ->update();
    }

    /**
     * Init filters on construct
     * 
     * @return void
     */
    protected function initFilters(?string $gender, ?string $department, ?int $min, ?int $max, ?string $status)
    {
        $this->gender = $gender;
        $this->department = $department;
        $this->min = $min;
        $this->max = $max;
        $this->status = $status;
    }
}
