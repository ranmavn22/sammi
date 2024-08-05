<?php


namespace App\Services;


class ExportService
{
    private function getKey($item)
    {
        return $item[3] . (string)$this->convertDateTime($item[6]);
    }

    public function convertDateTime($dateTime)
    {
        if (is_numeric($dateTime))
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($dateTime)->format('m/Y');

        return $dateTime;
    }

    public function prepareDataExprire($dataRaw)
    {
        $storeCount = [];
        foreach ($dataRaw as $item) {
            if (!empty($item) && count($item) >= 15) {
                $key = $this->getKey($item);
                $storeName = $item[1];

                if (!empty($storeCount[$storeName][$key])) {
                    $storeCount[$storeName][$key] += (int) $item[10];
                } else {
                    $storeCount[$storeName][$key] = (int) $item[10];
                }
            }
        }

        $header = array_fill(0, count($dataRaw[0]), null);
        foreach ($storeCount as $storeName => $value){
            $header[] = $storeName;
        }

        $dataFinal = [];
        $keyExits = [];

        foreach ($dataRaw as &$item) {
            $key = $this->getKey($item);
            if (in_array($key, $keyExits)) {
                continue;
            }
            $keyExits[] = $key;
            $values = [];
            foreach ($storeCount as $store) {
                $values[] = (string)($store[$key] ?? 0);
            }

            $item = array_merge($item, $values);
            $item[6] = $this->convertDateTime($item[6]);
            $item[8] = $this->convertDateTime($item[8]);

            $dataFinal[] = $item;
        }

        return array_merge([$header], $dataFinal);
    }
}
