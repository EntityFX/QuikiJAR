<strong>��������� ���������:</strong><br/>
<form name="msg" action="{$arr}" method="post">
    ����:<br/> 
        <select id="Select1" name="sel">
        {foreach from=$arr item=rec} {* ������� ������ � ����� ��������� �����*}   
            <option value=$count>{$rec.UserID}</option> 
        {/foreach}
        </select><br/>     
    ����� ���������:<br/>
        <textarea cols="50" name="mes" rows="10"></textarea><br/> 
    ��������:<br/>
        <textarea cols="50" readonly="readonly" name="smiles" rows="4">=)   =*)   =(   ;D</textarea><br/> 
        <input type="submit">
</form>