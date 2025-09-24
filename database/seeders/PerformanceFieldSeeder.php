<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SMS\PerformanceField;

class PerformanceFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fields = [
            [
                'name' => 'Technical Skills',
                'category' => 'Technical Skills',
                'description' => 'Evaluation of technical knowledge and practical skills in relevant areas',
                'max_score' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'Communication Skills',
                'category' => 'Soft Skills',
                'description' => 'Verbal and written communication abilities, clarity and effectiveness',
                'max_score' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'Leadership',
                'category' => 'Leadership',
                'description' => 'Ability to lead, motivate, and guide others effectively',
                'max_score' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'Teamwork',
                'category' => 'Soft Skills',
                'description' => 'Collaboration skills and ability to work effectively in teams',
                'max_score' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'Physical Fitness',
                'category' => 'Physical Fitness',
                'description' => 'Physical endurance, strength, and overall fitness assessments',
                'max_score' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'Problem Solving',
                'category' => 'Soft Skills',
                'description' => 'Analytical thinking, creativity, and problem-solving abilities',
                'max_score' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'Discipline',
                'category' => 'Discipline',
                'description' => 'Adherence to rules, regulations, and professional conduct',
                'max_score' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'Attendance',
                'category' => 'Attendance',
                'description' => 'Punctuality, attendance record, and reliability',
                'max_score' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'Adaptability',
                'category' => 'Soft Skills',
                'description' => 'Ability to adapt to changing situations and environments',
                'max_score' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'Initiative',
                'category' => 'Leadership',
                'description' => 'Proactive approach and willingness to take initiative',
                'max_score' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'Time Management',
                'category' => 'Soft Skills',
                'description' => 'Ability to manage time effectively and meet deadlines',
                'max_score' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'Professional Development',
                'category' => 'Technical Skills',
                'description' => 'Commitment to learning and professional growth',
                'max_score' => 100,
                'is_active' => true,
            ],
        ];

        foreach ($fields as $field) {
            PerformanceField::create($field);
        }
    }
}
