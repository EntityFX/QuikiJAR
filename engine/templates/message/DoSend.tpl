<strong>Отправить сообщение:</strong><br/>
<form name="msg" action="{$arr}" method="post">
    Кому:<br/> 
        <select id="Select1" name="sel">
            {foreach from=$arr item=rec} {* Выводит друзей в выбор отправки сообщения другу*}   
                <option value={$rec->id}>{$rec->name} < {$rec->mail} > {$rec->secondName} </option> 
            {/foreach}
        </select><br/>     
    Текст сообщения:<br/>
        <textarea cols="50" name="mes" rows="10"></textarea><br/> 
    Смайлики:<br/>
        <textarea cols="50" readonly="readonly" rows="4">=)   =*)   =(   ;D</textarea><br/> 
        <input type="submit" name="sbmt">
        <input type="submit" name="sv" value="Сохранить">
</form>