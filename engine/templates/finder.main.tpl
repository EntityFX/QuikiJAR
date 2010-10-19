<div id="findTabs" style="width: 500px;">
    <ul>
        <li><a href="#byName">Расширенный</a></li>
        <li><a href="#byMail">По Почте</a></li>
        <li><a href="#byId">По ID</a></li>
    </ul>
    <div id="byName" class="reg_form" style="margin: 0 0;">
        <form action="{$links.search}1/?type=byData" method="post">
            <dl>
                <dd><label>Имя: </label></dd><dt><input type="text" name="name" /></dt>
                <dd><label>Фамилия: </label></dd><dt><input type="text" name="surname" /></dt>    
                <dd><label>Пол: </label></dd><dt>
                <select name="gender">
                  <option value="-">Любой</option>      
                  <option value="0">Мужской</option>
                  <option value="1">Женский</option>
                </select></dt>
                <dd><label>Возраст: </label></dd><dt>От: <input type="text" name="ageFrom" size="2"/> До: <input type="text" name="ageTo" size="2"/></dt>
                <dd><label>Только онлайн: </label></dd><dt><input type="checkbox" name="isOnline" /></dt>                                                             
                <dd>&nbsp;</dd><dt><input type="submit" /></dt>
            </dl>
        </form>
    </div>
    <div id="byMail">
        <form action="{$links.search}1/?type=byMail" method="post">
            <dl>
                <dd><label>Имя: </label></dd><dt><input type="text" name="mail" /></dt>
                <dd>&nbsp;</dd><dt><input type="submit" name="type" /></dt>
            </dl>
        </form>
    </div>
    <div id="byId">
        <form action="{$links.search}1/?type=byId" method="post">
            <dl>
                <dd><label>Имя: </label></dd><dt><input type="text" name="id" /></dt>
                <dd>&nbsp;</dd><dt><input type="submit" name="type" /></dt>
            </dl>
        </form>
    </div>
</div>