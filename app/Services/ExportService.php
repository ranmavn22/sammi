<?php


namespace App\Services;


class ExportService
{
    private function getKey($item)
    {
        return $item[2] . (string)$this->convertDateTime($item[5]);
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
                    $storeCount[$storeName][$key] += (int) $item[9];
                } else {
                    $storeCount[$storeName][$key] = (int) $item[9];
                }
            }
        }

        $header = array_fill(0, count($dataRaw[0]), null);
        foreach ($storeCount as $storeName => $value){
            $header[] = $storeName;
        }

        foreach ($dataRaw as &$item) {
            $key = $this->getKey($item);
            $values = [];
            foreach ($storeCount as $store) {
                $values[] = (string)($store[$key] ?? 0);
            }

            $item = array_merge($item, $values);
            $item[5] = $this->convertDateTime($item[5]);
            $item[7] = $this->convertDateTime($item[7]);
        }

        return array_merge([$header], $dataRaw);
    }
}
