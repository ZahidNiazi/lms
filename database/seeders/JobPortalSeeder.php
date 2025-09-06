<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobPortalApplication;
use App\Models\NotificationTemplate;
use App\Models\InterviewLocation;
use App\Models\TrainingBatch;
use App\Models\Student;
use App\Models\User;

class JobPortalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample notification templates
        $this->createNotificationTemplates();
        
        // Create sample interview locations
        $this->createInterviewLocations();
        
        // Create sample training batches
        $this->createTrainingBatches();
        
        // Create sample job portal applications for existing students
        $this->createJobPortalApplications();
    }

    private function createNotificationTemplates()
    {
        // Skip notification templates for now since the existing table has different structure
        // We'll use the existing notification_templates table structure
        $templates = [
            [
                'name' => 'Application Status Update - Email',
                'slug' => 'application_status_update_email'
            ],
            [
                'name' => 'Interview Scheduled - Email',
                'slug' => 'interview_scheduled_email'
            ],
            [
                'name' => 'Application Selected - Email',
                'slug' => 'application_selected_email'
            ],
            [
                'name' => 'Batch Assigned - Email',
                'slug' => 'batch_assigned_email'
            ]
        ];

        foreach ($templates as $template) {
            \App\Models\NotificationTemplates::create($template);
        }
    }

    private function createInterviewLocations()
    {
        $locations = [
            [
                'name' => 'National Service Training Center - Male',
                'address' => 'Hulhumale, Male City',
                'city' => 'Male',
                'atoll' => 'Kaafu',
                'contact_person' => 'Ahmed Ali',
                'contact_phone' => '+960 123-4567',
                'contact_email' => 'male.center@nationalservice.mv',
                'capacity' => 100,
                'available_facilities' => ['parking', 'accommodation', 'cafeteria', 'medical_room'],
                'is_active' => true,
                'created_by' => 1
            ],
            [
                'name' => 'National Service Training Center - Addu',
                'address' => 'Hithadhoo, Addu City',
                'city' => 'Addu',
                'atoll' => 'Seenu',
                'contact_person' => 'Aminath Hassan',
                'contact_phone' => '+960 234-5678',
                'contact_email' => 'addu.center@nationalservice.mv',
                'capacity' => 75,
                'available_facilities' => ['parking', 'accommodation', 'cafeteria'],
                'is_active' => true,
                'created_by' => 1
            ],
            [
                'name' => 'National Service Training Center - Kulhudhuffushi',
                'address' => 'Kulhudhuffushi, Haa Dhaalu',
                'city' => 'Kulhudhuffushi',
                'atoll' => 'Haa Dhaalu',
                'contact_person' => 'Ibrahim Mohamed',
                'contact_phone' => '+960 345-6789',
                'contact_email' => 'kulhudhuffushi.center@nationalservice.mv',
                'capacity' => 50,
                'available_facilities' => ['parking', 'cafeteria'],
                'is_active' => true,
                'created_by' => 1
            ]
        ];

        foreach ($locations as $location) {
            InterviewLocation::create($location);
        }
    }

    private function createTrainingBatches()
    {
        $batches = [
            [
                'batch_name' => 'Batch 2025-01',
                'batch_code' => 'NS-2025-01',
                'start_date' => now()->addDays(30)->toDateString(),
                'end_date' => now()->addDays(120)->toDateString(),
                'capacity' => 350,
                'enrolled_count' => 0,
                'status' => 'active',
                'description' => 'First batch of 2025 National Service Training'
            ],
            [
                'batch_name' => 'Batch 2025-02',
                'batch_code' => 'NS-2025-02',
                'start_date' => now()->addDays(90)->toDateString(),
                'end_date' => now()->addDays(180)->toDateString(),
                'capacity' => 350,
                'enrolled_count' => 0,
                'status' => 'active',
                'description' => 'Second batch of 2025 National Service Training'
            ]
        ];

        foreach ($batches as $batch) {
            TrainingBatch::create($batch);
        }
    }

    private function createJobPortalApplications()
    {
        // Get existing students with profiles
        $students = Student::has('profile')->with('profile')->get();
        
        foreach ($students as $student) {
            // Create job portal application if it doesn't exist
            if (!$student->jobPortalApplication) {
                $application = JobPortalApplication::create([
                    'student_id' => $student->id,
                    'application_number' => 'NS-' . date('Y') . '-' . str_pad($student->id, 4, '0', STR_PAD_LEFT),
                    'status' => 'pending_review',
                    'documents_verified' => false,
                    'basic_criteria_met' => false
                ]);

                // Randomly assign some applications to different statuses for demo
                $statuses = ['pending_review', 'document_review', 'approved', 'rejected', 'interview_scheduled', 'selected'];
                $randomStatus = $statuses[array_rand($statuses)];
                
                if ($randomStatus !== 'pending_review') {
                    $application->update([
                        'status' => $randomStatus,
                        'reviewed_by' => 1,
                        'reviewed_at' => now()->subDays(rand(1, 30))
                    ]);
                }
            }
        }
    }
}