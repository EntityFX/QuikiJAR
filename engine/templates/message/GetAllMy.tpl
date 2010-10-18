<strong>Все мои сообщения:</strong><br/>
{foreach from=$arr item=rec} {* Выводит друзей в выбор сообщения другу*}   
    <h3>{$rec.Message} от {$rec.FromID}</h3><font size=2>дата и время {$rec.DateTime}</font>
{/foreach}