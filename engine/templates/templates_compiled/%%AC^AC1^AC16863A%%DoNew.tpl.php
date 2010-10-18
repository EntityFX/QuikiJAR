<?php /* Smarty version 2.6.26, created on 2010-10-18 15:29:00
         compiled from notice/DoNew.tpl */ ?>
<strong>Создание объявления:</strong><br/>
<form name="msg" action="<?php echo $this->_tpl_vars['arr']; ?>
" method="post">
    Тема:<br/> 
        <input type="text" name="text"></br>    
    Текст объявления:<br/>
        <textarea cols="50" name="mes" rows="10"></textarea><br/>  
        <input type="submit">
</form>