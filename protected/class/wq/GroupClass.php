<?php
include_once(dirname(__FILE__) . '/../mainClass.php');

/**
 * 分组类
 * 姚小磊
 * 2016/7/15
 */
class GroupClass extends mainClass
{
    public $page = null;

    /** 查找商户下的分组
     * @param $merchant_id 商户id
     * @param string $search 搜索条件 条件如下：
     * type:分组类型
     * status:分组使用状态
     * name:分组名称
     * @return string 返回数据
     */
    public function getGroupLists($merchant_id, $search = '')
    {
        $cmd = Yii::app()->db->createCommand();
        $cmd->select('a.id, a.merchant_id, a.`name`, a.num, a.type, a.last_time, IFNULL(b.use_num, 0) AS use_num');
        $cmd->from('wq_user_group a');
        $cmd->leftJoin('(
			SELECT
                a.group_id, COUNT(a.group_id) AS use_num
            FROM wq_marketing_activity a
            WHERE a.merchant_id = ' . $merchant_id . '
            AND a.flag = ' . FLAG_NO . '
            AND a.`status` = ' . MARKETING_ACTIVITY_STATUS_START . '
            GROUP BY a.group_id
			) b', 'a.id = b.group_id');

        $cmd->andWhere('a.merchant_id = :merchant_id');
        $cmd->params[':merchant_id'] = $merchant_id;

        $cmd->andWhere('a.flag = :flag');
        $cmd->params[':flag'] = FLAG_NO;
        $cmd->order('a.create_time desc');

        if (!empty($search)) {
            if (!empty($search['type'])) {
                $cmd->andWhere('a.type = :type');
                $cmd->params[':type'] = $search['type'];
            }

            if (!empty($search['status'])) {
                if ($search['status'] == 1) { //已使用
                    $cmd->andWhere('b.use_num > 0');
                } else if ($search['status'] == 2) { //未使用
                    $cmd->andWhere('b.use_num IS NULL');
                }
            }

            if (!empty($search['name'])) {
                $cmd->andWhere("a.name like '%" . $search['name'] . "%'");
            }
        }

        $cmd1 = clone $cmd;
        $result1 = $cmd1->queryAll();

        $pages = new CPagination(count($result1));
        //$pages->pageSize = Yii::app()->params['perPage'];
        $pages->pageSize = 7;
        $this->page = $pages;

        $cmd->offset = (isset($_GET['page']) ? ($_GET['page'] - 1) : 0) * $pages->pageSize;
        $cmd->limit = $pages->pageSize;

        $result = $cmd->queryAll();

        return json_encode($result);
    }

    /** 根据分组id获取分组信息
     * @param $group_id 分组id
     * @return mixed
     */
    public function getGroupInfoById($group_id)
    {
        $res_group = UserGroup::model()->find('id = :id and flag = :flag', array(
            ':id' => $group_id,
            ':flag' => FLAG_NO
        ));

        return $res_group;
    }

    /** 获取分组下所有用户的id
     * @param $group_id 分组id
     * @return array
     */
    public function getGroupAllUserId($group_id)
    {
        $group_all_user_id = Group::model()->findAll('group_id = :group_id and flag = :flag', array(
            ':group_id' => $group_id,
            ':flag' => FLAG_NO
        ));

        $user_ids = array();

        foreach ($group_all_user_id as $v) {
            $user_ids[] = $v['user_id'];
        }

        return $user_ids;
    }
}