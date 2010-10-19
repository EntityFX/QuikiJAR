{if $user->isOnline eq true}
<span style="background-color: #fcc; color: #fff; font-weight: bold;">На сайте</span>
{else}
<span style="background-color: #ccf; color: #fff; font-weight: bold;">Не на сайте</span>
{/if}<br/>
<img src="{$photo}" alt="{$user->name} {$user->secondName}" /><hr/>
<h3>ID: {$user->id}</h3>
<strong>{$user->name} {$user->secondName}</strong><br/>
<i>{$user->burthday}</i><br/>
<span style="color: blue">Пол:</span> {if $user->gender==0}Мужской{else}Женский{/if}<br/>
Дата рождения: {$user->burthday}<br />	
mail: <strong>{$user->mail}</strong><br/>
Registration IP: <strong>{$user->ip}</strong><br/>
<a href="/friends/{$user->id}/">Мои дружбаны</a><br/>
<a href="/galary/{$user->id}/">Галерея</a><br/> 
{if $info neq NULL}
Мои предпочтения: 
    <table border="1">
        <thead>
            <tr><th>Увлечение</th><th>Информация</th></tr>
        </thead>
        <tbody>
            {foreach from=$info item=obj} {* Выводит подразделы*}
                <tr><td>{$obj->title}</td><td>{$obj->text}</td></tr>
            {/foreach} 
        </tbody>
    </table>
{else}
У вас нет предпочтений. <a href="" >Рекомендуем добавить</a>.<br/>
{/if}
<a href="{$links.settings}">Настройки</a><br/> 
<a href="{$links.signOutPath}">Выход</a>
