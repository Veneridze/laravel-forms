<?php
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Veneridze\LaravelDocuments\DocumentFactory;
use PhpOffice\PhpSpreadsheet\IOFactory;

Route::middleware('auth')->post('/forms/table/parse', function (Request $request) {
    $request->validate([
        'file' => ['required', 'file', 'mimes:xlsx']
    ]);
    $file = $request->file('file');

    // Загружаем файл с помощью PhpSpreadsheet
    $spreadsheet = IOFactory::load($file->getPathname());

    // Получаем первый лист
    $sheet = $spreadsheet->getActiveSheet();

    // Получаем диапазон ячеек с данными
    $highestRow = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestColumn();
    $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);

    // Извлекаем заголовки (первая строка)
    $headers = [];
    for ($col = 1; $col <= $highestColumnIndex; ++$col) {
        // $headers[] = $sheet->getCellByColumnAndRow($col, 1)->getFormattedValue();
        $cellCoordinate = Coordinate::stringFromColumnIndex($col) . '1'; // Формируем координату ячейки (A1, B1, ...)
        $headers[] = $sheet->getCell($cellCoordinate)->getFormattedValue();
    }

    // Извлекаем данные с учетом форматирования
    $data = [];
    for ($row = 2; $row <= $highestRow; ++$row) {
        $rowData = [];
        for ($col = 1; $col <= $highestColumnIndex; ++$col) {
            // $cell = $sheet->getCellByColumnAndRow($col, $row);
            $cellCoordinate = Coordinate::stringFromColumnIndex($col) . $row; // Формируем координату ячейки (A2, B2, ...)
            $cell = $sheet->getCell($cellCoordinate);
            $rowData[$headers[$col - 1]] = $cell->getFormattedValue();
        }
        $data[] = $rowData;
    }

    // Возвращаем данные в формате JSON
    return response()->json($data);
})->name('forms.table.parse');
