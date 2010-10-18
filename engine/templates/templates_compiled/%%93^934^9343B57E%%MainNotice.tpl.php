<?php /* Smarty version 2.6.26, created on 2010-10-18 15:28:53
         compiled from notice/MainNotice.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'counter', 'notice/MainNotice.tpl', 2, false),)), $this); ?>
<strong>Действие:</strong><br/>
<?php echo smarty_function_counter(array('start' => 'cnt','skip' => 'fffqwerrty'), $this);?>

<form name="ntc" action="<?php echo $this->_tpl_vars['txt']; ?>
" method="post">
    <a href="/notice/DoNew/">Создать новое объявление</a><br/>
    <a href="/notice/GetAllMy/">Мои<?php echo smarty_function_counter(array(), $this);?>
 объявления</a><br/>
    <hr color=green>
    <strong>Действие (для администраторов):</strong><br/>  
    <a href="/notice/DoNewADM/">Создать<?php echo smarty_function_counter(array(), $this);?>
 новую рубрику</a><br/>
    <a href="/notice/GetAllADM/">Все рубрики</a><br/>  
</form>