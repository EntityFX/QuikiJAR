<<<<<<< .mine<?php /* Smarty version 2.6.26, created on 2010-09-26 17:37:50
=======<?php /* Smarty version 2.6.26, created on 2010-09-26 23:45:21
>>>>>>> .theirs         compiled from friends.all.tpl */ ?>
<a href="/friends/groups/">Группы</a>  
<?php if ($this->_tpl_vars['FRIENDS'] != NULL): ?>
    Мои дружбаны:
    <ul>        
    <?php $_from = $this->_tpl_vars['FRIENDS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['rec']):
?>         <li><a href="/user/view/<?php echo $this->_tpl_vars['rec']->id; ?>
"><strong><?php echo $this->_tpl_vars['rec']->name; ?>
 <?php echo $this->_tpl_vars['rec']->secondName; ?>
</strong></a> <a href="delete/<?php echo $this->_tpl_vars['rec']->id; ?>
/"><span style="color: red;">X</a></span></li>
    <?php endforeach; endif; unset($_from); ?>  
    </ul>
<?php else: ?>
    <u>У тя друганов нету</u>
<?php endif; ?>
<hr>
<?php if ($this->_tpl_vars['randomFRIENDS'] != NULL): ?>
    Случайные трое дружбана:
    <ul>        
    <?php $_from = $this->_tpl_vars['randomFRIENDS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['rec']):
?>         <li><a href="/user/view/<?php echo $this->_tpl_vars['rec']->id; ?>
"><strong><?php echo $this->_tpl_vars['rec']->name; ?>
 <?php echo $this->_tpl_vars['rec']->secondName; ?>
</strong></a> <a href="delete/<?php echo $this->_tpl_vars['rec']->id; ?>
/"><span style="color: red;">X</a></span></li>
    <?php endforeach; endif; unset($_from); ?>  
    </ul>
<?php endif; ?> 
<a href="/user/view/">На мою страницу</a>   