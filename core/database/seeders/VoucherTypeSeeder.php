<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\backend\VoucherType;

class VoucherTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Sale Voucher',
                'code' => 'SALE',
            ],
            [
                'name' => 'Refund Voucher',
                'code' => 'REFUND',
            ],
            [
                'name' => 'Adjustment Voucher',
                'code' => 'ADJUST',
            ],
        ];

        foreach ($types as $type) {
            VoucherType::updateOrCreate(
                ['code' => $type['code']], // unique key
                ['name' => $type['name']]
            );
        }
    }
}
