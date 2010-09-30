<?php /* Smarty version 2.6.26, created on 2010-09-28 22:36:06
         compiled from friends.groups.show.tpl */ ?>
<a href="/friends/groups/">К списку групп</a><br/>
Название группы: <b><?php echo $this->_tpl_vars['GROUP']->title; ?>
</b><br/>
    <?php if ($this->_tpl_vars['GROUP_FRIENDS'] != NULL): ?>
        <ul>      
        <?php $_from = $this->_tpl_vars['GROUP_FRIENDS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['rec']):
?>             <li><?php echo $this->_tpl_vars['rec']->name; ?>
 <?php echo $this->_tpl_vars['rec']->secondName; ?>
<a href="/friends/groups/deletefriend/<?php echo $this->_tpl_vars['GROUP']->id; ?>
/<?php echo $this->_tpl_vars['rec']->id; ?>
/"><span style="color: red;">X</span></a></li>     
        <?php endforeach; endif; unset($_from); ?>  
        </ul>
    <?php endif; ?>
Добавить друга в группу:  
<form action="../../addfriend/<?php echo $this->_tpl_vars['GROUP']->id; ?>
/" method="post">
    Выберите друга: 
    <?php if ($this->_tpl_vars['FRIENDS'] != NULL): ?>
        <select name="friend_ID">        
        <?php $_from = $this->_tpl_vars['FRIENDS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['rec']):
?>             <option value="<?php echo $this->_tpl_vars['rec']->id; ?>
"><?php echo $this->_tpl_vars['rec']->name; ?>
 <?php echo $this->_tpl_vars['rec']->secondName; ?>
</option>     
        <?php endforeach; endif; unset($_from); ?>  
        </select>
    <?php endif; ?>
    <input type="submit" value="Добавить">
</form>