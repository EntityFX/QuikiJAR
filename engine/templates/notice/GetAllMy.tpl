<strong>��� ��� ����������:</strong><br/>
{foreach from=$arr item=rec} {* ������� ������ � ����� ��������� �����*}   
    <b>����: {$rec.Theme}</b></br> <font size=4 color=darkred>{$rec.Text}</font></br>  �� {$rec.UserID} </br>
{/foreach}