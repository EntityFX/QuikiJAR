<?php /* Smarty version 2.6.26, created on 2010-09-30 01:38:16
         compiled from users.view.tpl */ ?>
Уровень доступа:<?php echo $this->_tpl_vars['accLevel']; ?>
<br/>
<?php if ($this->_tpl_vars['user']->isOnline == true): ?>
<span style="background-color: #fcc; color: #fff; font-weight: bold;">На сайте</span>
<?php else: ?>
<span style="background-color: #ccf; color: #fff; font-weight: bold;">Не на сайте</span>
<?php endif; ?><br/>
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