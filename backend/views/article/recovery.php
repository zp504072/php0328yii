
<?=\yii\bootstrap\Html::a('首页',['article/index',],['class'=>'btn btn-sm btn-warning'])?>
    <table class="table table-bordered table-condensed">

        <tr>
            <th>ID</th>
            <th>标题</th>
            <th>简介</th>
            <th>分类</th>
            <th>排序</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        <?php foreach($models as $model): ?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->name?></td>
            <td><?=$model->intro?></td>
            <td><?=$model->articleCategory->name?></td>
            <td><?=$model->sort?></td>
            <td>已删除</td>





            <td><?=\yii\bootstrap\Html::a('恢复',['article/decided','id'=>$model->id],['class'=>'btn btn-sm btn-success'])?>
                <?=\yii\bootstrap\Html::a('彻底删除',['article/bye','id'=>$model->id],['class'=>'btn btn-sm btn-danger'])?></td>
            <?php endforeach;?>
    </table>
<?php
echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页']);

