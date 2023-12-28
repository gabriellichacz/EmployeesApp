<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Jobs\UpdateEmployees;
use Illuminate\Http\Request;

class UpdatesController extends Controller
{
    /**
     * Employee updates
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() : \Illuminate\Contracts\Support\Renderable
    {
        return view('updates.index', [
            'dep_names' => $this->depNames()
        ]);
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

    public function addUpdateRule(Request $request)
    {
        $max = null;
        $min = null;
        $gender = null;
        $status = null;
        $department = null;
        $updateRule = [];

        if ($request->has('max_salary'))
        {
            $max = intval($request->max_salary);
        }

        if ($request->has('min_salary'))
        {
            $min = intval($request->min_salary);
        }

        if ($request->has('gender'))
        {
            $gender = $request->gender;
        }

        if ($request->has('status'))
        {
            $status = $request->status;
        }

        if ($request->has('department'))
        {
            $department = $request->department;
        }

        if ($request->has('raise_perc'))
        {
            $updateRule = [
                'rule' => 'raise_perc',
                'field' => 'salary',
                'value' => $request->raise_perc
            ];
        }

        if ($request->has('raise_const'))
        {
            $updateRule = [
                'rule' => 'raise_const',
                'field' => 'salary',
                'value' => $request->raise_const
            ];
        }

        if ($request->has('job_title'))
        {
            $updateRule = [
                'rule' => 'job_title',
                'field' => 'title',
                'value' => $request->job_title
            ];
        }

        $updateQueue = new UpdateEmployees($gender, $department, $min, $max, $status, $updateRule);
        $updateQueue->dispatch();
    }
}
