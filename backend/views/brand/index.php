

<?=\yii\bootstrap\Html::a('添加品牌',['brand/add',],['class'=>'btn btn-sm btn-info']) ?>

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
    <?php foreach($brands as $brand): ?>
    <tr>
        <td><?=$brand->id?></td>
        <td><?=$brand->name?></td>
        <td><?=$brand->intro?></td>
        <td><?=$brand->status?'显示':'隐藏'?></td>
        <td><?=$brand->sort?></td>
        <td><?=\yii\bootstrap\Html::img($brand->logo,['height'=>50])?></td>


        <td><?=\yii\bootstrap\Html::a('修改',['brand/edit','id'=>$brand->id],['class'=>'btn btn-sm btn-success'])?>
            <?=\yii\bootstrap\Html::a('删除',['brand/delete','id'=>$brand->id],['class'=>'btn btn-sm btn-danger'])?></td>
        <?php endforeach;?>
</table>

