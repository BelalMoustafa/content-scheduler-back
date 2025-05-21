<?php

namespace Modules\Platforms\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Platforms\Models\Platform;
use Illuminate\Support\Facades\Log;

class PlatformSeeder extends Seeder
{
    public function run(): void
    {
        $platforms = [
            [
                'name' => 'Twitter',
                'type' => 'twitter'
            ],
            [
                'name' => 'Instagram',
                'type' => 'instagram'
            ],
            [
                'name' => 'LinkedIn',
                'type' => 'linkedin'
            ],
            [
                'name' => 'Facebook',
                'type' => 'facebook'
            ]
        ];

        foreach ($platforms as $platform) {
            Platform::create($platform);
        }

        Log::info('Platforms seeded successfully');
    }
}
