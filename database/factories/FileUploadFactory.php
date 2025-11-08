<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FileUpload>
 */
class FileUploadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $filename = fake()->word() . '.csv';
        
        return [
            'original_filename' => $filename,
            'stored_filename' => time() . '_' . $filename,
            'file_path' => 'uploads/' . time() . '_' . $filename,
            'file_hash' => fake()->md5(),
            'status' => fake()->randomElement(['pending', 'processing', 'completed', 'failed']),
            'total_rows' => fake()->numberBetween(10, 1000),
            'processed_rows' => fake()->numberBetween(0, 1000),
            'error_message' => null,
        ];
    }
}
