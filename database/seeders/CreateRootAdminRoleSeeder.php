<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Wnikk\LaravelAccessRules\AccessRules;

class CreateRootAdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $acr = new AccessRules();
        $acr->newOwner('Role','root','RootAdmin role');

        // For example #1
        $acr->addPermission('usualUser.viewAny');

        // For example #2
        $acr->addPermission('moderator.view');

        // For example #3
        $acr->addPermission('Admin.update', 'name');
        $acr->addPermission('Admin.update', 'email');
        $acr->addPermission('Admin.update', 'password');

        // For example #4
        $acr->addPermission('viewAny');
        $acr->addPermission('view');
        $acr->addPermission('create');
        $acr->addPermission('update');
        $acr->addPermission('delete');

        // For example #5
        $acr->addPermission('Watching.watchingModer.viewAny');
        $acr->addPermission('Watching.watchingModer.view');
        $acr->addPermission('Watching.watchingModer.create');
        $acr->addPermission('Watching.watchingModer.update');
        $acr->addPermission('Watching.watchingModer.delete');

        // For example #6
        //For all - $acr->addPermission('example6.update');
        $acr->addPermission('watchingAdmin.update.self');

        // For example #7
        $acr->addPermission('rowsAnyNews.test');

        // For final example
        $acr->addPermission('Watching.UserRules.index');
        $acr->addPermission('Watching.UserRules.rules');
        $acr->addPermission('Watching.UserRules.rules', 'index');
        $acr->addPermission('Watching.UserRules.rules', 'store');
        $acr->addPermission('Watching.UserRules.rules', 'update');
        $acr->addPermission('Watching.UserRules.rules', 'destroy');
        $acr->addPermission('Watching.UserRules.roles');
        $acr->addPermission('Watching.UserRules.roles', 'index');
        $acr->addPermission('Watching.UserRules.roles', 'store');
        $acr->addPermission('Watching.UserRules.roles', 'update');
        $acr->addPermission('Watching.UserRules.roles', 'destroy');
        $acr->addPermission('Watching.UserRules.inherit');
        $acr->addPermission('Watching.UserRules.inherit', 'index');
        $acr->addPermission('Watching.UserRules.inherit', 'store');
        $acr->addPermission('Watching.UserRules.inherit', 'destroy');
        $acr->addPermission('Watching.UserRules.permission');
        $acr->addPermission('Watching.UserRules.permission', 'index');
        $acr->addPermission('Watching.UserRules.permission', 'update');
    }
}
