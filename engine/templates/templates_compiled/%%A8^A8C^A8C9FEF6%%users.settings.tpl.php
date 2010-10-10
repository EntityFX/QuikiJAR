<?php /* Smarty version 2.6.26, created on 2010-10-11 00:47:11
         compiled from users.settings.tpl */ ?>
<form method="post" action="/user/settings/save/">
Страница видна:      
    <select name="level">
        <?php $this->assign('per', 0); ?>
        <?php $_from = $this->_tpl_vars['levelstr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['level']):
?>         
            <option value="<?php echo $this->_tpl_vars['per']; ?>
" <?php if ($this->_tpl_vars['per'] == $this->_tpl_vars['accLevel']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['level']; ?>
</option>
            <?php $this->assign('per', ($this->_tpl_vars['per']+1)); ?>         
        <?php endforeach; endif; unset($_from); ?> 
    </select>
    <input type="submit" value="Установить">
</form>
<hr/>
<form method="post" action="/user/settings/passchange/">
Сменить пароль<br/>      
    Старый пароль: <input type="password" name="oldpass"><br/>
    Новый пароль: <input type="password" name="newpass"><br/>    
    <input type="submit" value="Сменить">
</form>