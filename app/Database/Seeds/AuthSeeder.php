<?php namespace App\Database\Seeds;

use Myth\Auth\Authorization\GroupModel;
use Myth\Auth\Authorization\PermissionModel;
use Myth\Auth\Entities\User;
use App\Models\UserModel;

class AuthSeeder extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        // Initialize the classes
        $groups = new GroupModel();
        $permissions = new PermissionModel();
        $users = new UserModel();
        $authorization = service('authorization');

        /*** GROUPS ***/
        // Test for and create the necessary groups

        $rows = [
            ['name' => 'Level1', 'description' => 'Admins of the system. They will have full access to the application'],
            ['name' => 'Level2', 'description' => 'DOE and DCPCR Chairman - They will have the highest level of access to reports'],
            ['name' => 'Level3', 'description' => 'Deputy Director of Education of District and District Magistrate - They will have the access to their respective District level reports'],
            ['name' => 'Level4', 'description' => 'Deputy Director of Education of Zone - They will have the access to their respective Zone level reports'],
            ['name' => 'Level5', 'description' => 'Head of School - They will have the access to their respective School level reports'],
            ['name' => 'Level6', 'description' => 'DCPU - They will have the access to their respective District level reports for the cases mapped to them'],
            ['name' => 'Level7', 'description' => 'CWC - They will have the access to report for their jurisdiction'],
            ['name' => 'Level8', 'description' => 'Operators - They will have the access to only operators functionality'],
        ];
        foreach ($rows as $row) {
            $group = $groups->where('name', $row['name'])->first();

            if (empty($group)) {
                $groups->insert($row);
            }
        }

        /*** PERMISSIONS ***/
        // Test for and create the necessary permissions

        $rows = [
            ['name' => 'manageUsers', 'description' => 'Create operators and other users and assign them groups'],
            ['name' => 'viewAllReports', 'description' => 'View all reports'],
            ['name' => 'viewReportsDistricts', 'description' => 'View reports of a district'],
            ['name' => 'viewReportsZone', 'description' => 'View reports of a zone'],
            ['name' => 'viewReportsSchool', 'description' => 'View reports of a school'],
            ['name' => 'manageCases', 'description' => 'Access Operator functionality'],
        ];
        foreach ($rows as $row) {
            $permission = $permissions->where('name', $row['name'])->first();

            if (empty($permission)) {
                $permissions->insert($row);
            }
        }

        /*** GROUPS_PERMISSIONS ***/
        // Authorize groups for access to various sections

        // Admins
        $names = ['Level1'];
        foreach ($names as $name) {
            $authorization->removePermissionFromGroup('manageUsers', $name);
            $authorization->addPermissionToGroup('manageUsers', $name);
            $authorization->removePermissionFromGroup('viewAllReports', $name);
            $authorization->addPermissionToGroup('viewAllReports', $name);
            $authorization->removePermissionFromGroup('manageCases', $name);
            $authorization->addPermissionToGroup('manageCases', $name);
        }

        // DOE and DCPCR Chairman
        $names = ['Level2'];
        foreach ($names as $name) {
            $authorization->removePermissionFromGroup('viewAllReports', $name);
            $authorization->addPermissionToGroup('viewAllReports', $name);
        }

        // Deputy Director of Education of District and District Magistrate
        $names = ['Level3'];
        foreach ($names as $name) {
            $authorization->removePermissionFromGroup('viewReportsDistricts', $name);
            $authorization->addPermissionToGroup('viewReportsDistricts', $name);
        }

        // Deputy Director of Education of Zone
        $names = ['Level4'];
        foreach ($names as $name) {
            $authorization->removePermissionFromGroup('viewReportsZone', $name);
            $authorization->addPermissionToGroup('viewReportsZone', $name);
        }

        // Head of School
        $names = ['Level5'];
        foreach ($names as $name) {
            $authorization->removePermissionFromGroup('viewReportsSchool', $name);
            $authorization->addPermissionToGroup('viewReportsSchool', $name);
        }

        // Operators
        $names = ['Level8'];
        foreach ($names as $name) {
            $authorization->removePermissionFromGroup('manageCases', $name);
            $authorization->addPermissionToGroup('manageCases', $name);
        }

        /*** USERS ***/
        $rows = [
            [
                'username' => 'admin',
                'password' => 'password123',
                'active' => 1,
            ],
            [
                'username' => 'dcpcr',
                'password' => 'password901',
                'active' => 1,
            ],
            [
                'username' => 'district1',
                'password' => 'password456',
                'active' => 1,
            ],
            [
                'username' => 'zone1',
                'password' => 'password789',
                'active' => 1,
            ],
            [
                'username' => 'school1',
                'password' => 'password0AB',
                'active' => 1,
            ],
            [
                'username' => 'operator1',
                'password' => 'password0AB',
                'active' => 1,
            ],

        ];

        foreach ($rows as $row) {
            $user = $users->where('username', $row['username'])->first();

            if (empty($user)) {
                // Use the User entity to handle correct password hashing
                $users->insert(new User($row));
                echo "user inserted - ".$row['username'];
            }
        }

        /*** GROUPS_USERS ***/
        $authorization->removeUserFromGroup(1, 'level1');
        $authorization->addUserToGroup(1, 'level1');

        $authorization->removeUserFromGroup(2, 'level1');
        $authorization->addUserToGroup(2, 'level1');

        $authorization->removeUserFromGroup(3, 'level3');
        $authorization->addUserToGroup(3, 'level3');

        $authorization->removeUserFromGroup(4, 'level4');
        $authorization->addUserToGroup(4, 'level4');

        $authorization->removeUserFromGroup(5, 'level5');
        $authorization->addUserToGroup(5, 'level5');

        $authorization->removeUserFromGroup(6, 'level8');
        $authorization->addUserToGroup(6, 'level8');
    }
}