<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Jobs\UpdateEmployees;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function addUpdateRule()
    {
        UpdateEmployees::dispatch();
    }
}
