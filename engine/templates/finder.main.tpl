<div id="findTabs" style="width: 500px;">
    <ul>
        <li><a href="#byName">�����������</a></li>
        <li><a href="#byMail">�� �����</a></li>
        <li><a href="#byId">�� ID</a></li>
    </ul>
    <div id="byName" class="reg_form" style="margin: 0 0;">
        <form action="{$links.search}1/?type=byData" method="post">
            <dl>
                <dd><label>���: </label></dd><dt><input type="text" name="name" /></dt>
                <dd><label>�������: </label></dd><dt><input type="text" name="surname" /></dt>    
                <dd><label>���: </label></dd><dt>
                <select name="gender">
                  <option value="-">�����</option>      
                  <option value="0">�������</option>
                  <option value="1">�������</option>
                </select></dt>
                <dd><label>�������: </label></dd><dt>��: <input type="text" name="ageFrom" size="2"/> ��: <input type="text" name="ageTo" size="2"/></dt>
                <dd><label>������ ������: </label></dd><dt><input type="checkbox" name="isOnline" /></dt>                                                             
                <dd>&nbsp;</dd><dt><input type="submit" /></dt>
            </dl>
        </form>
    </div>
    <div id="byMail">
        <form action="{$links.search}1/?type=byMail" method="post">
            <dl>
                <dd><label>���: </label></dd><dt><input type="text" name="mail" /></dt>
                <dd>&nbsp;</dd><dt><input type="submit" name="type" /></dt>
            </dl>
        </form>
    </div>
    <div id="byId">
        <form action="{$links.search}1/?type=byId" method="post">
            <dl>
                <dd><label>���: </label></dd><dt><input type="text" name="id" /></dt>
                <dd>&nbsp;</dd><dt><input type="submit" name="type" /></dt>
            </dl>
        </form>
    </div>
</div>