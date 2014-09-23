<?php

namespace VJ\Functions;

use VJ\Models;

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
        global $dm;
        $list = $dm->getRepository('VJ\Models\System')->findOneBy(['id' => self::SYSTEM_ID_NODE_FLAT]);

        return $list->v;
    }

    /**
     * 查询讨论节点 以树型返回
     *
     * @return mixed
     */
    public static function queryNodeAsTree()
    {
        global $dm;
        $list = $dm->getRepository('VJ\Models\System')->findOneBy(['id' => self::SYSTEM_ID_NODE_TREE]);

        return $tree->v;
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
    public static function create($title, $content, $node, $options = [])
    {
        /*

            Options:

                highlight:      是否高亮该话题

         */

        global $dm;
        global $_UID, $__CONFIG;

        \VJ\User\ACL::check('PRIV_TOPIC_CREATE');

        if (isset($options['highlight'])) {
            \VJ\User\ACL::check('PRIV_TOPIC_HIGHLIGHT');
        }

        $argv = [
            'title'   => &$title,
            'content' => &$content,
            'node'    => &$node
        ];

        \VJ\Validator::filter($argv, [
            'title'   => 'html',
            'content' => 'trim',
            'node'    => 'lower'
        ]);

        \VJ\Validator::validate($argv, [
            'content' => [
                'contentlength' => [$__CONFIG->Topic->contentMin, $__CONFIG->Topic->contentMax]
            ]
        ]);

        $node = self::queryNodeName($node);

        if ($node === false) {
            throw new \VJ\Exception('ERR_NOT_FOUND', 'node');
        }

        $nodel = strtolower($node);
        $mtime = time();

        $doc          = new Models\Topic();
        $doc->uid     = $_UID;
        $doc->time    = $mtime;
        $doc->mtime   = $mtime;
        $doc->stime   = $mtime;
        $doc->title   = $title;
        $doc->md      = new \MongoBinData(gzcompress($content));
        $doc->text    = \VJ\Formatter\Markdown::parse($content);
        $doc->replyc  = 0;
        $doc->viewc   = 0;
        $doc->node    = $node;
        $doc->nodel   = $nodel;
        $doc->vote_id = 'topic_'.(string)$doc->id;
        $doc->dcz_id  = 'topic_'.(string)$doc->id;

        if (isset($options['highlight'])) {
            $doc->hl = true;
        }

        $dm->persist($doc);
        $dm->flush();

        return (string)$doc->id;
    }
}