<strong>��� ��� ���������:</strong><br/>
{foreach from=$arr item=rec} {* ������� ������ � ����� ��������� �����*}   
    <h3>{$rec.Message} �� {$rec.FromID}</h3><font size=2>���� � ����� {$rec.DateTime}</font>
{/foreach}