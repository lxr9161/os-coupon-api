<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function responseData($status, $info)
    {
        return [
            'status' => $status,
            'info' => $info
        ];
    }

    protected function responseFail($info = '操作失败')
    {
        return $this->responseData('fail', $info);
    }

    protected function responseSuccess($info = '操作成功')
    {
        return $this->responseData('success', $info);
    }
}
