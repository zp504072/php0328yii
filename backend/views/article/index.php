
<form action="" method="get">
    <div class="input-group col-md-3 pull-right" style="margin-top:0px positon:relative">
        <input type="text" name="keywords" class="form-control" placeholder="请输入文章名"  / >
        <span class="input-group-btn">
               <button class="btn btn-info btn-search">查找</button>
            </span>
    </div>
</form>
<?=\yii\bootstrap\Html::a('添加文章',['article/add',],['class'=>'btn btn-sm btn-info']) ?>
&emsp;
<?=\yii\bootstrap\Html::a('回收站',['article/recovery',],['class'=>'btn btn-sm btn-warning'])?>

<table class="table table-bordered table-condensed">
    <tr>
        <th>ID</th>
        <th>标题</th>
        <th>简介</th>
        <th>分类</th>
        <th>排序</th>
        <th>状态</th>
        <th>添加时间</th>
        <th>操作</th>
    </tr>
    <?php foreach($models as $model): ?>
    <tr>
        <td><?=$model->id?></td>
        <td><?=$model->name?></td>
        <td><?=$model->intro?></td>
        <td><?=$model->articleCategory->name?></td>
        <td><?=$model->sort?></td>
        <td><?=$model->status?'显示':'隐藏'?></td>
        <td><?=date('Y-m-d H:i:s',$model->create_time)?></td>




        <td><?=\yii\bootstrap\Html::a('修改',['article/edit','id'=>$model->id],['class'=>'btn btn-sm btn-success'])?>
            <?=\yii\bootstrap\Html::a('删除',['article/delete','id'=>$model->id],['class'=>'btn btn-sm btn-danger'])?></td>
        <?php endforeach;?>
</table>
<?php
echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页']);

