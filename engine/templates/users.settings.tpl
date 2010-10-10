<form method="post" action="/user/settings/save/">
Страница видна:      
    <select name="level">
        {assign var=per value=0}
        {foreach from=$levelstr item=level} {* Выводит подразделы*}        
            <option value="{$per}" {if $per eq $accLevel}selected="selected"{/if}>{$level}</option>
            {assign var=per value=`$per+1`}         
        {/foreach} 
    </select>
    <input type="submit" value="Установить">
</form>
<hr/>
<form method="post" action="/user/settings/passchange/">
Сменить пароль<br/>      
    Старый пароль: <input type="password" name="oldpass"><br/>
    Новый пароль: <input type="password" name="newpass"><br/>    
    <input type="submit" value="Сменить">
</form>