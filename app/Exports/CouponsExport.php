<?php

namespace App\Exports;

use App\Models\Coupon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CouponsExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Coupon::select(
            'name',
            'code',
            'discount',
            'limit',
            'type',
            'minimum_spend',
            'maximum_spend',
            'per_user_limit',
            'expiry_date',
            'is_active'
        )->get();
    }

    public function headings(): array
    {
        return [
            'Name',
            'Code',
            'Discount',
            'Limit',
            'Type',
            'Minimum Spend',
            'Maximum Spend',
            'Per User Limit',
            'Expiry Date',
            'Is Active',
        ];
    }
}
