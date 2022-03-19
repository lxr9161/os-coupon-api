<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User;

class UserController extends Controller
{
    /**
     * 获取用户列表
     *
     * @param Request $request
     */
    public function getUserList(Request $request, User $model)
    {
        $query = $request->query();
        $where = '1=1';
        if (!empty(intval($query['user_id']))) {
            $where .= ' and id=' .$query['user_id'];
        }
        if (!empty($query['openid'])) {
            $where .= ' and openid="' . $query['openid'] . '"';
        }
        if (!empty($query['start_time'])) {
            $where .= ' and created_at >=' . strtotime($query['start_time']);
        }
        if (!empty($query['end_time'])) {
            $where .= ' and created_at <=' . strtotime($query['end_time']);
        }

        $data = $model->whereRaw($where)->orderBy('id', 'DESC')->paginate(20);

        return $this->responseSuccess($data);
    }


    /**
     * 设置用户为管理身份
     *
     * @param Request $request
     * @param User $model
     */
    public function setAdmin(Request $request, User $model)
    {
        $postData = $request->all();
        $user = $model->find($postData['user_id']);
        
        if (empty($user)) {
            return $this->responseFail('未找到用户');
        }

        $user->fill([
            'is_admin' => $postData['is_admin']
        ]);
        $res = $user->save();
        if ($res) {
            return $this->responseSuccess();
        }

        return $this->responseFail();
    }
}
