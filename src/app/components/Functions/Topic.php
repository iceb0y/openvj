<?php

namespace VJ\Functions;

use \VJ\I;
use \VJ\Utils;

class Topic
{

    const SYSTEM_ID_NODE_FLAT = 'topic_node_flat';
    const SYSTEM_ID_NODE_TREE = 'topic_node_tree';

    /**
     * 查询讨论节点 以列表返回
     *
     * @return mixed
     */
    public static function queryNodeAsFlat()
    {

        $mongo = \Phalcon\DI::getDefault()->getShared('mongo');
        $list  = $mongo->System->findOne(['_id' => self::SYSTEM_ID_NODE_FLAT]);

        return $list['v'];

    }

    /**
     * 查询讨论节点 以树型返回
     *
     * @return mixed
     */
    public static function queryNodeAsTree()
    {

        $mongo = \Phalcon\DI::getDefault()->getShared('mongo');
        $tree  = $mongo->System->findOne(['_id' => self::SYSTEM_ID_NODE_TREE]);

        return $tree['v'];

    }

    /**
     * 查询讨论节点是否存在，并返回其正确大小写形式
     *
     * @param $node
     *
     * @return bool|int|string
     */
    public static function queryNodeName($node)
    {

        $nodeList = self::queryNodeAsFlat();

        foreach ($nodeList as $nodeName => $type) {
            if (strcasecmp($nodeName, $node) === 0) {
                return $nodeName;
            }
        }

        return false;

    }

    /**
     * 创建话题
     *
     * @param       $title
     * @param       $content
     * @param       $node
     * @param array $options
     *
     * @return array|string
     */
    public static function create($title, $content, $node, $options = array())
    {
        /*

            Options:

                highlight:      是否高亮该话题

         */

        $di    = \Phalcon\DI::getDefault();
        $acl   = $di->getShared('acl');
        $mongo = $di->getShared('mongo');

        $title   = (string)$title;
        $content = (string)$content;
        $node    = (string)$node;

        global $_UID, $__CONFIG;

        if (!$acl->has(PRIV_TOPIC_CREATE)) {
            return I::error('NO_PRIV', 'PRIV_TOPIC_CREATE');
        }

        if (isset($options['highlight'])) {
            if (!$acl->has(PRIV_TOPIC_HIGHLIGHT)) {
                return I::error('NO_PRIV', 'PRIV_TOPIC_HIGHLIGHT');
            }
        }

        $argv = [
            'title' => &$title,
            'content' => &$content,
            'node' => &$node
        ];

        \VJ\Validator::filter($argv, [
            'title' => 'html',
            'content' => 'trim',
            'node' => 'lower'
        ]);

        $validateResult = \VJ\Validator::validate($argv, [
            'content' => [
                'contentlength' => [$__CONFIG->Topic->contentMin, $__CONFIG->Topic->contentMax]
            ]
        ]);

        if ($validateResult !== true) {
            return $validateResult;
        }

        $node = self::queryNodeName($node);

        if ($node === false) {
            return I::error('NOT_FOUND', 'node');
        }

        $nodel = strtolower($node);
        $mtime = time();

        $doc = [
            '_id'    => new \MongoID(),
            'uid'    => $_UID,
            'time'   => $mtime,
            'mtime'  => $mtime,
            'stime'  => $mtime,
            'title'  => $title,
            'md'     => new \MongoBinData(gzcompress($content)),
            'text'   => \VJ\Formatter\Markdown::parse($content),
            'replyc' => 0,
            'viewc'  => 0,
            'node'   => $node,
            'nodel'  => $nodel
        ];

        $doc['vote_id'] = $doc['dcz_id'] = 'topic_'.(string)$doc['_id'];

        if (isset($options['highlight'])) {
            $doc['hl'] = true;
        }

        $mongo->Topic->insert($doc);

        return (string)$doc['_id'];

    }


}