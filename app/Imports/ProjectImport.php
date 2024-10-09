<?php

namespace App\Imports;

use App\Models\Project;
use App\Models\Type;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Calculation\Logical\Boolean;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ProjectImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $typesMap = $this->getTypesMap(Type::all());

        foreach ($collection as $row) {

//            dd($row);

            if (!isset($row['naimenovanie'])) continue;

            Project::create([
                'type_id' =>  $this->getTypeId($typesMap, $row['tip']),
                'title' => $row['naimenovanie'],
                'created_at-time' => Date::excelToDateTimeObject($row['data_sozdaniia']),
                'contracted_at' => Date::excelToDateTimeObject($row['podpisanie_dogovora']),
                'deadline' => Date::excelToDateTimeObject($row['dedlain']),
                'is_chain' => isset($row['setevik']) ? $this->getBool($row['setevik']) : null,
                'is_on_time' => isset($row['sdaca_v_srok']) ? $this->getBool($row['sdaca_v_srok']) : null,
                'has_outsource' => isset($row['nalicie_autsorsinga']) ? $this->getBool($row['nalicie_autsorsinga']) : null,
                'has_investors' => isset($row['nalicie_investorov']) ? $this->getBool($row['nalicie_investorov']) : null,
                'worker_count' => $row['kolicestvo_ucastnikov'],
                'service_count' => $row['kolicestvo_uslug'],
                'payment_first_step' => $row['vlozenie_v_pervyi_etap'],
                'payment_second_step' => $row['vlozenie_vo_vtoroi_etap'],
                'payment_third_step' => $row['vlozenie_v_tretii_etap'],
                'payment_forth_step' => $row['vlozenie_v_cetvertyi_etap'],
                'comment' => $row['kommentarii'],
                'effective_value' => $row['znacenie_effektivnosti'],
            ]);
        }
    }

    private function getTypesMap($types): array
    {
        $map = [];
        foreach($types as $type) {
            $map[$type->title] = $type->id;
        }
        return $map;
    }

    private function getTypeId($map, $title)
    {
        return isset($map[$title]) ? $map[$title] : Type::create(['title' => $title])->id;
    }

    private function getBool($item): bool
    {
        return $item == 'Да';
    }
}
