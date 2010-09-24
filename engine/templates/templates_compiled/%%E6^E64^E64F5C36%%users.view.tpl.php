<?php /* Smarty version 2.6.26, created on 2010-09-24 18:26:46
         compiled from users.view.tpl */ ?>
<html>
<head>
<title></title>
</head>
<body>
    <img src="<?php echo $this->_tpl_vars['photo']; ?>
" alt="<?php echo $this->_tpl_vars['user']->name; ?>
 <?php echo $this->_tpl_vars['user']->secondName; ?>
" /><hr/>
    <h3>ID: <?php echo $this->_tpl_vars['user']->id; ?>
</h3>
    <strong><?php echo $this->_tpl_vars['user']->name; ?>
 <?php echo $this->_tpl_vars['user']->secondName; ?>
</strong><br/>
    <i><?php echo $this->_tpl_vars['user']->burthday; ?>
</i><br/>
    <span style="color: blue">Пол:</span> <?php if ($this->_tpl_vars['user']->gender == 0): ?>Мужской<?php else: ?>Женский<?php endif; ?><br/>
    mail: <strong><?php echo $this->_tpl_vars['user']->mail; ?>
</strong><br/>
    Registration IP: <strong><?php echo $this->_tpl_vars['user']->ip; ?>
</strong><br/>
    <a href="/friends">Мои дружбаны</a><br/>
    <a href="<?php echo $this->_tpl_vars['links']['signOutPath']; ?>
">Выход</a>
</body>
</html>