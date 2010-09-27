<a href="/friends/groups/">К списку групп</a><br/>
Название группы: <b>{$GROUP->title}</b><br/>
    {if $GROUP_FRIENDS != NULL}
        <ul>      
        {foreach from=$GROUP_FRIENDS item=rec} {* Выводит друзейвгруппе*}
            <li>{$rec->name} {$rec->secondName}<a href="/friends/groups/deletefriend/{$GROUP->id}/{$rec->id}/"><span style="color: red;">X</span></a></li>     
        {/foreach}  
        </ul>
    {/if}
Добавить друга в группу:  
<form action="../../addfriend/{$GROUP->id}/" method="post">
    Выберите друга: 
    {if $FRIENDS != NULL}
        <select name="friend_ID">        
        {foreach from=$FRIENDS item=rec} {* Выводит полный список друзей*}
            <option value="{$rec->id}">{$rec->name} {$rec->secondName}</option>     
        {/foreach}  
        </select>
    {/if}
    <input type="submit" value="Добавить">
</form>