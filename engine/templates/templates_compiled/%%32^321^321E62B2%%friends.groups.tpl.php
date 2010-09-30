<?php /* Smarty version 2.6.26, created on 2010-09-28 22:23:23
         compiled from friends.groups.tpl */ ?>
<?php if ($this->_tpl_vars['GROUPS'] != NULL): ?>
    Мои группы:
    <table>       
    <?php $_from = $this->_tpl_vars['GROUPS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['rec']):
?>         <tr><td><a href="show/<?php echo $this->_tpl_vars['rec']->id; ?>
/"><span style="color: green;"><b><?php echo $this->_tpl_vars['rec']->title; ?>
</b></span></a></td><td><a href="del/<?php echo $this->_tpl_vars['rec']->id; ?>
/"><span style="color: red;">X</span></a></td></tr>
    <?php endforeach; endif; unset($_from); ?>  
    </table>
<?php else: ?>
    <u>У тя нет групп</u>
<?php endif; ?>
<form action="add/" method="post">
    Создать группу: <input type="text" name="group_name"><br/>
    <input type="submit" value="Создать группу">
</form>