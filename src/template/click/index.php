{include common/header@psrphp/admin}
<h1>广告点击记录</h1>

<form method="GET">
    <input type="hidden" name="page" value="1">
    <input type="search" name="item_id" value="{:$request->get('item_id')}" placeholder="请输入广告id" onchange="event.target.form.submit();">
</form>

<table style="margin-top: 20px;">
    <thead>
        <tr>
            <th>ID</th>
            <th>广告ID</th>
            <th>访客IP</th>
            <th>点击时间</th>
        </tr>
    </thead>
    <tbody>
        {foreach $clicks as $vo}
        <tr onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display=='table-row'?'none':'table-row'">
            <td>{$vo.id}</td>
            <td>
                <span>id:{$vo['item_id']}</span>
                {if $vo['item']}
                <span>{$vo['item']['title']}</span>
                {/if}
            </td>
            <td>{$vo.ip}</td>
            <td>{$vo.time}</td>
        </tr>
        <tr style="display: none;">
            <td colspan="4">
                <div>user_agent: <code>{$vo.user_agent}</code> </div>
                <div>referer: <code>{$vo.referer}</code> </div>
            </td>
        </tr>
        {/foreach}
    </tbody>
</table>

<div style="display: flex;flex-direction: row;flex-wrap: wrap;margin-top: 20px;">
    <a href="{echo $router->build('/psrphp/ad/click/index', array_merge($_GET, ['page'=>1]))}">首页</a>
    <a href="{echo $router->build('/psrphp/ad/click/index', array_merge($_GET, ['page'=>max($request->get('page')-1, 1)]))}">上一页</a>
    <a href="{echo $router->build('/psrphp/ad/click/index', array_merge($_GET, ['page'=>min($request->get('page')+1, $maxpage)]))}">下一页</a>
    <a href="{echo $router->build('/psrphp/ad/click/index', array_merge($_GET, ['page'=>$maxpage]))}">末页</a>
</div>
{include common/footer@psrphp/admin}