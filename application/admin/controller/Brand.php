<?php
namespace app\admin\controller;

use think\Controller;

class Brand extends Controller
{
    /**
     * 列表
     * @return [type] [description]
     */
    public function lists()
    {
        $brandRes = db('brand')->order('id desc')->paginate(10);
        $this->assign([
            'brandRes' => $brandRes,
        ]);
        return view('lists');
    }

    /**
     * 添加
     * @return [type] [description]
     */
    public function add()
    {
        if (request()->isPost()) {
            //接受参数
            $data = input('post.');
            if ($data['brand_url'] && stripos($data['brand_url'], 'http://') === false) {
                $data['brand_url'] = 'http://' . $data['brand_url'];
            }

            //处理图片上传
            if ($_FILES['brand_pic']['tmp_name']) {
                $data['brand_pic'] = $this->upload();
            }
            //进行数据的验证
            $validate = validate('Brand');
            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }
            //进行添加
            $add = db('brand')->insert($data);
            if ($add) {
                $this->success('添加品牌成功！', 'lists');
            }
            $this->error('添加品牌失败');
        }

        return view();
    }

    /**
     * 修改
     * @return [type] [description]
     */
    public function update()
    {
        if (request()->isPost()) {
            //接受参数
            $data = input('post.');
            if ($data['brand_url'] && stripos($data['brand_url'], 'http://') === false) {
                $data['brand_url'] = 'http://' . $data['brand_url'];
            }

            //处理图片上传
            if ($_FILES['brand_pic']['tmp_name']) {
                $brand_pic = db('brand')->where('id', $data['id'])->value('brand_pic');
                if ($brand_pic) {
                    $brand_pic = IMG_UPLOADS . $brand_pic;
                    @unlink($brand_pic);
                }
                $data['brand_pic'] = $this->upload();
            }

            //数据入库前进行验证
            $validate = validate('Brand');
            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }

            $save = db('brand')->update($data);

            if ($save !== false) {
                $this->success('修改品牌成功！', 'lists');
            }
            $this->error('修改品牌失败');
        }
        //编辑页面赋值
        $id       = input('id');
        $brandRes = db('brand')->find($id);
        $this->assign([
            'brandRes' => $brandRes,
        ]);
        return view();
    }

    /**
     * 删除
     * @return [type] [description]
     */
    public function delete($id)
    {
        $del = db('brand')->delete($id);
        if ($del) {
            $this->success('删除品牌成功！', 'lists');
        } else {
            $this->success('删除品牌失败！');
        }
        return view();
    }

    //上传图片操作
    public function upload()
    {
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('brand_pic');
        // 移动到框架应用根目录/public/uploads/ 目录下
        if ($file) {
            $info = $file->move(ROOT_PATH . 'public' . DS . 'static' . DS . 'uploads');
            if ($info) {
                // 成功上传后 获取上传信息
                return $info->getSaveName();
            } else {
                // 上传失败获取错误信息
                echo $file->getError();
                $this->error('添加品牌失败', 'add');
            }
        }
    }

}
