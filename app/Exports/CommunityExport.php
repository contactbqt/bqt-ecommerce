<?php

namespace App\Exports;

use App\Models\Community;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CommunityExport implements FromCollection, WithHeadings, WithMapping
{
    protected $search;
    protected $slNo = 0;

    public function __construct($search = null)
    {
        $this->search = $search;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Community::select('id', 'name', 'created_at')
        ->where(function ($query) {
            if ($this->search) {
                $query->where('name', 'like', '%' . $this->search . '%');
            }
        })
        ->orderBy('created_at', 'desc')
        ->get();
    }

    public function map($comumunity): array
    {
        return [
            ++$this->slNo,
            $comumunity->id,
            $comumunity->name,
            $comumunity->created_at->format('d M Y, h:i A'),
        ];
    }

    public function headings(): array
    {
        return [
            'SL No',
            'Row ID',
            'Name',
            'Created At',
        ];
    }

}
