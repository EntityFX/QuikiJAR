{if $user->isOnline eq true}
<span style="background-color: #fcc; color: #fff; font-weight: bold;">�� �����</span>
{else}
<span style="background-color: #ccf; color: #fff; font-weight: bold;">�� �� �����</span>
{/if}<br/>
<img src="{$photo}" alt="{$user->name} {$user->secondName}" /><hr/>
<h3>ID: {$user->id}</h3>
<strong>{$user->name} {$user->secondName}</strong><br/>
<i>{$user->burthday}</i><br/>
<span style="color: blue">���:</span> {if $user->gender==0}�������{else}�������{/if}<br/>
���� ��������: {$user->burthday}<br />	
mail: <strong>{$user->mail}</strong><br/>
Registration IP: <strong>{$user->ip}</strong><br/>
<a href="/friends/{$user->id}/">��� ��������</a><br/>
<a href="/galary/{$user->id}/">�������</a><br/> 
{if $info neq NULL}
��� ������������: 
    <table border="1">
        <thead>
            <tr><th>���������</th><th>����������</th></tr>
        </thead>
        <tbody>
            {foreach from=$info item=obj} {* ������� ����������*}
                <tr><td>{$obj->title}</td><td>{$obj->text}</td></tr>
            {/foreach} 
        </tbody>
    </table>
{else}
� ��� ��� ������������. <a href="" >����������� ��������</a>.<br/>
{/if}
<a href="{$links.settings}">���������</a><br/> 
<a href="{$links.signOutPath}">�����</a>
