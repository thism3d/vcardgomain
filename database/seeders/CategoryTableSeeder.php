<?php

namespace Database\Seeders;

use App\Models\BusinessCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoryData = [
            [
                'name'=>'It Developer',
                'logo'=>'category1.png',
                'created_by'=>1,
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                'name'=>'Car Mechanic',
                'logo'=>'category2.png',
                'created_by'=>1,
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                'name'=>'Photo Studio',
                'logo'=>'category3.png',
                'created_by'=>1,
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                'name'=>'Wedding',
                'logo'=>'category4.png',
                'created_by'=>1,
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                'name'=>'Lawyer',
                'logo'=>'category5.png',
                'created_by'=>1,
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                'name'=>'Doctor',
                'logo'=>'category6.png',
                'created_by'=>1,
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                'name'=>'Influencer',
                'logo'=>'category7.png',
                'created_by'=>1,
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                'name'=>'Model',
                'logo'=>'category8.png',
                'created_by'=>1,
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                'name'=>'Graphic Design',
                'logo'=>'category9.png',
                'created_by'=>1,
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
        ];
        BusinessCategory::insert($categoryData);
    }
}
