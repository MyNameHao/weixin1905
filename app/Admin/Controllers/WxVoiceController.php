<?php

namespace App\Admin\Controllers;

use App\Model\VoiceModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class WxVoiceController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Model\VoiceModel';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new VoiceModel);

        $grid->column('v_id', __('V id'));
        $grid->column('openid', __('Openid'));
        $grid->column('nickname', __('Nickname'));
        $grid->column('voice', __('Voice'));
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
        $show = new Show(VoiceModel::findOrFail($id));

        $show->field('v_id', __('V id'));
        $show->field('openid', __('Openid'));
        $show->field('nickname', __('Nickname'));
        $show->field('voice', __('Voice'));
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
        $form = new Form(new VoiceModel);

        $form->text('openid', __('Openid'));
        $form->text('nickname', __('Nickname'));
        $form->text('voice', __('Voice'));

        return $form;
    }
}
