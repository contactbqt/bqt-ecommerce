<?php

namespace App\Livewire\Admin\Import;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class TemplateExport implements FromCollection, WithHeadings
{
    protected $headings;

    public function __construct(array $headings)
    {
        $this->headings = $headings;
    }

    public function collection()
    {
        return new Collection();
    }

    public function headings(): array
    {
        return $this->headings;
    }
}
