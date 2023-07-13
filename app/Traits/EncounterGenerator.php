<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\Henctr;
use App\Models\Hopdlog;
use App\Models\Hperson;
use Illuminate\Support\Facades\DB;

trait EncounterGenerator {
    // generate encounter code (enccode)
    protected function generateEncounterCode($hpercode, $encdate): string
    {
        return 'OPD'. ltrim($hpercode, '0') . Carbon::parse($encdate)->format('MdYHis');
    }

    // generate encounter (Henctr, Hopdlog)
    protected function generateEncounter($hpercode, $tscode, $priority, $teleconsultation)
    {
        try {
            DB::beginTransaction();

            $encdate = date('Y-m-d H:i:s');
            $enccode = $this->generateEncounterCode($hpercode, $encdate);
            $age = $this->computeAgeInYearsMonthDay($hpercode);
            $disinstruc = $teleconsultation ? 'T' : ($priority == 0 ? 'R' : 'P');
            $filling = Hopdlog::whereRaw("cast(opddate as date) = '" . date('Y-m-d') . "'")
                ->where([
                    ['tscode', $tscode],
                    ['disinstruc', $disinstruc],
                ])
                ->count() + 1;

            Henctr::create([
                'enccode' => $enccode,
                'hpercode' => $hpercode,
                'encdate' => $encdate
            ]);

            Hopdlog::create([
                'enccode' => $enccode,
                'hpercode' => $hpercode,
                'opddate' => $encdate,
                'tscode' => $tscode,
                'opdstat' => 'A',
                'newold' => Hopdlog::where('hpercode', $hpercode)->count() > 0 ? 'O' : 'N',
                'filling' => $filling,
                'datetriage' => $encdate,
                'patage' => $age['year'],
                'disinstruc' => $disinstruc,
            ]);

            DB::commit();

            return $enccode;
        } catch (\Exception $e) {
            DB::rollBack();
            return null;
        }
    }

    // compute age in years, months, days
    protected function computeAgeInYearsMonthDay($hpercode): array
    {
        $person = Hperson::find($hpercode);
        $birthdate = Carbon::parse($person->bdate);
        $now = Carbon::now();
        $age = $birthdate->diff($now);

        return [
            'year' => $age->y,
            'month' => $age->m,
            'day' => $age->d,
        ];
    }
}