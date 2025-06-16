<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Order;

class OrdersExport implements FromCollection, WithHeadings
{
    protected $records;

    // Constructor to accept the records
    public function __construct($records)
    {
        $this->records = $records;
    }

    public function collection()
    {
        // Use the provided records directly instead of passing a query
        return $this->records->get();
    }

    public function headings(): array
    {
        return [
            'ID', 'Order No', 'Table Name', 'Waiter Name', 'Final Value'
        ];
    }
}
