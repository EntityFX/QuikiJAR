<strong>��������� ���������:</strong><br/>
<form name="msg" action="{$arr}" method="post">
    ����:<br/> 
        <select id="Select1" name="sel">
            {foreach from=$arr item=rec} {* ������� ������ � ����� �������� ��������� �����*}   
                <option value={$rec->id}>{$rec->name} < {$rec->mail} > {$rec->secondName} </option> 
            {/foreach}
        </select><br/>     
    ����� ���������:<br/>
        <textarea cols="50" name="mes" rows="10"></textarea><br/> 
    ��������:<br/>
        <textarea cols="50" readonly="readonly" rows="4">=)   =*)   =(   ;D</textarea><br/> 
        <input type="submit" name="sbmt">
        <input type="submit" name="sv" value="���������">
</form>