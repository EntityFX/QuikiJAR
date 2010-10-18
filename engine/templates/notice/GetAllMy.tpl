<strong>Все мои объявления:</strong><br/>
{foreach from=$arr item=rec} {* Выводит друзей в выбор сообщения другу*}   
    <b>Тема: {$rec.Theme}</b></br> <font size=4 color=darkred>{$rec.Text}</font></br>  от {$rec.UserID} </br>
{/foreach}