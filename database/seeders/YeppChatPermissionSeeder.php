<?php

namespace EscolaLms\YeppChat\Database\Seeders;

use EscolaLms\YeppChat\Enums\YeppChatPermissionEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class YeppChatPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::findOrCreate('admin', 'api');
        $tutor = Role::findOrCreate('tutor', 'api');
        $student = Role::findOrCreate('student', 'api');

        foreach (YeppChatPermissionEnum::asArray() as $const => $value) {
            Permission::findOrCreate($value, 'api');
        }

        $admin->givePermissionTo(YeppChatPermissionEnum::asArray());
        $tutor->givePermissionTo(YeppChatPermissionEnum::asArray());
        $student->givePermissionTo(YeppChatPermissionEnum::asArray());
    }
}
