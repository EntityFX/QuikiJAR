<?php /* Smarty version 2.6.26, created on 2010-10-18 15:28:53
         compiled from notice/MainNotice.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'counter', 'notice/MainNotice.tpl', 2, false),)), $this); ?>
<strong>��������:</strong><br/>
<?php echo smarty_function_counter(array('start' => 'cnt','skip' => 'fffqwerrty'), $this);?>

<form name="ntc" action="<?php echo $this->_tpl_vars['txt']; ?>
" method="post">
    <a href="/notice/DoNew/">������� ����� ����������</a><br/>
    <a href="/notice/GetAllMy/">���<?php echo smarty_function_counter(array(), $this);?>
 ����������</a><br/>
    <hr color=green>
    <strong>�������� (��� ���������������):</strong><br/>  
    <a href="/notice/DoNewADM/">�������<?php echo smarty_function_counter(array(), $this);?>
 ����� �������</a><br/>
    <a href="/notice/GetAllADM/">��� �������</a><br/>  
</form>