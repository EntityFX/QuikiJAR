{if $GROUPS != NULL}
    ��� ������:
    <table>       
    {foreach from=$GROUPS item=rec} {* ������� ����������*}
        <tr><td><a href="show/{$rec->id}/"><span style="color: green;"><b>{$rec->title}</b></span></a></td><td><a href="del/{$rec->id}/"><span style="color: red;">X</span></a></td></tr>
    {/foreach}  
    </table>
{else}
    <u>� �� ��� �����</u>
{/if}
<form action="add/" method="post">
    ������� ������: <input type="text" name="group_name"><br/>
    <input type="submit" value="������� ������">
</form>