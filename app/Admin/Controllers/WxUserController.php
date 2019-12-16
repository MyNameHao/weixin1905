<?php

namespace App\Admin\Controllers;

use App\Model\WeixinUser;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class WxUserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Model\WeixinUser';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new WeixinUser);

        $grid->column('uid', __('Uid'));
        $grid->column('openid', __('Openid'));
        $grid->column('sub_time', __('添加时间'))->display(function($time){
            return date('Y-m-d H:i:s',$time);
        });
        $grid->column('sex', __('性别'))->display(function($sex){
            if($sex==1){
                return '男';
            }elseif($sex==2){
                return '女';
            }else{
                return '已隐藏';
            }
        });
        $grid->column('nickname', __('用户名'));
        //$grid->column('created_at', __('Created at'));
        //$grid->column('updated_at', __('Updated at'));

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
        $show = new Show(WeixinUser::findOrFail($id));

        $show->field('uid', __('Uid'));
        $show->field('openid', __('Openid'));
        $show->field('sub_time', __('Sub time'));
        $show->field('sex', __('Sex'));
        $show->field('nickname', __('Nickname'));
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
        $form = new Form(new WeixinUser);

        $form->text('openid', __('Openid'));
        $form->number('sub_time', __('Sub time'));
        $form->switch('sex', __('Sex'));
        $form->text('nickname', __('Nickname'));

        return $form;
    }
}
