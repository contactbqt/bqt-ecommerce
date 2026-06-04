<?php

namespace App\Exports;

use App\Models\Instruction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InstructionExport implements FromCollection, WithHeadings, WithMapping
{
    protected $searchName;
    protected $searchCommunity;
    protected $slNo = 0;

    public function __construct($searchName = null, $searchCommunity = null)
    {
        $this->searchName = $searchName;
        $this->searchCommunity = $searchCommunity;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Instruction::select('id', 'instruction_text', 'community_id', 'created_at')
        ->where(function ($query) {
            if ($this->searchName) {
                $query->where('instruction_text', 'like', '%' . $this->searchName . '%');
            }
        })
        ->where(function ($query) {
            if ($this->searchCommunity) {
                $query->where('community_id', $this->searchCommunity);
            }
        })
        ->orderBy('created_at', 'desc')
        ->get();
    }

    public function map($instruction): array
    {
        return [
            ++$this->slNo,
            $instruction->id,
            $instruction->instruction_text,
            $instruction->community ? $instruction->community->name : 'N/A',
            $instruction->created_at->format('d M Y, h:i A'),
        ];
    }

    public function headings(): array
    {
        return [
            'SL No',
            'Row ID',
            'Instruction Text',
            'Community',
            'Created At',
        ];
    }

}
