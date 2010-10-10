<?php /* Smarty version 2.6.26, created on 2010-10-10 23:07:12
         compiled from users.accessLevel.tpl */ ?>
<?php if ($this->_tpl_vars['level'] == 0): ?>
Для всех
<?php elseif ($this->_tpl_vars['level'] == 1): ?>
Только дря зарегистрированных
<?php elseif ($this->_tpl_vars['level'] == 2): ?>
Для друзей и для знакомств
<?php elseif ($this->_tpl_vars['level'] == 3): ?>
Для друзей
<?php elseif ($this->_tpl_vars['level'] == 4): ?>
Никому
<?php endif; ?>