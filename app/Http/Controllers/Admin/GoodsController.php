<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Goods;


class GoodsController extends Controller
{
    protected $model;

    public function __construct(Goods $model)
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
        $status = $request->query('status');
        $where = '';
        if (is_numeric($status)) {
            $where = 'status=' . $status;
        }

        return $this->model->getListWithPage(20, $where, 'id DESC');
    }


    /**
     * 新增
     */
    public function store(Request $request)
    {
        $postData = $request->all();
        if (empty($postData['name'])) {
            return $this->responseFail('请填写物品名称');
        }
       
        $postData['price'] = (int) $postData['price'];
        $postData['sort'] = (int) $postData['sort'];
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
            return $this->responseFail('未查找物品');
        }
        if (empty($postData['name'])) {
            return $this->responseFail('请填写物品名称');
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