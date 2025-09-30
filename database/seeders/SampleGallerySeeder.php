<?php

namespace Database\Seeders;

use App\Models\Gallery;
use App\Models\Branch;
use Illuminate\Database\Seeder;

class SampleGallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branch = Branch::first();
        
        if (!$branch) {
            return;
        }

        $galleryItems = [
            [
                'title' => 'Modern Cardio Equipment',
                'description' => 'State-of-the-art cardio machines for all fitness levels',
                'image_path' => 'gallery/cardio-equipment.jpg',
                'type' => 'image',
                'category' => 'equipment',
                'branch_id' => $branch->id,
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Weight Training Area',
                'description' => 'Comprehensive weight training facility with free weights and machines',
                'image_path' => 'gallery/weight-training.jpg',
                'type' => 'image',
                'category' => 'equipment',
                'branch_id' => $branch->id,
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Group Fitness Studio',
                'description' => 'Spacious studio for group fitness classes and personal training',
                'image_path' => 'gallery/group-fitness.jpg',
                'type' => 'image',
                'category' => 'gym',
                'branch_id' => $branch->id,
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'Locker Rooms',
                'description' => 'Clean and modern locker rooms with premium amenities',
                'image_path' => 'gallery/locker-rooms.jpg',
                'type' => 'image',
                'category' => 'gym',
                'branch_id' => $branch->id,
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'title' => 'Swimming Pool',
                'description' => 'Olympic-size swimming pool for lap swimming and aqua fitness',
                'image_path' => 'gallery/swimming-pool.jpg',
                'type' => 'image',
                'category' => 'gym',
                'branch_id' => $branch->id,
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'title' => 'Yoga Studio',
                'description' => 'Peaceful yoga studio with natural lighting and calming atmosphere',
                'image_path' => 'gallery/yoga-studio.jpg',
                'type' => 'image',
                'category' => 'gym',
                'branch_id' => $branch->id,
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'title' => 'Functional Training Zone',
                'description' => 'Dedicated area for functional training and HIIT workouts',
                'image_path' => 'gallery/functional-training.jpg',
                'type' => 'image',
                'category' => 'equipment',
                'branch_id' => $branch->id,
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'title' => 'Reception Area',
                'description' => 'Welcoming reception area with friendly staff and modern design',
                'image_path' => 'gallery/reception.jpg',
                'type' => 'image',
                'category' => 'gym',
                'branch_id' => $branch->id,
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 8,
            ],
        ];

        foreach ($galleryItems as $itemData) {
            Gallery::firstOrCreate(
                ['title' => $itemData['title']],
                $itemData
            );
        }
    }
}
