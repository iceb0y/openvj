<?php

namespace VJ\User;

use VJ\Models;

class ACL
{

    const CACHE_ACL_KEY       = 'openvj-cache-groupacl';
    const SYSTEM_ID_ACL       = 'acl';
    const SYSTEM_ID_ACL_RULES = 'acl_rules';

    private $acl = [];

    public static function initialize()
    {
        $di = \Phalcon\DI::getDefault();
        $di->setShared('acl', function () {
            $acl = new \VJ\User\ACL();

            return $acl;
        });
    }

    public static function check($priv, $uid = null)
    {
        if (!self::has($priv, $uid)) {
            throw new \VJ\Exception('ERR_NO_PRIV', $priv);
        }
    }

    public static function has($priv, $uid = null)
    {
        $acl = \Phalcon\DI::getDefault()->getShared('acl');

        return $acl->hasPriv($priv, $uid);
    }

    public function __construct()
    {
        $cache = \Phalcon\DI::getDefault()->getShared('cache');

        $this->acl = $cache->get(self::CACHE_ACL_KEY);

        if ($this->acl == null) {

            global $dm;
            $rec=$dm->getRepository('VJ\Models\System')->findOneBy(['id' => self::SYSTEM_ID_ACL])

            if ($rec == null) {
                throw new \Exception('ACL list not exist');
            }

            $this->acl = $rec->v;

            $cache->save(self::CACHE_ACL_KEY, $this->acl);
        }
    }

    /**
     * 根据用户自身权限和用户组计算最终权限
     *
     * @param $userPriv
     * @param $group
     *
     * @return mixed
     */
    public function merge($userACL, $group)
    {
        return $userACL + $this->acl[(int)$group];
    }

    /**
     * 返回一个组的权限
     *
     * @param $group
     *
     * @return mixed
     */
    public function getGroupACL($group)
    {
        return $this->acl[(int)$group];
    }

    /**
     * 判断当前或特定用户是否有某项权限
     *
     * @param      $priv
     * @param null $uid
     *
     * @return bool
     */
    public function hasPriv($priv, $uid = null)
    {

        global $dm;

        if (defined('OPENVJ_ACL_DISABLE')) {
            return true;
        }

        if ($uid !== null) {


            $u=$dm->getRepository('VJ\Models\User')->findOneBy(['uid' => (int)$uid])

            if ($u == false) {
                return false;
            }

            $_ACL = $this->merge(unserialize($u->acl), $u->group);

            if (!isset($_ACL[$priv])) {
                return false;
            }

            return (bool)$_ACL[$priv];
        } else {

            global $_ACL;

            if ($_ACL == null || !isset($_ACL[$priv])) {
                return false;
            }

            return (bool)$_ACL[$priv];
        }
    }

    /**
     * 保存ACL规则
     *
     * @param $ACL
     * @param $ACLRule
     */
    public static function save($ACL, $ACLRule)
    {

        // update records
        global $dm;

        $dm->createQueryBuilder('VJ\Models\System')
               ->update()
               ->upsert(true)
               ->field('id')->equals(self::SYSTEM_ID_ACL)
               ->field('v')->set($ACL)
               ->getQuery()
               ->execute();
        $dm->createQueryBuilder('VJ\Models\System')
               ->update()
               ->upsert(true)
               ->field('id')->equals(self::SYSTEM_ID_ACL_RULES)
               ->field('v')->set($ACLRule)
               ->getQuery()
               ->execute();
        // update cache
        $cache = \Phalcon\DI::getDefault()->getShared('cache');
        $cache->save(self::CACHE_ACL_KEY, $ACL);

        return true;
    }

    /**
     * 查询权限表
     *
     * @return array
     */
    public static function queryPrivilegeTable()
    {

        $fp = fopen(APP_DIR.'includes/privilege.php', 'r');

        $priv = [];
        $flag = false;

        while (!feof($fp)) {

            $line = fgets($fp);

            if (strpos($line, 'PRIVILEGE-TABLE-BEGIN') !== false) {
                $flag = true;
                continue;
            }

            if ($flag === false) {
                continue;
            }

            if (strpos($line, 'PRIVILEGE-TABLE-END') !== false) {
                break;
            }

            if (strlen(trim($line)) === 0) {
                continue;
            }

            preg_match('/const\s*(\w+)\s*=\s*(\d+);\s*\/\/([\s\S]*)$/', $line, $matches);

            if ($matches != null) {

                $priv[$matches[1]] = [
                    'v' => (int)$matches[2],
                    'd' => trim($matches[3])
                ];
            }
        }

        fclose($fp);

        return $priv;
    }

    /**
     * Export ACL to .js file
     *
     * @return string
     */
    public static function export()
    {
        global $dm;
        $rec=$dm->getRepository('VJ\Models\System')->findOneBy(['id' => self::SYSTEM_ID_ACL])
        $acl   = $rec->v;
        $rec=$dm->getRepository('VJ\Models\System')->findOneBy(['id' => self::SYSTEM_ID_ACL_RULES])
        $acl_r = $rec->v;

        $result = \VJ\Escaper::json([
            'acl'       => $acl,
            'acl_rules' => $acl_r
        ]);

        return $result;
    }

    /**
     * 查询权限规则表
     *
     * @return mixed
     */
    public static function queryRules()
    {
        $rec   = $mongo->System->findOne(['_id' => self::SYSTEM_ID_ACL_RULES]);
        global $dm;
        $rec=$dm->getRepository('VJ\Models\System')->findOneBy(['id' => self::SYSTEM_ID_ACL_RULES])
        return $rec->v;
    }

    /**
     * 将权限表转换为权限树
     *
     * @param $privFlat
     *
     * @return array
     */
    public static function convertToTree($privFlat)
    {
        $tree = [];

        foreach ($privFlat as $name => $value) {

            $namespace = explode('_', $name);

            $ref = & $tree;

            foreach ($namespace as $n) {

                if (!isset($ref[$n])) {
                    $ref[$n] = [];
                }

                $ref = & $ref[$n];
            }

            $ref['_v'] = $value['v'];
            $ref['_d'] = $value['d'];
        }

        return $tree;
    }
}