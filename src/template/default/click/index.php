{include common/header@psrphp/admin}
<h1>点击记录</h1>

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
            <th>USER_AGENT</th>
            <th>REFERER</th>
            <th>跳转地址</th>
            <th>点击时间</th>
        </tr>
    </thead>
    <tbody>
        {foreach $clicks as $vo}
        <tr>
            <td>{$vo.id}</td>
            <td>{$vo.item_id}</td>
            <td>{$vo.ip}</td>
            <td>{$vo.user_agent}</td>
            <td>{$vo.referer}</td>
            <td>{$vo.url}</td>
            <td>{$vo.time}</td>
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