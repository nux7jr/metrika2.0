<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Wnikk\LaravelAccessRules\AccessRules;

class CreateRulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // usualUser - route middleware
        AccessRules::newRule('usualUser.viewAny', 'View all users on usualUser');

        // moderator  - check in action
        AccessRules::newRule('moderator.view', 'View data of user on moderator');

        // Admin - check on action options
        AccessRules::newRule([
            'guard_name' => 'Admin.update',
            'title' => 'Changing different user data on Admin',
            'options' => 'required|in:name,email,password'
        ]);

        // watchingAll - global resource
        AccessRules::newRule('viewAny', 'Global rule "viewAny" for watchingAll');
        AccessRules::newRule('view', 'Global rule "view" for watchingAll');
        AccessRules::newRule('create', 'Global rule "create" for watchingAll');
        AccessRules::newRule('update', 'Global rule "update" for watchingAll');
        AccessRules::newRule('delete', 'Global rule "delete" for watchingAll');

        // watchingModer - resource for controller
        AccessRules::newRule('Watching.watchingModer.viewAny', 'Rule for one Controller his action "viewAny" watchingModer');
        AccessRules::newRule('Watching.watchingModer.view', 'Rule for one Controller his action "view" watchingModer');
        AccessRules::newRule('Watching.watchingModer.create', 'Rule for one Controller his action "create" watchingModer');
        AccessRules::newRule('Watching.watchingModer.update', 'Rule for one Controller his action "update" watchingModer');
        AccessRules::newRule('Watching.watchingModer.delete', 'Rule for one Controller his action "delete" watchingModer');

        // watchingAdmin - magic self
        AccessRules::newRule(
            'watchingAdmin.update',
            'Rule that allows edit all news',
            'An example of how to use a magic suffix ".self" on watchingAdmin'
        );
        AccessRules::newRule('watchingAdmin.update.self', 'Rule that allows edit only where user is author');

        // rowsAny - Policy
        AccessRules::newRule('rowsAnyNews.test', 'Rule event "test" rowsAny');

        // Final example, add control to the Access user interface
        $id = AccessRules::newRule('Watching.UserRules.main', 'View all rules, permits and inheritance');
        AccessRules::newRule('Watching.UserRules.rules', 'Working with Rules', null, $id, 'nullable|in:index,store,update,destroy');
        AccessRules::newRule('Watching.UserRules.roles', 'Working with Roles', null, $id, 'nullable|in:index,store,update,destroy');
        AccessRules::newRule('Watching.UserRules.inherit', 'Working with Inherit', null, $id, 'nullable|in:index,store,destroy');
        AccessRules::newRule('Watching.UserRules.permission', 'Working with Permission', null, $id, 'nullable|in:index,update');
    }
}
