<form method="post" action="/user/settings/save/">
�������� �����:      
    <select name="level">
        {assign var=per value=0}
        {foreach from=$levelstr item=level} {* ������� ����������*}        
            <option value="{$per}" {if $per eq $accLevel}selected="selected"{/if}>{$level}</option>
            {assign var=per value=`$per+1`}         
        {/foreach} 
    </select>
    <input type="submit" value="����������">
</form>
<hr/>
<form method="post" action="/user/settings/passchange/">
������� ������<br/>      
    ������ ������: <input type="password" name="oldpass"><br/>
    ����� ������: <input type="password" name="newpass"><br/>    
    <input type="submit" value="�������">
</form>