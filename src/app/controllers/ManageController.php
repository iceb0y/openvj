<?php

use \VJ\I;

class ManageController extends \VJ\Controller\Basic
{

    public function initialize()
    {

        $acl = \Phalcon\DI::getDefault()->getShared('acl');
        if (!$acl->has(PRIV_ADMIN_ACCESS)) {
            return $this->raiseError('NO_PRIV', 'PRIV_ADMIN_ACCESS');
        }

        $this->view->CURRENT_ACTION = $this->dispatcher->getActionName();

        $this->view->MANAGE_MENU = [

            ['type' => 'link', 'href' => '/manage/statistics', 'text' => 'Statistics', 'action' => 'statistics'],
            ['type' => 'link', 'href' => '/', 'text' => 'Vijos homepage', 'action' => 'home'],
            ['type' => 'link', 'href' => '/user/logout', 'text' => 'Logout', 'action' => 'logout'],
            ['type' => 'headline', 'text' => 'System'],
            ['type' => 'link', 'href' => '/', 'text' => 'Error center', 'action' => 'error'],
            ['type' => 'link', 'href' => '/', 'text' => 'Cache', 'action' => 'cache'],
            ['type' => 'headline', 'text' => 'Settings'],
            ['type' => 'link', 'href' => '/manage/acl', 'text' => 'ACL', 'action' => 'acl'],
            ['type' => 'link', 'href' => '/', 'text' => 'RP credit', 'action' => 'rp'],
            ['type' => 'headline', 'text' => 'Problem set'],
            ['type' => 'link', 'href' => '/', 'text' => 'Manage Problems', 'action' => 'problem'],
            ['type' => 'link', 'href' => '/', 'text' => 'Manage Data', 'action' => 'problemdata'],
            ['type' => 'headline', 'text' => 'Other'],
            ['type' => 'link', 'href' => '/', 'text' => 'Manage Teams', 'action' => 'team'],
            ['type' => 'link', 'href' => '/', 'text' => 'Manage Apps', 'action' => 'app'],
            ['type' => 'link', 'href' => '/', 'text' => 'Manage Contests', 'action' => 'contest'],
        ];

    }

    public function statisticsAction()
    {

        $this->view->setVars([
            'PAGE_CLASS' => 'manage_statistics page_manage',
            'TITLE'      => gettext('Statistics')
        ]);

    }

    public function aclAction()
    {

        if ($this->request->isPost() === true) {

            $result = \VJ\Security\CSRF::checkToken();

            if (I::isError($result)) {
                return $this->raiseError($result);
            }

            $result = \VJ\Validator::required($_POST, ['acl', 'acl_rule']);

            if (\VJ\I::isError($result)) {
                return $this->raiseError($result);
            }

            $result = \VJ\User\ACL::save(
                json_decode($_POST['acl'], true),
                json_decode($_POST['acl_rule'], true)
            );

            $this->forwardAjax($result);

        } else {

            if (isset($_GET['export'])) {

                $result = \VJ\Security\CSRF::checkToken();

                if (I::isError($result)) {
                    return $this->raiseError($result);
                }

                $this->view->disable();

                header('Content-Type: application/javascript');
                header('Content-Disposition: attachment; filename=acl.js');
                header('Pragma: no-cache');

                echo \VJ\User\ACL::export();

                return false;

            } else {

                $privTable = \VJ\User\ACL::queryPrivilegeTable();
                $privTree  = \VJ\User\ACL::convertToTree($privTable);
                $aclRules  = \VJ\User\ACL::queryRules();

                global $__GROUPS;

                $this->view->setVars([
                    'PAGE_CLASS'    => 'manage_acl page_manage',
                    'TITLE'         => gettext('ACL'),

                    'ACL_PRIVTABLE' => $privTable,
                    'ACL_PRIVTREE'  => $privTree,
                    'ACL_RULES'     => $aclRules,
                    'ACL_GROUPS'    => $__GROUPS
                ]);

            }
        }
    }

    public function indexAction()
    {

        global $__CONFIG;

        header('Location: '.$__CONFIG->Misc->basePrefix.'/manage/statistics');
        exit();
    }

}