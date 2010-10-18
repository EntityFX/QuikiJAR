По данным:
<form action="{$links.search}1/" method="post">
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

