

<?=\yii\bootstrap\Html::a('首页',['brand/index',],['class'=>'btn btn-sm btn-info']) ?>

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
        <td>已删除</td>
        <td><?=$model->sort?></td>
        <td><?=\yii\bootstrap\Html::img($model->logo,['height'=>50])?></td>


        <td><?=\yii\bootstrap\Html::a('恢复',['brand/decided','id'=>$model->id],['class'=>'btn btn-sm btn-success'])?>
            <?=\yii\bootstrap\Html::a('彻底删除',['brand/bye','id'=>$model->id],['class'=>'btn btn-sm btn-danger'])?></td>
        <?php endforeach;?>
</table>
<?php
echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页']);


