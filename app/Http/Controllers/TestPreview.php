<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use League\Csv\Reader;

class TestPreview extends Controller
{
    public function preview(Request $request)
    {
        $path = $request->file('file')->getRealPath();
        $csv = Reader::createFromPath($path, 'r');
        // dd($csv->fetchOne());
        $header = $csv->fetchOne();
        // $records = $csv->setHeaderOffset(0)->fetchAll();
        $csv->setHeaderOffset(0); // use the first line as headers for rows
        $row = $csv->getRecords();
        foreach ($row as $rows) {
            var_dump($rows);
            // foreach($rows as $item){
            //     var_dump($item);
            // }
        }
        dd($row);

        return view('import.preview', [
            'header' => $header,
            'records' => $records,
        ]);
    }
}
