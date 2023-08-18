{include common/header@psrphp/admin}
<h1>广告管理</h1>

<fieldset>
    <legend>添加广告：</legend>
    <a href="{:$router->build('/psrphp/ad/item/create', ['billboard_id'=>$billboard['id']])}">添加</a>
</fieldset>

<table style="margin-top: 15px;">
    <thead>
        <tr>
            <th>类型</th>
            <th>备注</th>
            <th>管理</th>
        </tr>
    </thead>
    <tbody>
        {foreach $items as $vo}
        <tr>
            <td>
                {$vo.type}
            </td>
            <td>
                {$vo.tips}
            </td>
            <td>
                <a href="{:$router->build('/psrphp/ad/item/update', ['id'=>$vo['id']])}">编辑</a>
                <a href="{:$router->build('/psrphp/ad/item/delete', ['id'=>$vo['id']])}" onclick="return confirm('确定删除吗？删除后不可恢复！');">删除</a>
            </td>
        </tr>
        {/foreach}
    </tbody>
</table>
{include common/footer@psrphp/admin}