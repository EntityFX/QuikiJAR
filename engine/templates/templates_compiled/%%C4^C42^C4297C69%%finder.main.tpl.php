<?php /* Smarty version 2.6.26, created on 2010-10-18 22:07:44
         compiled from finder.main.tpl */ ?>
�� ������:
<form action="<?php echo $this->_tpl_vars['links']['search']; ?>
1/" method="post">
    <label>���: </label><input type="text" name="name" /><br />
    <label>�������: </label><input type="text" name="surname" /><br />    
    <label>���: </label>
    <select name="gender">
      <option value="-">�����</option>      
      <option value="0">�������</option>
      <option value="1">�������</option>
    </select><br />
    <label>�������: </label>��: <input type="text" name="ageFrom" />��: <input type="text" name="ageTo" /><br />
    <label>������ ������: </label><input type="checkbox" name="isOnline" /><br />                                                             
    <input type="submit" />
</form>
