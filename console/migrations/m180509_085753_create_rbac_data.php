<?php

use yii\db\Migration;
use frontend\modules\user\models\User;
/**
 * Class m180509_085753_create_rbac_data
 */
class m180509_085753_create_rbac_data extends Migration {
    /**
     * @inheritdoc
     */
    public function safeUp() {

        $auth = Yii::$app->authManager;
        // complaints, post
        $viewComplaintPermission = $auth->createPermission('viewComplaints');
        $auth->add($viewComplaintPermission);

        $deletePostPermission = $auth->createPermission('deletePost');
        $auth->add($deletePostPermission);

        $viewPostPermission = $auth->createPermission('viewPost');
        $auth->add($viewPostPermission);

        $approvePostPermission = $auth->createPermission('approvePost');
        $auth->add($approvePostPermission);

        //user
        $viewUserListPermission = $auth->createPermission('viewUserList');
        $auth->add($viewUserListPermission);

        $viewUserPermission = $auth->createPermission('viewUser');
        $auth->add($viewUserPermission);

        $deleteUserPermission = $auth->createPermission('deleteUser');
        $auth->add($deleteUserPermission);

        $updateUserPermission = $auth->createPermission('updateUser');
        $auth->add($updateUserPermission);

        // roles

        $moderatorRole = $auth->createRole('moderator');
        $auth->add($moderatorRole);

        $adminRole = $auth->createRole('admin');
        $auth->add($adminRole);

        //add permission to roles

        $auth->addChild($moderatorRole, $viewComplaintPermission);
        $auth->addChild($moderatorRole, $deletePostPermission);
        $auth->addChild($moderatorRole, $approvePostPermission);
        $auth->addChild($moderatorRole, $viewPostPermission);
        $auth->addChild($moderatorRole, $viewUserListPermission);
        $auth->addChild($moderatorRole, $viewUserPermission);

        $auth->addChild($adminRole, $moderatorRole);
        $auth->addChild($adminRole, $deleteUserPermission);
        $auth->addChild($adminRole, $updateUserPermission);

        $userAdmin = User::getUserById(17);
        $userModerator = User::getUserById(19);

        $auth->assign($adminRole, $userAdmin->id);
        $auth->assign($moderatorRole, $userModerator->id);
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        echo "m180509_085753_create_rbac_data cannot be reverted.\n";

        return false;
    }

}
