<?php /* Smarty version 2.6.26, created on 2010-09-24 21:15:11
         compiled from friends.all.tpl */ ?>
<html>
<head>
<title></title>
</head>
<body>
    <?php if ($this->_tpl_vars['FRIENDS'] != NULL): ?>
        ��� ��������:
        <ul>        
        <?php $_from = $this->_tpl_vars['FRIENDS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['rec']):
?>             <li><a href="/user/view/<?php echo $this->_tpl_vars['rec']->id; ?>
"><strong><?php echo $this->_tpl_vars['rec']->name; ?>
 <?php echo $this->_tpl_vars['rec']->secondName; ?>
</strong></a> <a href="delete/<?php echo $this->_tpl_vars['rec']->id; ?>
/"><span style="color: red;">X</a></span></li>
        <?php endforeach; endif; unset($_from); ?>  
        </ul>
    <?php else: ?>
        <u>� �� �������� ����</u>
    <?php endif; ?>
    <hr>
    <?php if ($this->_tpl_vars['randomFRIENDS'] != NULL): ?>
        ��������� ���� ��������:
        <ul>        
        <?php $_from = $this->_tpl_vars['randomFRIENDS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['rec']):
?>             <li><a href="/user/view/<?php echo $this->_tpl_vars['rec']->id; ?>
"><strong><?php echo $this->_tpl_vars['rec']->name; ?>
 <?php echo $this->_tpl_vars['rec']->secondName; ?>
</strong></a> <a href="delete/<?php echo $this->_tpl_vars['rec']->id; ?>
/"><span style="color: red;">X</a></span></li>
        <?php endforeach; endif; unset($_from); ?>  
        </ul>
    <?php endif; ?> 
    <a href="/user/view/">�� ��� ��������</a>   
</body>
</html>