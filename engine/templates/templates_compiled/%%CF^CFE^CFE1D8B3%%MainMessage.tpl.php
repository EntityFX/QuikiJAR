<?php /* Smarty version 2.6.26, created on 2010-10-18 15:22:40
         compiled from message/MainMessage.tpl */ ?>
<strong>Действие:</strong><br/>
<form name="msg" action="<?php echo $this->_tpl_vars['arr']; ?>
" method="post">
    <a href="/message/DoSend/">Написать сообщение</a><br/>
    <a href="/message/GetNew/">Входящие сообщения</a><br/> 
    <a href="/message/GetSends/">Исходящие сообщения</a><br/> 
    <a href="/message/GetSaves/">Черновики</a><br/> 
    <a href="/message/GetAllMy/">Все мои сообщения</a><br/>   
</form>