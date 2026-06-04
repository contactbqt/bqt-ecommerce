<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserExport implements FromCollection, WithHeadings, WithMapping
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
        return User::select('id', 'name', 'community_id', 'created_at')
        ->where(function ($query) {
            if ($this->searchName) {
                $query->where('name', 'like', '%' . $this->searchName . '%');
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

    public function map($user): array
    {
        return [
            ++$this->slNo,
            $user->id,
            $user->name,
            $user->community ? $user->community->name : 'N/A',
            $user->created_at->format('d M Y, h:i A'),
        ];
    }

    public function headings(): array
    {
        return [
            'SL No',
            'Row ID',
            'Name',
            'Community',
            'Created At',
        ];
    }

}
