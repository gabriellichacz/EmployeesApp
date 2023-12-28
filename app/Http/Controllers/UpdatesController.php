<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UpdatesController extends Controller
{

    /**
     * Construct function
     */
    public function __construct()
    {
        
    }

    public function index()
    {
        return view('updates.index');
    }
}
