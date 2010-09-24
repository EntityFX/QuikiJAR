<html>
<head>
<title></title>
</head>
<body>
    <img src="{$photo}" alt="{$user->name} {$user->secondName}" /><hr/>
    <h3>ID: {$user->id}</h3>
    <strong>{$user->name} {$user->secondName}</strong><br/>
    <i>{$user->burthday}</i><br/>
    <span style="color: blue">Пол:</span> {if $user->gender==0}Мужской{else}Женский{/if}<br/>
    mail: <strong>{$user->mail}</strong><br/>
    Registration IP: <strong>{$user->ip}</strong><br/>
    <a href="/friends">Мои дружбаны</a><br/>
    <a href="{$links.signOutPath}">Выход</a>
</body>
</html>