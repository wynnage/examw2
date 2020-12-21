<?php

namespace app\api\controller;

use think\Controller;
use think\Loader;
use think\Request;

class User extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //接收参数
        $keyword = input("keyword");
        $where = [];
        if (!empty($keyword)) {
            //根据用户名或者手机号搜索
            $where ["username|tel"]= ['like', "%{$keyword}%"];
        }
        //查询数据
        $data = model("User")->where($where)->paginate(2, ['query' => ['keyword' => $keyword]]);
        //返回数据
        return json(['code' => 200, 'msg' => "success", "data" => $data, "keyword" => $keyword]);
    }

    public function save(Request $request)
    {
        //接收参数
        $param = $request->param();
        //验证参数
        $validate = Loader::validate("User");
        if (!$validate->check($param)) {
            return json(['code' => 500, 'msg' => $validate->getError(), "data" => ""]);
        }
        //密码加密
        $param['psw'] = md5(md5($param['psw']));
        //添加数据
        $res = model("User")->allowField(true)->save($param);
        if ($res) {
            return json(['code' => 200, 'msg' => "success", "data" => ""]);
        } else {
            return json(['code' => 500, 'msg' => "fail", "data" => ""]);
        }
    }

    /**
     * 保存更新的资源
     *
     * @param \think\Request $request
     * @param int $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //验证id
        if (!is_numeric($id) || $id <= 0) {
            return json(['code' => 500, 'msg' => "操作异常", "data" => ""]);
        }
        //接收参数
        $param = $request->param();
        //验证参数
        $validate = $this->validate($param, [
            "username|用户名" => "unique:user",
            "tel|手机号" => "regex:[1][356789]\d{9}",
            "email|邮箱" => "email",
        ]);
        if ($validate !== true) {
            return json(['code' => 500, 'msg' => $validate, "data" => ""]);
        }
        //修改数据
        $res = model("User")->update($param, true, ['id' => $id]);
        if ($res) {
            return json(['code' => 200, 'msg' => "success", "data" => ""]);
        } else {
            return json(['code' => 500, 'msg' => "fail", "data" => ""]);
        }


    }

    /**
     * 删除指定资源
     *
     * @param int $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //验证id
        if (!is_numeric($id) || $id <= 0) {
            return json(['code' => 500, 'msg' => "操作异常", "data" => ""]);
        }
        //删除数据
        $res = model("User")::destroy($id);
        if ($res) {
            return json(['code' => 200, 'msg' => "success", "data" => ""]);
        } else {
            return json(['code' => 500, 'msg' => "fail", "data" => ""]);
        }
    }
}
