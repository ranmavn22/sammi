<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExprireDate implements FromArray, WithChunkReading
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    private function prepareData()
    {
        $result = [];

        foreach ($this->data as $item) {
            if (!empty($item) && count($item) >= 15) {
                $key = $this->getKey($item);
                $storeName = $item[1];

                if (!empty($result[$storeName][$key])) {
                    $result[$storeName][$key] += (int) $item[9];
                } else {
                    $result[$storeName][$key] = (int) $item[9];
                }
            }

        }

        return $result;
    }

    private function getKey($item)
    {
        return $item[1] . $item[2] . $item[5];
    }

    public function array(): array
    {
        $storeCount = $this->prepareData();
        $header = array_fill(0, count($this->data[0]), null);
        foreach ($storeCount as $storeName => $value){
            $header[] = $storeName;
        }

        foreach ($this->data as &$item) {
            $key = $this->getKey($item);
            $values = [];
            foreach ($storeCount as $store) {
                $values[] = (string)($store[$key] ?? 0);
            }

            $item = array_merge($item, $values);
        }

        return array_merge([$header], $this->data);
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
