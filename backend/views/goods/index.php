
<form action="" method="get">
    <div class="input-group col-md-3 pull-right" style="margin-top:0px positon:relative">
        <input type="text" name="keywords" class="form-control" placeholder="请输入商品名"  / >
        <span class="input-group-btn">
               <button class="btn btn-info btn-search">查找</button>
            </span>
    </div>
</form>
<?=\yii\bootstrap\Html::a('添加商品',['goods/add',],['class'=>'btn btn-sm btn-info']) ?>

<table class="table table-bordered table-condensed">
    <tr>
        <th>ID</th>
        <th>商品名称</th>
        <th>货号</th>
        <th>是否在售</th>
        <th>LOGO</th>
        <th>市场售价</th>
        <th>商品售价</th>
        <th>库存</th>
        <th>状态</th>
        <th>排序</th>
        <th>添加时间</th>
        <th>点击率</th>
        <th>操作</th>
    </tr>

    <?php foreach($models as $model): ?>
    <tr>

        <td><?=$model->id?></td>
        <td><?=$model->name?></td>
        <td><?=$model->sn?></td>
        <td><?=$model->in_on_sale?'在售':'下架'?></td>
        <td><?=\yii\bootstrap\Html::img($model->logo,['height'=>50])?></td>
        <td><?=$model->market_price?></td>
        <td><?=$model->shop_price?></td>
        <td><?=$model->stock?></td>

        <td><?=$model->status?'显示':'隐藏'?></td>
        <td><?=$model->sort?></td>
        <td><?=date('Y-m-d H:i:s',$model->create_time)?></td>
        <td><?=$model->view_times?></td>




        <td>
            <?=\yii\bootstrap\Html::a('相册',['goods/gallery','id'=>$model->id],['class'=>'btn btn-sm btn-success'])?>
            <?=\yii\bootstrap\Html::a('查看',['goods/content','id'=>$model->id],['class'=>'btn btn-sm btn-success'])?>
            <?=\yii\bootstrap\Html::a('修改',['goods/edit','id'=>$model->id],['class'=>'btn btn-sm btn-success'])?>
            <?=\yii\bootstrap\Html::a('删除',['goods/delete','id'=>$model->id],['class'=>'btn btn-sm btn-danger'])?></td>
        <?php endforeach;?>
</table>

