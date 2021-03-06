<?php
// +----------------------------------------------------------------------
// | 深圳市保联科技有限公司
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.luckyins.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 黎小龙 <shalinglom@gmail.com>
// +----------------------------------------------------------------------
namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\model\User as userModel;
use app\lib\enum\Response;
use think\Request;
use app\api\service\Wechat;

class User extends BaseController {

    function __construct(Request $request = null) {

        parent::__construct($request);
        //当前model
        $this->currentModel    = new userModel();
        //当前validate
        $this->currentValidate = validate('user');
    }

    /**
     * 用户注册
     * @return \think\response\Json
     * @throws \app\lib\exception\BaseException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function create()
    {
        $this->currentValidate->goCheck('create');
        $userInfo = $this->currentModel->saveData($this->data);
        if (!$userInfo) {
            $this->response->error(Response::USER_CREATE_ERROR);
        }
        $this->result['userInfo'] = $this->currentModel->toArray();
        return json($this->result, 201);
    }

    /**
     * 用户数据更新
     * @throws \app\lib\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function update()
    {

        $this->currentValidate->goCheck('update');
        if ($this->currentModel->saveData($this->data)) {
            return json($this->result, 202);
        }
        $this->response->error(Response::USER_UPDATE_ERROR);
    }

    /**
     * 查询用户数据
     * @throws \app\lib\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function select()
    {

        //$this->currentValidate->goCheck('select');
        $map = [];
        if ($this->data) {
            //组装查询条件
            $map = set_map($this->data, 'id,phone,type,group_id,group_type,wx_account');
        }
        $userList = $this->currentModel->selectUser($map, $this->page, $this->row);
        if ($userList) {
            return json($userList, 202);
        }
        $this->response->error(Response::USERS_EMPTY);
    }

    /**
     * 用户删除
     * @return \think\response\Json
     * @throws \app\lib\exception\BaseException
     */
    public function delete() {

        $this->currentValidate->goCheck('delete');
        if ($this->currentModel->deleteUser($this->data)) {
            return json($this->result, 200);
        }
        $this->response->error(Response::USER_DELETE_ERROR);
    }

    public function getOpenid(){

        $wechatModel = new Wechat();
        $openid = $wechatModel->getOpenid(['jsCode'=>$_GET['jsCode']]);

        return $this->jsonReturn(1,'成功',['openid'=>$openid]);
    }
}