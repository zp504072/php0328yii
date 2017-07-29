

<?=\yii\bootstrap\Html::a('添加角色',['rbac/add-role',],['class'=>'btn btn-sm btn-info']) ?>

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


        <td><?=\yii\bootstrap\Html::a('修改',['rbac/edit-role','name'=>$models->name],['class'=>'btn btn-sm btn-success'])?>
            <?=\yii\bootstrap\Html::a('删除',['rbac/del-role','name'=>$models->name],['class'=>'btn btn-sm btn-danger'])?></td>
        <?php endforeach;?>
</table>

