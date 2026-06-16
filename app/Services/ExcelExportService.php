<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExcelExportService
{
    /**
     * Build an .xlsx file from a sheet definition and stream it as a download.
     *
     * $sheets = [
     *     'Sheet Title' => [
     *         'headerRows' => [
     *             [['label' => 'Kode', 'colspan' => 1, 'rowspan' => 1, 'bg' => 'F59E0B'], ...],
     *             ...
     *         ],
     *         'rows' => [[...], [...]],
     *         'colWidths' => [20, 40, ...],
     *     ],
     * ]
     */
    public function download(string $filename, array $sheets): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);

        $index = 0;
        foreach ($sheets as $title => $config) {
            $sheet = $spreadsheet->createSheet($index);
            $sheet->setTitle($this->sanitizeSheetTitle((string) $title));

            $headerRows = $config['headerRows'] ?? [];
            $rows = $config['rows'] ?? [];
            $colWidths = $config['colWidths'] ?? [];

            $currentRow = 1;
            $occupied = [];

            foreach ($headerRows as $headerRow) {
                $col = 1;
                foreach ($headerRow as $cell) {
                    while (isset($occupied[$currentRow][$col])) {
                        $col++;
                    }

                    $colspan = max(1, (int) ($cell['colspan'] ?? 1));
                    $rowspan = max(1, (int) ($cell['rowspan'] ?? 1));
                    $label = $cell['label'] ?? '';
                    $bg = $cell['bg'] ?? null;

                    $startCol = $col;
                    $endCol = $col + $colspan - 1;
                    $startCoord = Coordinate::stringFromColumnIndex($startCol) . $currentRow;
                    $endCoord = Coordinate::stringFromColumnIndex($endCol) . ($currentRow + $rowspan - 1);

                    $sheet->setCellValue($startCoord, $label);
                    if ($colspan > 1 || $rowspan > 1) {
                        $sheet->mergeCells("{$startCoord}:{$endCoord}");
                    }

                    $style = $sheet->getStyle("{$startCoord}:{$endCoord}");
                    $style->getFont()->setBold(true);
                    $style->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                        ->setVertical(Alignment::VERTICAL_CENTER)
                        ->setWrapText(true);
                    $style->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                    if ($bg) {
                        $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($bg);
                        $style->getFont()->getColor()->setRGB('FFFFFF');
                    }

                    for ($r = $currentRow; $r <= $currentRow + $rowspan - 1; $r++) {
                        for ($c = $startCol; $c <= $endCol; $c++) {
                            $occupied[$r][$c] = true;
                        }
                    }

                    $col = $endCol + 1;
                }
                $currentRow++;
            }

            $totalCols = 0;
            foreach ($occupied as $rowCols) {
                $totalCols = max($totalCols, max(array_keys($rowCols)));
            }

            foreach ($rows as $row) {
                $col = 1;
                foreach ($row as $value) {
                    $coord = Coordinate::stringFromColumnIndex($col) . $currentRow;
                    $sheet->setCellValue($coord, $value);
                    $totalCols = max($totalCols, $col);
                    $col++;
                }
                $currentRow++;
            }

            if ($totalCols > 0 && $currentRow > 1) {
                $range = 'A1:' . Coordinate::stringFromColumnIndex($totalCols) . ($currentRow - 1);
                $sheet->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            }

            foreach ($colWidths as $idx => $width) {
                $colLetter = Coordinate::stringFromColumnIndex($idx + 1);
                $sheet->getColumnDimension($colLetter)->setWidth($width);
            }

            $sheet->getStyle('A1:' . Coordinate::stringFromColumnIndex(max($totalCols, 1)) . max($currentRow - 1, 1))
                ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            $index++;
        }

        if ($spreadsheet->getSheetCount() === 0) {
            $spreadsheet->createSheet();
        }
        $spreadsheet->setActiveSheetIndex(0);

        $writer = new Xlsx($spreadsheet);

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    private function sanitizeSheetTitle(string $title): string
    {
        $title = preg_replace('/[\\\\\/\?\*\[\]:]/', '', $title) ?? $title;

        return mb_substr($title, 0, 31);
    }
}
