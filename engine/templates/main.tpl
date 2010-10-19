<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />   
        <title>{$TITLE}</title>
        <link rel="stylesheet" href="/css/style.css" type="text/css">  
        <link type="text/css" href="/css/qtheme/jquery-ui-1.8.5.custom.css" rel="stylesheet" />    
        <script type="text/javascript" src="/engine/js/jquery-1.4.2.min.js"></script>
        <script type="text/javascript" src="/engine/js/jquery-ui-1.8.5.custom.min.js"></script>
        <script type="text/javascript" src="/engine/js/jquery.ui.datepicker-ru.js"></script>
        {literal}
        <script type="text/javascript">
            $(function() 
            {
                $.datepicker.setDefaults($.datepicker.regional["ru"]);
                $( "#datepicker" ).datepicker(
                    { 
                        dateFormat: 'yy-mm-dd',
                        changeMonth: true,
                        changeYear: true
                    });
                $("input:submit").button();
                $("input:checkbox").buttonset();
            });
        </script>
        {/literal}
    </head>                                                   
    <body>
    <div id="slider"></div>
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