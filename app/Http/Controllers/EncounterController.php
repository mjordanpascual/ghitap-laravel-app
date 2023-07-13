<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EncounterController extends Controller
{
    public function index(Request $request)
    {
        $per_page = $request->input('per_page') ?? 10;
        $attributes = $request->except(['per_page', 'page', 'date', 'hospital_number']);

        $date = $request->input('date') ?? date('Y-m-d');

        $encounters = DB::table('encounters')
            ->join('patients', 'encounters.hospital_number', '=', 'patients.hospital_number')
            ->join('departments', 'encounters.department_code', '=', 'departments.code')
            ->select(
                'encounters.*',
                'patients.patlast', 'patients.patfirst', 'patients.patmiddle', 'patients.patsex', 'patients.patsuffix', 'patients.patbdate',
                'departments.name as department_name'
            )
            ->where($attributes)
            ->where(function ($query) use ($request, $date) {
                if($request->has('hospital_number')) {
                    $query->where('encounters.hospital_number', $request->input('hospital_number'));
                } else {
                    // $query->where(DB::raw('cast(timestamp as date)'), $date);
                    $query->whereDate('timestamp', $date);
                }
            })
            ->paginate($per_page);

        return response()->json($encounters);
    }

    public function store(Request $request)
    {
        $request->validate([
            'hospital_number' => 'required|exists:patients,hospital_number',
            'department_code' => 'required|exists:departments,code',
            'type' => 'nullable|in:R,T',

            // 'hospital_number' => [
            //     'required',
            //     'exists:patients,hospital_number',
            // ]
        ]);

        $id = DB::table('encounters')->insertGetId([
            'hospital_number' => $request->input('hospital_number'),
            'department_code' => $request->input('department_code'),
            'timestamp' => now(),
            'type' => $request->input('type'),
        ]);

        return response()->json([
            'message' => 'Encounter created successfully',
            'data' => $id,
        ], 201);
    }
}