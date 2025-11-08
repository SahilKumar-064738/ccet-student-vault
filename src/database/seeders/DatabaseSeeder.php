namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Branches
        $branches = [
            ['code' => 'CSE', 'name' => 'Computer Science & Engineering'],
            ['code' => 'IT', 'name' => 'Information Technology'],
            ['code' => 'ECE', 'name' => 'Electronics & Communication Engineering'],
            ['code' => 'EE', 'name' => 'Electrical Engineering'],
            ['code' => 'ME', 'name' => 'Mechanical Engineering'],
            ['code' => 'CE', 'name' => 'Civil Engineering'],
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }

        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@ccet.ac.in',
            'password' => 'Password123',
            'role' => 'admin',
            'branch_id' => 1,
            'year' => 1,
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        // Create CR User
        User::create([
            'name' => 'CR User',
            'email' => 'cr@ccet.ac.in',
            'password' => 'Password123',
            'role' => 'cr',
            'branch_id' => 1,
            'year' => 2,
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        // Create Teacher User
        User::create([
            'name' => 'Teacher User',
            'email' => 'teacher@ccet.ac.in',
            'password' => 'Password123',
            'role' => 'teacher',
            'branch_id' => 1,
            'year' => 1,
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        // Create Student User
        User::create([
            'name' => 'Student User',
            'email' => 'student@ccet.ac.in',
            'password' => 'Password123',
            'role' => 'student',
            'branch_id' => 1,
            'year' => 2,
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        // Create Subjects for CSE Branch
        $cseSubjects = [
            // Year 1
            ['branch_id' => 1, 'name' => 'Programming Fundamentals', 'code' => 'CS101', 'year' => 1, 'semester' => 1],
            ['branch_id' => 1, 'name' => 'Mathematics I', 'code' => 'MA101', 'year' => 1, 'semester' => 1],
            ['branch_id' => 1, 'name' => 'Physics', 'code' => 'PH101', 'year' => 1, 'semester' => 1],
            ['branch_id' => 1, 'name' => 'Engineering Drawing', 'code' => 'ED101', 'year' => 1, 'semester' => 1],
            ['branch_id' => 1, 'name' => 'Data Structures', 'code' => 'CS102', 'year' => 1, 'semester' => 2],
            ['branch_id' => 1, 'name' => 'Mathematics II', 'code' => 'MA102', 'year' => 1, 'semester' => 2],
            
            // Year 2
            ['branch_id' => 1, 'name' => 'Object Oriented Programming', 'code' => 'CS201', 'year' => 2, 'semester' => 3],
            ['branch_id' => 1, 'name' => 'Database Management Systems', 'code' => 'CS202', 'year' => 2, 'semester' => 3],
            ['branch_id' => 1, 'name' => 'Computer Networks', 'code' => 'CS203', 'year' => 2, 'semester' => 3],
            ['branch_id' => 1, 'name' => 'Digital Logic Design', 'code' => 'CS204', 'year' => 2, 'semester' => 3],
            ['branch_id' => 1, 'name' => 'Operating Systems', 'code' => 'CS205', 'year' => 2, 'semester' => 4],
            ['branch_id' => 1, 'name' => 'Software Engineering', 'code' => 'CS206', 'year' => 2, 'semester' => 4],
            
            // Year 3
            ['branch_id' => 1, 'name' => 'Algorithm Design & Analysis', 'code' => 'CS301', 'year' => 3, 'semester' => 5],
            ['branch_id' => 1, 'name' => 'Web Technologies', 'code' => 'CS302', 'year' => 3, 'semester' => 5],
            ['branch_id' => 1, 'name' => 'Artificial Intelligence', 'code' => 'CS303', 'year' => 3, 'semester' => 5],
            ['branch_id' => 1, 'name' => 'Computer Architecture', 'code' => 'CS304', 'year' => 3, 'semester' => 5],
            ['branch_id' => 1, 'name' => 'Machine Learning', 'code' => 'CS305', 'year' => 3, 'semester' => 6],
            ['branch_id' => 1, 'name' => 'Cloud Computing', 'code' => 'CS306', 'year' => 3, 'semester' => 6],
            
            // Year 4
            ['branch_id' => 1, 'name' => 'Cyber Security', 'code' => 'CS401', 'year' => 4, 'semester' => 7],
            ['branch_id' => 1, 'name' => 'Big Data Analytics', 'code' => 'CS402', 'year' => 4, 'semester' => 7],
            ['branch_id' => 1, 'name' => 'Internet of Things', 'code' => 'CS403', 'year' => 4, 'semester' => 7],
            ['branch_id' => 1, 'name' => 'Blockchain Technology', 'code' => 'CS404', 'year' => 4, 'semester' => 8],
        ];

        foreach ($cseSubjects as $subject) {
            Subject::create($subject);
        }

        // Create Subjects for IT Branch
        $itSubjects = [
            ['branch_id' => 2, 'name' => 'Programming Fundamentals', 'code' => 'IT101', 'year' => 1, 'semester' => 1],
            ['branch_id' => 2, 'name' => 'Data Structures', 'code' => 'IT102', 'year' => 1, 'semester' => 2],
            ['branch_id' => 2, 'name' => 'Database Management Systems', 'code' => 'IT201', 'year' => 2, 'semester' => 3],
            ['branch_id' => 2, 'name' => 'Web Development', 'code' => 'IT202', 'year' => 2, 'semester' => 4],
            ['branch_id' => 2, 'name' => 'Mobile App Development', 'code' => 'IT301', 'year' => 3, 'semester' => 5],
            ['branch_id' => 2, 'name' => 'Network Security', 'code' => 'IT302', 'year' => 3, 'semester' => 6],
        ];

        foreach ($itSubjects as $subject) {
            Subject::create($subject);
        }

        // Create Subjects for ECE Branch
        $eceSubjects = [
            ['branch_id' => 3, 'name' => 'Circuit Theory', 'code' => 'EC101', 'year' => 1, 'semester' => 1],
            ['branch_id' => 3, 'name' => 'Electronic Devices', 'code' => 'EC102', 'year' => 1, 'semester' => 2],
            ['branch_id' => 3, 'name' => 'Analog Electronics', 'code' => 'EC201', 'year' => 2, 'semester' => 3],
            ['branch_id' => 3, 'name' => 'Digital Electronics', 'code' => 'EC202', 'year' => 2, 'semester' => 4],
            ['branch_id' => 3, 'name' => 'Communication Systems', 'code' => 'EC301', 'year' => 3, 'semester' => 5],
            ['branch_id' => 3, 'name' => 'VLSI Design', 'code' => 'EC302', 'year' => 3, 'semester' => 6],
        ];

        foreach ($eceSubjects as $subject) {
            Subject::create($subject);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('');
        $this->command->info('Default Login Credentials:');
        $this->command->info('-------------------------');
        $this->command->info('Admin: admin@ccet.ac.in / Password123');
        $this->command->info('CR: cr@ccet.ac.in / Password123');
        $this->command->info('Teacher: teacher@ccet.ac.in / Password123');
        $this->command->info('Student: student@ccet.ac.in / Password123');
    }
}