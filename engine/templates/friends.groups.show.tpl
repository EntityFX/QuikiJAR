<a href="/friends/groups/">� ������ �����</a><br/>
�������� ������: <b>{$GROUP->title}</b><br/>
    {if $GROUP_FRIENDS != NULL}
        <ul>      
        {foreach from=$GROUP_FRIENDS item=rec} {* ������� �������������*}
            <li>{$rec->name} {$rec->secondName}<a href="/friends/groups/deletefriend/{$GROUP->id}/{$rec->id}/"><span style="color: red;">X</span></a></li>     
        {/foreach}  
        </ul>
    {/if}
�������� ����� � ������:  
<form action="../../addfriend/{$GROUP->id}/" method="post">
    �������� �����: 
    {if $FRIENDS != NULL}
        <select name="friend_ID">        
        {foreach from=$FRIENDS item=rec} {* ������� ������ ������ ������*}
            <option value="{$rec->id}">{$rec->name} {$rec->secondName}</option>     
        {/foreach}  
        </select>
    {/if}
    <input type="submit" value="��������">
</form>