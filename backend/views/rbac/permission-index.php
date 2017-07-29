

<?=\yii\bootstrap\Html::a('添加权限',['rbac/add-permission',],['class'=>'btn btn-sm btn-info']) ?>

<table class="table table-bordered table-condensed">
    <tr>

        <th>名称</th>
        <th>简介</th>
        <th>操作</th>
    </tr>
    <?php foreach($model as $models): ?>
    <tr>

        <td><?=$models->name?></td>

        <td><?=$models->description?></td>


        <td><?=\yii\bootstrap\Html::a('修改',['rbac/edit-permission','name'=>$models->name],['class'=>'btn btn-sm btn-success'])?>
            <?=\yii\bootstrap\Html::a('删除',['rbac/del-permission','name'=>$models->name],['class'=>'btn btn-sm btn-danger'])?></td>
        <?php endforeach;?>
</table>

