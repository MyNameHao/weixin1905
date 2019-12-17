<?php

namespace App\Admin\Controllers;

use App\Model\ImgModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class WxImgController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Model\ImgModel';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ImgModel);

        $grid->column('i_id', __('I id'));
        $grid->column('openid', __('Openid'));
        $grid->column('nickname', __('Nickname'));
        $grid->column('img', __('Img'))->display(function($img){
//            return '<img src="'.env('APP_UPLOAD').$img.'" height="50" width="50">';
            return '<img src="'.env("APP_UPLOAD").$img.'"height="50" width="50">';
        });
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
        $show = new Show(ImgModel::findOrFail($id));

        $show->field('i_id', __('I id'));
        $show->field('openid', __('Openid'));
        $show->field('nickname', __('Nickname'));
        $show->field('img', __('Img'));
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
        $form = new Form(new ImgModel);

        $form->text('openid', __('Openid'));
        $form->text('nickname', __('Nickname'));
        $form->image('img', __('Img'));

        return $form;
    }
}
