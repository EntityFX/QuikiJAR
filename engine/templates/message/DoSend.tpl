<strong>Отправить сообщение:</strong><br/>
<form name="msg" action="{$arr}" method="post">
    Кому:<br/> 
        <select id="Select1" name="sel">
        {foreach from=$arr item=rec} {* Выводит друзей в выбор сообщения другу*}   
            <option value=$count>{$rec.UserID}</option> 
        {/foreach}
        </select><br/>     
    Текст сообщения:<br/>
        <textarea cols="50" name="mes" rows="10"></textarea><br/> 
    Смайлики:<br/>
        <textarea cols="50" readonly="readonly" name="smiles" rows="4">=)   =*)   =(   ;D</textarea><br/> 
        <input type="submit">
</form>