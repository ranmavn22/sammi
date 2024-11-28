<?php


namespace App\Services;


class ExportService
{
    private $storeNameKey = 1;
    private $numberKey = 9;

    private function getKey($item)
    {
        return trim($item[4]) . (string)$this->convertDateTime($item[5]);
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
        foreach ($dataRaw as $index => $item) {
            if (!empty($item) && count($item) >= 15 && $item[1]) {
                $key = $this->getKey($item);

                $storeName = $item[$this->storeNameKey];

                if(!(int) $item[$this->numberKey]) {
                    dd($index, $item[0], $item[1], (int) $item[$this->numberKey], $item[$this->numberKey]);
                }

                if (!empty($storeCount[$storeName][$key])) {
                    $storeCount[$storeName][$key] += (int) $item[$this->numberKey];
                } else {
                    $storeCount[$storeName][$key] = (int) $item[$this->numberKey];
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
            $item[5] = $this->convertDateTime($item[5]);
            $item[7] = $this->convertDateTime($item[7]);

            $dataFinal[] = $item;
        }

        return array_merge([$header], $dataFinal);
    }
}
