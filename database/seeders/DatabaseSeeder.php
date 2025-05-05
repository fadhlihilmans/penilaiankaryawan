<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;
use App\Models\Position;
use App\Models\Employee;
use App\Models\Education;
use App\Models\EvaluationCriteria;
use App\Models\EvaluationCriteriaDetail;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'description' => 'Semua Hak Akses',
            ],
            [
                'name' => 'penilai',
                'description' => 'Hak Akses Untuk Penilai',
            ],
            [
                'name' => 'karyawan',
                'description' => 'Hak Akses Untuk Karyawan',
            ],
        ];

        foreach ($roles as $data) {
            $save = new Role();
            $save->name = $data['name'];
            $save->description = $data['description'];
            $save->save();
        }
        
        $user = [
            [
                'name' => 'admin',
                'email' => 'admin@bkk.id',
                'password' => bcrypt('12345678'),
            ],
            [
                'name' => 'penilai',
                'email' => 'penilai@bkk.id',
                'password' => bcrypt('12345678'),
            ],
            [
                'name' => 'karyawan_1',
                'email' => 'karyawan_1@bkk.id',
                'password' => bcrypt('12345678'),
            ],
            [
                'name' => 'karyawan_2',
                'email' => 'karyawan_2@bkk.id',
                'password' => bcrypt('12345678'),
            ],
            [
                'name' => 'karyawan_3',
                'email' => 'karyawan_3@bkk.id',
                'password' => bcrypt('12345678'),
            ],
        ];

        foreach ($user as $data) {
            $save = new User();
            $save->name = $data['name'];
            $save->email = $data['email'];
            $save->password = $data['password'];
            $save->save();
        }

        $addUserRole = [
            [
                'user_id' => 1,
                'role_id' => 1,
            ],
            [
                'user_id' => 2,
                'role_id' => 2,
            ],
            
            [
                'user_id' => 3,
                'role_id' => 3,
            ],
            [
                'user_id' => 4,
                'role_id' => 3,
            ],
            [
                'user_id' => 5,
                'role_id' => 3,
            ],
            
        ];
        foreach ($addUserRole as $data) {
            $save = new UserRole();
            $save->user_id = $data['user_id'];
            $save->role_id = $data['role_id'];
            $save->save();
        }
        
        $position = [
            [
                'name' => 'Bendahara',
                'description' => 'Bendahara',
            ],
            [
                'name' => 'sekertaris',
                'description' => 'Sekertaris',
            ],
            [
                'name' => 'pegawai',
                'description' => 'Pegawai',
            ],
        ];
        foreach ($position as $data) {
            $save = new Position();
            $save->name = $data['name'];
            $save->description = $data['description'];
            $save->save();
        }

        $education = [
            [
                'name' => 'SMA / Sederajat',
            ],
            [
                'name' => 'Diploma',
            ],
            [
                'name' => 'Sarjana',
            ],
            [
                'name' => 'Magister',
            ],
            [
                'name' => 'Doctor',
            ],
        ];

        foreach ($education as $data) {
            $save = new Education();
            $save->name = $data['name'];
            $save->save();
        }

        $employee = [
            [
                'user_id' => 3,
                'position_id' => 1,
                'education_id' => 1,
                'nip' => mt_rand(111111111, 999999999),
                'birth_place' => 'Batang',
                'birth_date' => date('Y-m-d'),
                'gender' => 'female',
                'employment_status' => 'permanent'
            ],
            [
                'user_id' => 4,
                'position_id' => 2,
                'education_id' => 2,
                'nip' => mt_rand(111111111, 999999999),
                'birth_place' => 'Batang',
                'birth_date' => date('Y-m-d'),
                'gender' => 'male',
                'employment_status' => 'permanent'
            ],
            
            [
                'user_id' => 5,
                'position_id' => 3,
                'education_id' => 3,
                'nip' => mt_rand(111111111, 999999999),
                'birth_place' => 'Batang',
                'birth_date' => date('Y-m-d'),
                'gender' => 'male',
                'employment_status' => 'permanent'
            ],
        ];
        
        foreach ($employee as $data) {
            $save = new Employee();
            $save->user_id = $data['user_id'];
            $save->position_id = $data['position_id'];
            $save->education_id = $data['education_id'];
            $save->nip = $data['nip'];
            $save->birth_place = $data['birth_place'];
            $save->birth_date = $data['birth_date'];
            $save->gender = $data['gender'];
            $save->employment_status = $data['employment_status'];
            $save->save();
        }

        $criteria = [
            [
                'name' => 'Kedisplinan',
                'description' => 'kedisplinan',
                'weight' => 30,
            ],
            [
                'name' => 'Keterampilan Teknis',
                'description' => 'Keterampilan Teknis',
                'weight' => 30,
            ],
            [
                'name' => 'Kepribadian',
                'description' => 'Kepribadian',
                'weight' => 40,
            ],
        ];
        foreach ($criteria as $data) {
            $save = new EvaluationCriteria();
            $save->name = $data['name'];
            $save->description = $data['description'];
            $save->weight = $data['weight'];
            $save->save();
        }

        $criteriaDetails = [
            'Kedisplinan' => [
                ['name' => 'Kehadiran', 'description' => 'Tingkat kehadiran secara rutin', 'weight' => 30],
                ['name' => 'Kepatuhan terhadap aturan', 'description' => 'Mematuhi peraturan perusahaan', 'weight' => 70],
            ],
            'Keterampilan Teknis' => [
                ['name' => 'Kemampuan Operasional', 'description' => 'Mengoperasikan alat/sistem kerja', 'weight' => 50],
                ['name' => 'Pemecahan Masalah', 'description' => 'Mampu mengatasi masalah teknis', 'weight' => 50],
            ],
            'Kepribadian' => [
                ['name' => 'Etika Kerja', 'description' => 'Sikap dan perilaku profesional', 'weight' => 40],
                ['name' => 'Kerja Sama', 'description' => 'Bekerja sama dalam tim', 'weight' => 60],
            ],
        ];

        foreach ($criteriaDetails as $criteriaName => $details) {
            $criteria = EvaluationCriteria::where('name', $criteriaName)->first();
            if (!$criteria) continue;

            foreach ($details as $detail) {
                EvaluationCriteriaDetail::create([
                    'evaluation_criteria_id' => $criteria->id,
                    'name' => $detail['name'],
                    'description' => $detail['description'],
                    'weight' => $detail['weight'],
                ]);
            }
        }
    }
}