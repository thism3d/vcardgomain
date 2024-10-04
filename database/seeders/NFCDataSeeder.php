<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\NFCCard;
class NFCDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nfcData = [
            [
                'card_name'=>'NFC Card 1',
                'price'=>"10",
                'image'=>'nfc1.png',
                'created_by'=>1,
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                'card_name'=>'NFC Card 2',
                'price'=>"10",
                'image'=>'nfc2.png',
                'created_by'=>1,
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                'card_name'=>'NFC Card 3',
                'price'=>"10",
                'image'=>'nfc3.png',
                'created_by'=>1,
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                'card_name'=>'NFC Card 4',
                'price'=>"10",
                'image'=>'nfc4.png',
                'created_by'=>1,
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                'card_name'=>'NFC Card 5',
                'price'=>"10",
                'image'=>'nfc5.png',
                'created_by'=>1,
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                'card_name'=>'NFC Card 6',
                'price'=>"10",
                'image'=>'nfc6.png',
                'created_by'=>1,
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                'card_name'=>'NFC Card 7',
                'price'=>"10",
                'image'=>'nfc7.png',
                'created_by'=>1,
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                'card_name'=>'NFC Card 8',
                'price'=>"10",
                'image'=>'nfc8.png',
                'created_by'=>1,
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                'card_name'=>'NFC Card 9',
                'price'=>"10",
                'image'=>'nfc9.png',
                'created_by'=>1,
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                'card_name'=>'NFC Card 10',
                'price'=>"10",
                'image'=>'nfc10.png',
                'created_by'=>1,
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
        ];
        NFCCard::insert($nfcData);
    }
}
