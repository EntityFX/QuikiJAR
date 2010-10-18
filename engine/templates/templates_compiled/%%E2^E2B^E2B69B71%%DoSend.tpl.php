<?php /* Smarty version 2.6.26, created on 2010-10-18 15:25:45
         compiled from message/DoSend.tpl */ ?>
<strong>Отправить сообщение:</strong><br/>
<form name="msg" action="<?php echo $this->_tpl_vars['arr']; ?>
" method="post">
    Кому:<br/> 
        <select id="Select1" name="sel">
        <?php $_from = $this->_tpl_vars['arr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['rec']):
?>    
            <option value=$count><?php echo $this->_tpl_vars['rec']['UserID']; ?>
</option> 
        <?php endforeach; endif; unset($_from); ?>
        </select><br/>     
    Текст сообщения:<br/>
        <textarea cols="50" name="mes" rows="10"></textarea><br/> 
    Смайлики:<br/>
        <textarea cols="50" readonly="readonly" name="smiles" rows="4">=)   =*)   =(   ;D</textarea><br/> 
        <input type="submit">
</form>