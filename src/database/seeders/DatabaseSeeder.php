public function run(): void
{
    // Create branches
    $branches = [
        ['code' => 'CSE', 'name' => 'Computer Science & Engineering'],
        ['code' => 'IT', 'name' => 'Information Technology'],
        ['code' => 'ECE', 'name' => 'Electronics & Communication Engineering'],
        ['code' => 'ME', 'name' => 'Mechanical Engineering'],
        ['code' => 'CE', 'name' => 'Civil Engineering'],
    ];

    foreach ($branches as $branch) {
        \App\Models\Branch::create($branch);
    }

    // Create admin user
    \App\Models\User::create([
        'name' => 'Admin User',
        'email' => 'admin@ccet.ac.in',
        'password' => 'Password123',
        'role' => 'admin',
        'branch_id' => 1,
        'year' => 1,
        'email_verified_at' => now(),
        'is_active' => true,
    ]);
}