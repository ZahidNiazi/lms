<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SMS\TrainingBatch;
use App\Models\SMS\Student;
use App\Models\SMS\LeaveType;
use App\Models\SMS\PerformanceField;
use App\Models\SMS\Subject;

class SMSSampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Training Batches
        $batch1 = TrainingBatch::create([
            'batch_name' => 'Batch 2025-001',
            'batch_code' => 'B2025-001',
            'start_date' => now()->addDays(30),
            'end_date' => now()->addDays(120),
            'capacity' => 350,
            'current_students' => 0,
            'status' => 'upcoming',
            'description' => 'First batch of 2025 National Service Training'
        ]);

        $batch2 = TrainingBatch::create([
            'batch_name' => 'Batch 2025-002',
            'batch_code' => 'B2025-002',
            'start_date' => now()->addDays(60),
            'end_date' => now()->addDays(150),
            'capacity' => 350,
            'current_students' => 0,
            'status' => 'upcoming',
            'description' => 'Second batch of 2025 National Service Training'
        ]);

        // Create Leave Types
        $leaveTypes = [
            ['name' => 'Annual Leave', 'description' => 'Annual vacation leave', 'max_days_per_year' => 30, 'requires_approval' => true],
            ['name' => 'Sick Leave', 'description' => 'Medical leave for illness', 'max_days_per_year' => 15, 'requires_approval' => true],
            ['name' => 'Emergency Leave', 'description' => 'Emergency family situations', 'max_days_per_year' => 5, 'requires_approval' => true],
            ['name' => 'Study Leave', 'description' => 'Leave for educational purposes', 'max_days_per_year' => 10, 'requires_approval' => true],
            ['name' => 'Maternity Leave', 'description' => 'Maternity leave for female students', 'max_days_per_year' => 90, 'requires_approval' => true],
        ];

        foreach ($leaveTypes as $leaveType) {
            LeaveType::create($leaveType);
        }

        // Create Performance Fields
        $performanceFields = [
            ['name' => 'Physical Fitness', 'description' => 'Physical training and fitness assessment', 'max_score' => 100, 'category' => 'Physical'],
            ['name' => 'Discipline', 'description' => 'Behavior and discipline assessment', 'max_score' => 100, 'category' => 'Behavioral'],
            ['name' => 'Leadership', 'description' => 'Leadership skills and initiative', 'max_score' => 100, 'category' => 'Skills'],
            ['name' => 'Teamwork', 'description' => 'Collaboration and team spirit', 'max_score' => 100, 'category' => 'Skills'],
            ['name' => 'Academic Performance', 'description' => 'Classroom and theoretical knowledge', 'max_score' => 100, 'category' => 'Academic'],
        ];

        foreach ($performanceFields as $field) {
            PerformanceField::create($field);
        }

        // Create Subjects
        $subjects = [
            ['name' => 'National Service Fundamentals', 'code' => 'NSF101', 'description' => 'Basic principles of national service', 'credits' => 3],
            ['name' => 'Physical Training', 'code' => 'PT101', 'description' => 'Physical fitness and training', 'credits' => 2],
            ['name' => 'Discipline and Leadership', 'code' => 'DL101', 'description' => 'Military discipline and leadership skills', 'credits' => 2],
            ['name' => 'First Aid and Safety', 'code' => 'FAS101', 'description' => 'Basic first aid and safety procedures', 'credits' => 1],
            ['name' => 'Communication Skills', 'code' => 'CS101', 'description' => 'Effective communication and reporting', 'credits' => 2],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }

        // Create Sample Students
        $students = [
            [
                'student_id' => 'NS2025-0001',
                'rank' => 'Recruit',
                'first_name' => 'Ahmed',
                'last_name' => 'Hassan',
                'name_in_dhivehi' => 'އަހްމަދު ހަސަން',
                'email' => 'ahmed.hassan@example.com',
                'national_id' => 'A123456',
                'permanent_address_name' => 'Home Address',
                'permanent_atoll' => 'Male',
                'permanent_island' => 'Male',
                'permanent_district' => 'Henveiru',
                'permanent_road_name' => 'Majeedhee Magu',
                'present_address_name' => 'Training Camp',
                'present_atoll' => 'Male',
                'present_island' => 'Male',
                'present_district' => 'Henveiru',
                'present_road_name' => 'Training Road',
                'contact_no' => '+960-1234567',
                'gender' => 'male',
                'blood_group' => 'O+',
                'date_of_birth' => '2000-05-15',
                'age' => 25,
                'service_duration' => 12,
                'parent_name' => 'Hassan Ali',
                'parent_relationship' => 'Father',
                'parent_email' => 'hassan.ali@example.com',
                'parent_contact_no' => '+960-7654321',
                'parent_address' => 'Male, Maldives',
                'batch_id' => $batch1->id,
                'company' => 'Alpha Company',
                'platoon' => '1st Platoon',
                'date_of_joining' => now()->addDays(30),
                'application_date' => now()->subDays(10),
                'applicant_number' => 'APP-2025-001',
                'pay_amount' => 5000.00,
                'status' => 'active',
                'is_active' => true,
            ],
            [
                'student_id' => 'NS2025-0002',
                'rank' => 'Recruit',
                'first_name' => 'Aisha',
                'last_name' => 'Mohamed',
                'name_in_dhivehi' => 'އައިޝާ މުހައްމަދު',
                'email' => 'aisha.mohamed@example.com',
                'national_id' => 'A234567',
                'permanent_address_name' => 'Home Address',
                'permanent_atoll' => 'Male',
                'permanent_island' => 'Male',
                'permanent_district' => 'Galolhu',
                'permanent_road_name' => 'Ameer Ahmed Magu',
                'present_address_name' => 'Training Camp',
                'present_atoll' => 'Male',
                'present_island' => 'Male',
                'present_district' => 'Henveiru',
                'present_road_name' => 'Training Road',
                'contact_no' => '+960-2345678',
                'gender' => 'female',
                'blood_group' => 'A+',
                'date_of_birth' => '2001-08-22',
                'age' => 24,
                'service_duration' => 12,
                'parent_name' => 'Mohamed Ibrahim',
                'parent_relationship' => 'Father',
                'parent_email' => 'mohamed.ibrahim@example.com',
                'parent_contact_no' => '+960-8765432',
                'parent_address' => 'Male, Maldives',
                'batch_id' => $batch1->id,
                'company' => 'Alpha Company',
                'platoon' => '2nd Platoon',
                'date_of_joining' => now()->addDays(30),
                'application_date' => now()->subDays(8),
                'applicant_number' => 'APP-2025-002',
                'pay_amount' => 5000.00,
                'status' => 'active',
                'is_active' => true,
            ],
            [
                'student_id' => 'NS2025-0003',
                'rank' => 'Recruit',
                'first_name' => 'Ibrahim',
                'last_name' => 'Ali',
                'name_in_dhivehi' => 'އިބްރާހިމް ޢަލީ',
                'email' => 'ibrahim.ali@example.com',
                'national_id' => 'A345678',
                'permanent_address_name' => 'Home Address',
                'permanent_atoll' => 'Addu',
                'permanent_island' => 'Hithadhoo',
                'permanent_district' => 'Hithadhoo',
                'permanent_road_name' => 'Main Road',
                'present_address_name' => 'Training Camp',
                'present_atoll' => 'Male',
                'present_island' => 'Male',
                'present_district' => 'Henveiru',
                'present_road_name' => 'Training Road',
                'contact_no' => '+960-3456789',
                'gender' => 'male',
                'blood_group' => 'B+',
                'date_of_birth' => '1999-12-10',
                'age' => 25,
                'service_duration' => 12,
                'parent_name' => 'Ali Ahmed',
                'parent_relationship' => 'Father',
                'parent_email' => 'ali.ahmed@example.com',
                'parent_contact_no' => '+960-9876543',
                'parent_address' => 'Addu, Maldives',
                'batch_id' => $batch2->id,
                'company' => 'Bravo Company',
                'platoon' => '1st Platoon',
                'date_of_joining' => now()->addDays(60),
                'application_date' => now()->subDays(5),
                'applicant_number' => 'APP-2025-003',
                'pay_amount' => 5000.00,
                'status' => 'active',
                'is_active' => true,
            ],
        ];

        foreach ($students as $studentData) {
            Student::create($studentData);
        }

        // Update batch student counts
        $batch1->update(['current_students' => 2]);
        $batch2->update(['current_students' => 1]);

        $this->command->info('SMS Sample data seeded successfully!');
        $this->command->info('Created:');
        $this->command->info('- 2 Training Batches');
        $this->command->info('- 5 Leave Types');
        $this->command->info('- 5 Performance Fields');
        $this->command->info('- 5 Subjects');
        $this->command->info('- 3 Sample Students');
    }
}