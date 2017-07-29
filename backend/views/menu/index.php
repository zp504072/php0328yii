

<?=\yii\bootstrap\Html::a('添加菜单',['menu/add',],['class'=>'btn btn-sm btn-info']) ?>

<table class="table table-bordered table-condensed">
    <tr>
        <th>名称</th>
        <th>菜单路径</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    <?php foreach($model as $models): ?>
    <tr>
        <td><?= $models->parent_id>0?'——'.$models->name:$models->name?></td>
        <td><?=$models->menu_url==null?'空':$models->menu_url?></td>
        <td><?=$models->sort?></td>


        <td><?=\yii\bootstrap\Html::a('修改',['menu/edit','id'=>$models->id],['class'=>'btn btn-sm btn-success'])?>
            <?=\yii\bootstrap\Html::a('删除',['menu/del','id'=>$models->id],['class'=>'btn btn-sm btn-danger'])?></td>
        <?php endforeach;?>
</table>
<?php
echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页']);



