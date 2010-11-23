<a href="{$links.signOutPath}" class="jAnc">Выход</a><br/><br/>
{if $user->isOnline eq true}
<span style="background-color: #fcc; color: #fff; font-weight: bold;">На сайте</span>
{else}
<span style="background-color: #ccf; color: #fff; font-weight: bold;">Не на сайте</span>
{/if}<br/>
<img src="{$photo}" alt="{$user->name} {$user->secondName}" /><hr/>
<i>Был в последний раз на сайте:</i>{$lastUpdateTime|date_format:"%eго %B %Y, %H:%M"}<br/>
<h3>ID: {$user->id}</h3>
<strong>{$user->name} {$user->secondName}</strong><br/>
<span style="color: blue">Пол:</span> {if $user->gender==0}Мужской{else}Женский{/if}<br/>
Место нахождения: {$location.country}, {$location.region}, {$location.city}<br/>
Дата рождения: {$user->burthday}<br />	
Зодиак: {$user->zodiac}<br />	
mail: <strong>{$user->mail}</strong><br/>
Registration IP: <strong>{$user->ip}</strong><br/>
<a href="/message/">Сообщения</a><br/>      
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
<a href="{$links.settings}">Настройки</a>