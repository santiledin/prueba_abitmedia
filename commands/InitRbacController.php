<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\User;

class InitRbacController extends Controller
{
    public function actionIndex()
    {
        $auth = Yii::$app->authManager;

        // Reset all
        $auth->removeAll();

        // Create roles
        echo "Creating roles...\n";
        $viewer = $auth->createRole('viewer');
        $viewer->description = 'Read-only access';
        $auth->add($viewer);

        $editor = $auth->createRole('editor');
        $editor->description = 'Can edit data';
        $auth->add($editor);

        $admin = $auth->createRole('admin');
        $admin->description = 'Administrator';
        $auth->add($admin);

        // Inheritance
        $auth->addChild($editor, $viewer);
        $auth->addChild($admin, $editor);

        // Create Admin User
        echo "Creating admin user...\n";
        $user = User::findByUsername('admin');
        if (!$user) {
            $user = new User();
            $user->username = 'admin';
            $user->email = 'admin@example.com';
            $user->setPassword('admin123');
            $user->generateAuthKey();
            $user->status = User::STATUS_ACTIVE;

            if ($user->save()) {
                echo "Admin user created.\n";
            } else {
                echo "Error creating admin user: " . json_encode($user->errors) . "\n";
                return;
            }
        } else {
            echo "Admin user already exists.\n";
        }

        // Assign Role
        echo "Assigning admin role...\n";
        if (!$auth->getAssignment('admin', $user->id)) {
            $auth->assign($admin, $user->id);
            echo "Role assigned.\n";
        } else {
            echo "Role already assigned.\n";
        }

        echo "Done.\n";
    }
}
