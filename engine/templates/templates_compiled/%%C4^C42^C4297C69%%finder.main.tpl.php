<?php /* Smarty version 2.6.26, created on 2010-10-18 22:07:44
         compiled from finder.main.tpl */ ?>
По данным:
<form action="<?php echo $this->_tpl_vars['links']['search']; ?>
1/" method="post">
    <label>Имя: </label><input type="text" name="name" /><br />
    <label>Фамилия: </label><input type="text" name="surname" /><br />    
    <label>Пол: </label>
    <select name="gender">
      <option value="-">Любой</option>      
      <option value="0">Мужской</option>
      <option value="1">Женский</option>
    </select><br />
    <label>Возраст: </label>От: <input type="text" name="ageFrom" />До: <input type="text" name="ageTo" /><br />
    <label>Только онлайн: </label><input type="checkbox" name="isOnline" /><br />                                                             
    <input type="submit" />
</form>
