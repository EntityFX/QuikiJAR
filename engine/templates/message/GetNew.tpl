<strong>Входящие сообщения:</strong><br/>
{foreach from=$arr item=rec} {* Выводит список входящих, т.е. ещё не прочитанных сообщений*}   
    <h3>{$rec.Message} от {$rec.FromID}</h3><font size=2>дата и время {$rec.DateTime}</font>
{/foreach}