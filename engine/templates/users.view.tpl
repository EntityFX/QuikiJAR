<html>
<head>
<title></title>
</head>
<body>
    <img src="{$photo}" alt="{$user->name} {$user->secondName}" /><hr/>
    <h3>ID: {$user->id}</h3>
    <strong>{$user->name} {$user->secondName}</strong><br/>
    <i>{$user->burthday}</i><br/>
    <span style="color: blue">���:</span> {if $user->gender==0}�������{else}�������{/if}<br/>
    mail: <strong>{$user->mail}</strong><br/>
    Registration IP: <strong>{$user->ip}</strong><br/>
    <a href="/friends">��� ��������</a><br/>
    <a href="{$links.signOutPath}">�����</a>
</body>
</html>