<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    public function check($uid)
    {
        $json = [];
        try {
            $json = File::json(storage_path('app/users.json')) ?? [];
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }

        $exists = in_array($uid, $json);

        return $exists ? response()->json([
            'message' => 'User exists',
        ], 200) : response()->json([
            'message' => 'User does not exist',
        ], 404);
    }
}
