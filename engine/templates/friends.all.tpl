<html>
<head>
<title></title>
</head>
<body>
    {if $FRIENDS != NULL}
        ��� ��������:
        <ul>        
        {foreach from=$FRIENDS item=rec} {* ������� ����������*}
            <li><a href="/user/view/{$rec->id}"><strong>{$rec->name} {$rec->secondName}</strong></a> <a href="delete/{$rec->id}/"><span style="color: red;">X</a></span></li>
        {/foreach}  
        </ul>
    {else}
        <u>� �� �������� ����</u>
    {/if}
    <hr>
    {if $randomFRIENDS != NULL}
        ��������� ���� ��������:
        <ul>        
        {foreach from=$randomFRIENDS item=rec} {* ������� ����������*}
            <li><a href="/user/view/{$rec->id}"><strong>{$rec->name} {$rec->secondName}</strong></a> <a href="delete/{$rec->id}/"><span style="color: red;">X</a></span></li>
        {/foreach}  
        </ul>
    {/if} 
    <a href="/user/view/">�� ��� ��������</a>   
</body>
</html>