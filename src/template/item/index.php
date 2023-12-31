{include common/header@psrphp/admin}
<h1>广告管理</h1>

<fieldset>
    <legend>添加广告</legend>
    <a href="{:$router->build('/psrphp/ad/item/create', ['billboard_id'=>$billboard['id'], 'type'=>'image'])}">图片</a>
    <a href="{:$router->build('/psrphp/ad/item/create', ['billboard_id'=>$billboard['id'], 'type'=>'WYSIWYG'])}">富文本</a>
    <a href="{:$router->build('/psrphp/ad/item/create', ['billboard_id'=>$billboard['id'], 'type'=>'html'])}">HTML代码</a>
    <a href="{:$router->build('/psrphp/ad/item/create', ['billboard_id'=>$billboard['id'], 'type'=>'tpl'])}">模板代码</a>
</fieldset>

<table style="margin-top: 20px;">
    <thead>
        <tr>
            <th>ID</th>
            <th>标题</th>
            <th>类型</th>
            <th>最大展现量</th>
            <th>展现量</th>
            <th>最大点击量</th>
            <th>点击量</th>
            <th>开始展示时间</th>
            <th>截止展示时间</th>
            <th>pc端</th>
            <th>移动端</th>
            <th>状态</th>
            <th>管理</th>
        </tr>
    </thead>
    <tbody>
        {foreach $items as $vo}
        <tr>
            <td>{$vo.id}</td>
            <td>{$vo.title}</td>
            <td>{$vo.type}</td>
            <td>{$vo.max_showtimes}</td>
            <td>{$vo.showtimes}</td>
            <td>{$vo.max_click}</td>
            <td>
                <a href="{echo $router->build('/psrphp/ad/click/index', ['item_id'=>$vo['id']])}">{$vo.click}</a>
            </td>
            <td>{$vo.starttime}</td>
            <td>{$vo.endtime}</td>
            <td>
                {if $vo['pc'] == 1}
                <span>yes</span>
                {else}
                <span>no</span>
                {/if}
            </td>
            <td>
                {if $vo['mobile'] == 1}
                <span>yes</span>
                {else}
                <span>no</span>
                {/if}
            </td>
            <td>
                {if $vo['state'] == 1}
                <span>已发布</span>
                {else}
                <span>未发布</span>
                {/if}
            </td>
            <td>
                <a href="{:$router->build('/psrphp/ad/item/update', ['id'=>$vo['id']])}">编辑</a>
                <a href="{:$router->build('/psrphp/ad/item/delete', ['id'=>$vo['id']])}" onclick="return confirm('确定删除吗？删除后不可恢复！');">删除</a>
                <a href="#" onclick="event.target.parentNode.parentNode.nextElementSibling.style.display=event.target.parentNode.parentNode.nextElementSibling.style.display=='table-row'?'none':'table-row'">预览</a>
            </td>
        </tr>
        <tr style="display: none;">
            <td colspan="13">
                {echo \App\Psrphp\Ad\Model\Ad::renderItem($vo, $billboard)}
            </td>
        </tr>
        {/foreach}
    </tbody>
</table>
{include common/footer@psrphp/admin}