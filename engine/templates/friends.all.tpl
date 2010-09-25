<html>
<head>
<title></title>
</head>
<body>
    {if $FRIENDS != NULL}
        Мои дружбаны:
        <ul>        
        {foreach from=$FRIENDS item=rec} {* Выводит подразделы*}
            <li><a href="/user/view/{$rec->id}"><strong>{$rec->name} {$rec->secondName}</strong></a> <a href="delete/{$rec->id}/"><span style="color: red;">X</a></span></li>
        {/foreach}  
        </ul>
    {else}
        <u>У тя друганов нету</u>
    {/if}
    <hr>
    {if $randomFRIENDS != NULL}
        Случайные трое дружбана:
        <ul>        
        {foreach from=$randomFRIENDS item=rec} {* Выводит подразделы*}
            <li><a href="/user/view/{$rec->id}"><strong>{$rec->name} {$rec->secondName}</strong></a> <a href="delete/{$rec->id}/"><span style="color: red;">X</a></span></li>
        {/foreach}  
        </ul>
    {/if} 
    <a href="/user/view/">На мою страницу</a>   
</body>
</html>