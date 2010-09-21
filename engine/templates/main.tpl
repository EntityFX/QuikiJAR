<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>{$TITLE}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
    </head>
    <body>
        Главная менюшка: <br />
        {if $MENU != NULL}
            <ul>
            {foreach from=$MENU item=rec name=menu}
                <li><a href="{$rec.href}">{$rec.title}</a></li>
            {/foreach}
            </ul>
        {/if}
        Подразделы раздела: <br />
        {if $SUB_SECTIONS != NULL}
            <ul>        
            {foreach from=$SUB_SECTIONS item=rec} {* Выводит подразделы*}
                <li><a href="{$rec.link}">{$rec.title}</a></li>
            {/foreach}  
            </ul>
        {/if}
        <hr />Список от главной страницы: 
        {foreach from=$PATH item=rec name=path}  {* Выводит путь *}
            <a href="{$rec.link}">{$rec.title}</a>
            {if !$smarty.foreach.path.last} :: {/if}
        {/foreach}
        <hr />
        {$TEXT_VAR}  {*Выводимый текст*}  
    </body>
</html>