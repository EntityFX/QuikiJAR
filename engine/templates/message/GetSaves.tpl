<strong>Черновики:</strong><br/>
{foreach from=$arr item=rec} {* Выводит список черновиков*}   
    <h3>{$rec.Message}</h3><font size=2>дата и время {$rec.DateTime}</font>
{/foreach}