

<?=\yii\bootstrap\Html::a('添加品牌',['brand/add',],['class'=>'btn btn-sm btn-info']) ?>
<?=\yii\bootstrap\Html::a('回收站',['brand/recovery',],['class'=>'btn btn-sm btn-warning']) ?>

<table class="table table-bordered table-condensed">
    <tr>
        <th>ID</th>
        <th>品牌名称</th>
        <th>简介</th>
        <th>状态</th>
        <th>排序</th>
        <th>LOGO</th>
        <th>操作</th>
    </tr>
    <?php foreach($models as $model): ?>
    <tr>
        <td><?=$model->id?></td>
        <td><?=$model->name?></td>
        <td><?=$model->intro?></td>
        <td><?=$model->status?'显示':'隐藏'?></td>
        <td><?=$model->sort?></td>
        <td><?=\yii\bootstrap\Html::img($model->logo,['height'=>50])?></td>



        <td><?=\yii\bootstrap\Html::a('修改',['brand/edit','id'=>$model->id],['class'=>'btn btn-sm btn-success'])?>
            <?=\yii\bootstrap\Html::a('删除',['brand/delete','id'=>$model->id],['class'=>'btn btn-sm btn-danger'])?></td>
        <?php endforeach;?>
</table>
<?php
echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页']);


