{include common/header@psrphp/admin}
<h1>点击记录</h1>

<form method="GET">
    <input type="month" name="month" value="{$request->get('month', date('Y-m'))}" onchange="this.form.submit()">
    <select name="item_id" onchange="this.form.submit()">
        <option value="">请选择</option>
        {foreach $billboards as $vo}
        <optgroup label="{$vo.title}">
            {foreach $items as $sub}
            {if $sub['billboard_id'] == $vo['id']}
            {if $sub['id'] == $request->get('item_id')}
            <option value="{$sub.id}" selected>{$sub.title}</option>
            {else}
            <option value="{$sub.id}">{$sub.title}</option>
            {/if}
            {/if}
            {/foreach}
        </optgroup>
        {/foreach}
    </select>
</form>

<div style="margin-top: 30px;border: 1px solid gray;padding: 20px 20px 30px 20px;">
    <div style="display: flex;justify-content: center;gap: 20px;">
        <div style="display: flex;gap: 5px;align-items: center;">
            <div style="width: 30px;height: 20px;background: #2196F3;"></div>
            <div>展现量</div>
        </div>
        <div style="display: flex;gap: 5px;align-items: center;">
            <div style="width: 30px;height: 20px;background: red;"></div>
            <div>点击量</div>
        </div>
    </div>
    <div style="display: flex;height: 200px;align-items: flex-end;gap: 10px;">
        {foreach $datas as $key => $vo}
        <div style="flex-grow: 1;position: relative;display: flex;align-items: flex-end;">
            <div style="position: relative;flex-grow: 1;">
                <div style="position: absolute;top: -20px;width: 100%;text-align: center;">{$vo.show}</div>
                <div style="height: {:ceil($vo['show']*200/$max+1)}px;background: #2196F3;"></div>
            </div>
            <div style="position: relative;flex-grow: 1;">
                <div style="position: absolute;top: -20px;width: 100%;text-align: center;">{$vo.click}</div>
                <div style="height: {:ceil($vo['click']*200/$max+1)}px;background: red;"></div>
            </div>
            <div style="position: absolute;bottom: -20px;width: 100%;text-align: center;">{:substr($key, 8)}</div>
        </div>
        {/foreach}
    </div>
</div>
{include common/footer@psrphp/admin}