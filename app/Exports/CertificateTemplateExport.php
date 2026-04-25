<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CertificateTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new CertificateDataSheet(),
            new CertificateInstructionsSheet(),
        ];
    }
}

class CertificateDataSheet implements FromArray, WithHeadings, WithStyles, WithColumnWidths, WithTitle
{
    public function title(): string
    {
        return 'Certificates';
    }

    public function headings(): array
    {
        return [
            'student_email',     // Required
            'course_id',         // Required
            'batch_id',          // Optional
            'title',             // Optional
            'issue_date',        // Optional — YYYY-MM-DD
            'grade',             // Optional
            'certificate_code',  // Optional — leave blank to auto-generate
        ];
    }

    public function array(): array
    {
        return [
            // Example 1 — complete historical certificate with custom serial
            [
                'ahmed.ali@example.com',
                1,
                1,
                'شهادة إتمام الدورة',
                '2024-06-15',
                'ممتاز',
                'INSEP-2024-001',
            ],
            // Example 2 — with grade, no batch (course-level certificate)
            [
                'fatima.hassan@example.com',
                2,
                '',
                'شهادة إتمام الدورة',
                '2024-09-01',
                'جيد جداً',
                'INSEP-2024-002',
            ],
            // Example 3 — no grade, with batch, auto serial
            [
                'omar.khalid@example.com',
                1,
                2,
                'شهادة إتمام الدورة',
                '2024-11-20',
                '',
                '',
            ],
            // Example 4 — minimal data (only required fields)
            [
                'sara.nasser@example.com',
                3,
                '',
                '',
                '',
                '',
                '',
            ],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        // Header row
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold'  => true,
                'size'  => 11,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1A3A5C'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
        ]);

        // Example rows — alternate shading
        $sheet->getStyle('A2:G5')->applyFromArray([
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F0F4FF'],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        $sheet->getStyle('A3:G3')->applyFromArray([
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E8EFF9'],
            ],
        ]);
        $sheet->getStyle('A5:G5')->applyFromArray([
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E8EFF9'],
            ],
        ]);

        // Thin border around data
        $sheet->getStyle('A1:G5')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => 'D0D9E8'],
                ],
            ],
        ]);

        // Row height
        $sheet->getRowDimension(1)->setRowHeight(22);

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 34, // student_email
            'B' => 14, // course_id
            'C' => 14, // batch_id
            'D' => 34, // title
            'E' => 18, // issue_date
            'F' => 18, // grade
            'G' => 26, // certificate_code
        ];
    }
}

class CertificateInstructionsSheet implements FromArray, WithStyles, WithColumnWidths, WithTitle
{
    public function title(): string
    {
        return 'Instructions';
    }

    public function array(): array
    {
        return [
            ['Column',            'Required?', 'Format / Notes'],
            ['student_email',     'Required',  'Registered email of the student in the system'],
            ['course_id',         'Required',  'Numeric ID of the course (see Courses page)'],
            ['batch_id',          'Optional',  'Numeric batch ID — leave blank if not applicable'],
            ['title',             'Optional',  'Certificate title — defaults to: شهادة إتمام الدورة'],
            ['issue_date',        'Optional',  'Date format: YYYY-MM-DD — defaults to today if blank'],
            ['grade',             'Optional',  'e.g. ممتاز / جيد جداً / 85% — leave blank if none'],
            ['certificate_code',  'Optional',  'Custom serial number — auto-generated if blank'],
            ['', '', ''],
            ['Notes', '', ''],
            ['• Delete the example rows (rows 2-5) from the Certificates sheet before importing.', '', ''],
            ['• Keep the header row (row 1) exactly as-is — do not rename columns.', '', ''],
            ['• Each imported row automatically generates a PDF certificate.', '', ''],
            ['• Duplicate certificate_code values will be skipped with an error message.', '', ''],
            ['• Rows with an unregistered email or invalid course_id will be skipped.', '', ''],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        // Header row
        $sheet->getStyle('A1:C1')->applyFromArray([
            'font' => [
                'bold'  => true,
                'size'  => 11,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1A3A5C'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Column rows (2-8)
        $sheet->getStyle('A2:C8')->applyFromArray([
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F8FAFF'],
            ],
        ]);

        // Required rows
        foreach ([2, 3] as $row) {
            $sheet->getStyle("B{$row}")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'DC2626']],
            ]);
        }
        // Optional rows
        foreach ([4, 5, 6, 7, 8] as $row) {
            $sheet->getStyle("B{$row}")->applyFromArray([
                'font' => ['color' => ['rgb' => '6B7280']],
            ]);
        }

        // "Notes" label
        $sheet->getStyle('A10')->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '1A3A5C']],
        ]);

        // Note lines
        $sheet->getStyle('A11:A15')->applyFromArray([
            'font' => ['color' => ['rgb' => '374151']],
        ]);

        // Borders on table
        $sheet->getStyle('A1:C8')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => 'D0D9E8'],
                ],
            ],
        ]);

        // Wrap text for notes column
        $sheet->getStyle('C2:C8')->getAlignment()->setWrapText(true);

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 22,
            'B' => 14,
            'C' => 60,
        ];
    }
}
