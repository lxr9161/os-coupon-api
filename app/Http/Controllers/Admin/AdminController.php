<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Admin;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    protected $model;

    public function __construct(Admin $adminModel)
    {
        $this->model = $adminModel;
    }
    /**
     * 管理员列表
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->model->paginate(10);

        return $data;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'login_name' => 'required|max:30|unique:admin,login_name',
            'password' => 'required|max:40',
        ], [
            'login_name.required' => '请填写登录名',
            'login_name.max' => '登录名不能超过30个字符',
            'login_name.unique' => '登录名已存在',
            'password.required' => '请填写登录密码',
            'password.max' => '登录密码不能超过40个字符',
        ]);
        if ($validator->errors()->first()) {
            return ['status' => 'fail', 'info' => $validator->errors()->first()];
        }
        $saveData = [
            'login_name' => $data['login_name'],
            'password' => Hash::make($data['password'])
        ];
        if (!empty($data['role'])) {
            $saveData['role'] = implode(',', $data['role']);
        }
        $this->model->fill($saveData);
        $r = $this->model->save();
        if ($r) {
            return ['status' => 'success' ,'info' => '添加成功'];
        } else {
            return ['status' => 'fail' ,'info' => '添加失败'];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $data = $this->model->find($id);

        if (empty($data)) {
            return ['status' => 'fail', 'info' => '用户不存在'];
        }

        return ['status' => 'success', 'info' => $data];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'login_name' => 'required|max:30|unique:admin,login_name,' . $id,
            'password' => 'max:40',
        ], [
            'login_name.required' => '请填写登录名',
            'login_name.max' => '登录名不能超过30个字符',
            'login_name.unique' => '登录名已存在',
            'password.max' => '登录密码不能超过40个字符',
        ]);


        if ($validator->errors()->first()) {
            return ['status' => 'fail', 'info' => $validator->errors()->first()];
        }
        $manager = $this->model->find($id);
        $saveData = [
            'login_name' => $data['login_name']
        ];
        if (!empty($data['password'])) {
            $saveData['password'] = Hash::make($data['password']);
        }
        if (!empty($data['role'])) {
            $saveData['role'] = implode(',', $data['role']);
        }
        $manager->fill($saveData);
        $r = $manager->save();
        if ($r) {
            return ['status' => 'success' ,'info' => '保存成功'];
        } else {
            return ['status' => 'fail' ,'info' => '保存失败'];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        if ((int)$id === 1) {
            return ['status' => 'fail', 'info' => '初始账号，不可删除'];
        }
        $delete = $this->model->find((int) $id);
        $r = $delete->delete();
        if ($r) {
            return ['status' => 'success', 'info' => '删除成功'];
        }  
        return ['status' => 'fail', 'info' => '删除失败'];
    }
}