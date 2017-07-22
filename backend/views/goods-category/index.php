

<?=\yii\bootstrap\Html::a('添加分类',['goods-category/add',],['class'=>'btn btn-sm btn-info']) ?>

<table class="table table-bordered table-condensed">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>上级分类</th>
        <th>简介</th>
        <th>操作</th>
    </tr>
    <?php foreach($model as $models): ?>
    <tr>
        <td><?=$models->id?></td>
        <td><?=$models->name?></td>
        <td><?=\backend\models\GoodsCategory::getParentName($models->parent_id)?></td>
        <td><?=$models->intro?></td>


        <td><?=\yii\bootstrap\Html::a('修改',['goods-category/edit','id'=>$models->id],['class'=>'btn btn-sm btn-success'])?>
            <?=\yii\bootstrap\Html::a('删除',['goods-category/delete','id'=>$models->id],['class'=>'btn btn-sm btn-danger'])?></td>
        <?php endforeach;?>
</table>

