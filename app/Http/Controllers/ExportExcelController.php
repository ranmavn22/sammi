<?php

namespace App\Http\Controllers;

use App\Exports\ExprireDate;
use App\Imports\RawData;
use App\Services\ExportService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportExcelController extends Controller
{
    private $data = [];

    public function __construct(private ExportService $exportService)
    {
    }

    /**
     * Get raw data
     */
    public function export(Request $request)
    {
        $this->data = Excel::toArray(new RawData(), $request->file);

        return $this->expire();
    }

    /**
     * Export ngày sắp hết hạn
     */
    public function expire()
    {
        $maxRecord = 900;

        if (!empty($this->data[0])) {
            $dataExport = $this->exportService->prepareDataExprire($this->data[0]);
//            return Excel::download(new ExprireDate($this->data[0]), 'expire.xlsx');

            $chunks = array_chunk($dataExport, $maxRecord);
            foreach ($chunks as $index => $chunk) {
                $export = new ExprireDate($chunk);
                Excel::store($export, 'expire-' . date('d-m-Y') . $index . '.xlsx', 'public');
            }
        }

        return to_route('home');
    }
}
