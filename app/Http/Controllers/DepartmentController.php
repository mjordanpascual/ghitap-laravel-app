<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class DepartmentController extends Controller
{
    //
    // CRUD Operations
    // Get all services

    public function index()
    {
        $services = DB::table('departments')->get();

        return response() ->json($services);
    }

}
