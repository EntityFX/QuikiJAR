<?php /* Smarty version 2.6.26, created on 2010-10-18 15:26:25
         compiled from message/GetSaves.tpl */ ?>
<strong>Черновики:</strong><br/>
<?php $_from = $this->_tpl_vars['arr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['rec']):
?>    
    <h3><?php echo $this->_tpl_vars['rec']['Message']; ?>
</h3><font size=2>дата и время <?php echo $this->_tpl_vars['rec']['DateTime']; ?>
</font>
<?php endforeach; endif; unset($_from); ?>