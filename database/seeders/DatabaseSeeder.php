<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Election;
use App\Models\Position;
use App\Models\Candidate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin account
        User::create([
            'name'       => 'System Administrator',
            'email'      => 'admin@university.edu',
            'password'   => Hash::make('password'),
            'role'       => 'admin',
            'student_id' => null,
        ]);

        // Sample students
        $students = [
            ['name' => 'Alice Johnson',  'email' => 'alice@university.edu',  'student_id' => 'STU-001'],
            ['name' => 'Bob Martinez',   'email' => 'bob@university.edu',    'student_id' => 'STU-002'],
            ['name' => 'Carol Williams', 'email' => 'carol@university.edu',  'student_id' => 'STU-003'],
        ];

        foreach ($students as $student) {
            User::create([
                'name' => $student['name'],
                'email' => $student['email'],
                'student_id' => $student['student_id'],
                'password' => Hash::make('password'),
                'role' => 'student',
                'email_verified_at' => now(), // Add this
            ]);
        }

        // Sample election
        $election = Election::create([
            'title'        => 'Student Council Elections 2025',
            'description' => 'Annual student council elections for the academic year 2025-2026.',
            'start_date'  => now()->subDay(),
            'end_date'    => now()->addDays(7),
            'status'      => 'active',
            'is_active'   => true, // Add this
        ]);

        // Positions & Candidates
        $positionsData = [
            'President' => [
                ['name' => 'John Smith', 'description' => 'Experienced leader with 2 years in student council'],
                ['name' => 'Maria Garcia', 'description' => 'Passionate about student rights and diversity'],
                ['name' => 'David Lee', 'description' => 'Former class representative, committed to change']
            ],
            'Secretary' => [
                ['name' => 'Emma Wilson', 'description' => 'Detail-oriented and organized'],
                ['name' => 'James Brown', 'description' => 'Experienced in documentation and communication']
            ],
            'Treasurer' => [
                ['name' => 'Olivia Davis', 'description' => 'Economics major with budget experience'],
                ['name' => 'Noah Miller', 'description' => 'Financial officer for 2 clubs'],
                ['name' => 'Sophia Taylor', 'description' => 'Experienced in fundraising and budgeting']
            ],
        ];

        foreach ($positionsData as $positionName => $candidates) {
            $position = Position::create([
                'election_id' => $election->id,
                'name' => $positionName,
                'description' => "Position for $positionName",
                'max_votes' => 1,
                'display_order' => array_search($positionName, array_keys($positionsData)) + 1,
            ]);
            
            foreach ($candidates as $candidate) {
                $position->candidates()->create([
                    'name' => $candidate['name'],
                    'description' => $candidate['description'],
                    'manifesto' => "My manifesto: " . $candidate['description'], // Using description as manifesto
                    'is_approved' => true, // Important: set to true so they appear in voting
                    'photo' => null, // Optional
                ]);
            }
        }

        // Create a second upcoming election
        $upcomingElection = Election::create([
            'title'        => 'Faculty Representative Elections 2025',
            'description' => 'Elections for faculty representatives.',
            'start_date'  => now()->addDays(10),
            'end_date'    => now()->addDays(17),
            'status'      => 'upcoming',
            'is_active'   => false,
        ]);

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin login: admin@university.edu / password');
    }
}