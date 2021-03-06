<?php
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 黎小龙 <shalinglom@gmail.com>
// +----------------------------------------------------------------------
// | CreateTime: 2018/6/15 
// +----------------------------------------------------------------------


namespace app\api\model;


class User extends BaseModel
{
    /**
     * 注册 |  更新
     * @param $data
     * @return false|int
     * @throws \think\exception\DbException
     */
    public function saveData($data)
    {
        $result = $this->save($data);
        return $result;
    }

    /**
     * 用户删除
     * @param $id
     * @return int
     */
    public function deleteUser($id)
    {
        $map['id'] = is_array($id) ? ['in', $id] : $id;
        //TODO 事务、头像删除、信息判断
        $result = $this->where($map)->delete();
        return $result;
    }

    /**
     * 查找用户
     * @param $map
     * @param $page
     * @param $row
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\exception\DbException
     */
    public function selectUser($map, $page, $row)
    {
        return $this->where($map)->page($page, $row)->select();
    }

    /**
     * 检查用户是否存在
     * @param $openid
     * @return bool
     */
    public function checkExistsUser($openid)
    {
        $user = $this->where('wx_openid',$openid)->find();

        return empty($user) ? false : $user;
    }

}