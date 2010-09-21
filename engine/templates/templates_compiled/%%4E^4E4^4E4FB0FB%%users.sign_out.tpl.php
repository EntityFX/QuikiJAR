<?php /* Smarty version 2.6.26, created on 2010-09-18 21:00:50
         compiled from users.sign_out.tpl */ ?>
<strong>Вход на сайт:</strong><br/>
<form name="signIn" action="<?php echo $this->_tpl_vars['links']['enter']; ?>
" method="post">
    <input type="text" name="mail"><br/>
    <input type="password" name="password"><br/>
    <input type="submit">
</form>
<a href="<?php echo $this->_tpl_vars['links']['register']; ?>
">Регистрация на сайте</a>