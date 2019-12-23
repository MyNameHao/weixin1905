<?php

namespace App\Admin\Controllers;

use App\Model\MediaImgModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Redis;
use GuzzleHttp\Client;

class MediaImgController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Model\MediaImgModel';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new MediaImgModel);

        $grid->column('id', __('Id'));
        $grid->column('mediaid', __('Mediaid'));
        $grid->column('local_path', __('Local path'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(MediaImgModel::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('mediaid', __('Mediaid'));
        $show->field('local_path', __('Local path'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new MediaImgModel);

//        $form->text('mediaid', __('Mediaid'));
        $form->image('local_path', __('Local path'));

        $form->saved(function (Form $form) {
            $d = $form->model();
//            dd($d->id);     //获取自增ID
            //新增临时素材
            $media_info = $this->mediaUpload(storage_path('app/public/'.$d->local_path),'image');
            $m = json_decode($media_info,true);
            dd($m['media_id']);
            $d->where(['id'=>$d->id])->update(['mediaid'=>$m['media_id']]);
        });

        return $form;
    }
    protected function mediaUpload($local_file_path,$media_type)
    {
        $access_token=Redis::get('wx_access_token');
//        dd($access_token);
        $url = 'https://api.weixin.qq.com/cgi-bin/media/upload?access_token='.$access_token.'&type='.$media_type;
        $client = new Client();
        $response = $client->request('POST',$url,[
            'multipart' => [
                [
                    'name'      => 'media',
                    'contents'  => fopen($local_file_path,'r')
                ]
            ]
        ]);
        return $response->getBody();
    }
}
