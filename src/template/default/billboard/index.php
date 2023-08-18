{include common/header@psrphp/admin}
<h1>广告位管理</h1>

<fieldset>
    <legend>管理</legend>
    <a href="{:$router->build('/psrphp/ad/billboard/create')}">新增广告位</a>
</fieldset>

<table style="margin-top: 20px;">
    <thead>
        <tr>
            <th>名称</th>
            <th>备注</th>
            <th>管理</th>
            <th>调用</th>
        </tr>
    </thead>
    <tbody>
        {foreach $billboards as $vo}
        <tr>
            <td>
                {$vo.name}
            </td>
            <td>
                {$vo.tips}
            </td>
            <td>
                <a href="{:$router->build('/psrphp/ad/billboard/update', ['id'=>$vo['id']])}">编辑</a>
                <a href="{:$router->build('/psrphp/ad/billboard/delete', ['id'=>$vo['id']])}" onclick="return confirm('确定删除吗？删除后不可恢复！');">删除</a>
                <a href="{:$router->build('/psrphp/ad/item/index', ['billboard_id'=>$vo['id']])}">广告管理</a>
            </td>
            <td><code>{literal}{ad{/literal} {$vo.name}{literal}}{/literal}</code></td>
        </tr>
        {/foreach}
    </tbody>
</table>
{include common/footer@psrphp/admin}