<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class AddHospitalUser extends Command
{
    protected $signature = 'user:add {uid}';

    protected $description = 'Add Hospital User';

    public function handle(): void
    {
        $json = [];
        try {
            $json = File::json(storage_path('app/users.json')) ?? [];
        } catch (\Exception $e) {
            $json = [];
        }

        $uid = $this->argument('uid');
        array_push($json, $uid);

        File::put(storage_path('app/users.json'), json_encode($json));
    }
}
