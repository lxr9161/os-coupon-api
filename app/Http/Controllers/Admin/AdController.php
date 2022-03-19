<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Ad;

class AdController extends Controller
{

    protected $model;

    public function __construct(Ad $model)
    {
        $this->model = $model;
    }
    /**
     * 列表
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->query();
        $where = [];
        if (!empty($params['code'])) {
            $where['position'] = $params['code'];
        }

        $list = $this->model->getAllAd($where);

        return $this->responseSuccess($list);
    }


    /**
     * 新增
     */
    public function store(Request $request)
    {
        $postData = $request->all();
        if (empty($postData['img_url'])) {
            return $this->responseFail('请上传图片');
        }
        $postData['sort'] = (int) $postData['sort'];
        $postData['coupon_id'] = (int) $postData['coupon_id'];
        $this->model->fill($postData);
        $res = $this->model->save();

        if ($res) {
            return $this->responseSuccess();
        }

        return $this->responseFail();
    }

    /**
     * 获取信息
     *
     * @param  int  $id
     */
    public function show($id)
    {
        $data = $this->model->find($id);

        return $this->responseSuccess($data);
    }

    /**
     * 更新
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $postData = $request->all();
        $this->model = $this->model->find($id);
        if (empty($this->model)) {
            return $this->responseFail('未查找到广告');
        }
        if (empty($postData['img_url'])) {
            return $this->responseFail('请上传图片');
        }
        $postData['sort'] = (int) $postData['sort'];
        $this->model->fill($postData);
        $res = $this->model->save();

        if ($res) {
            return $this->responseSuccess();
        }

        return $this->responseFail();
    }

    /**
     * 删除
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $res = $this->model->destroy($id);

        if ($res) {
            return $this->responseSuccess();
        }

        return $this->responseFail();
    }
}
