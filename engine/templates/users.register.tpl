<h1>Регистрация на сайте</h1>
<div class="reg_form">
    <form action="{$links.create}" method="post">
        <dl>
            <dd><label>Почта <span class="star">*</span></label></dd><dt><input type="text" name="mail"></dt>
            <dd><label>Имя <span class="star">*</span></label></dd><dt><input type="text" name="name"></dt>
            <dd><label>Фамилия <span class="star">*</span></label></dd><dt><input type="text" name="surname"></dt>
            <dd><label>Пол <span class="star">*</span></label></dd>
            <dt>
                <select name="gender">
                    <option value="1">Женский</option>
                    <option value="0">Мужской</option>
                </select>
            </dt> 
            <dd><label>Дата рождения <span class="star">*</span></label></dd><dt><input type="text" name="burthday" id="datepicker" readonly="readonly" /></dt>
            <dd>&nbsp;</dd><dt>&nbsp;</dt>
            <dd><label>Пароль <span class="star">*</span></label></dd><dt><input type="password" name="password1"></dt>
            <dd><label>Подтвердите пароль <span class="star">*</span></label></dd><dt><input type="password" name="password2"></dt>
            <dd>&nbsp;</dd><dt>&nbsp;</dt>  
            <dd><label>Страна <span class="star">*</span></label></dd>
            <dt>
                <select name="country" id="selCountry">
                    <option value="">Выберите страну</option>
                </select>
            </dt>
            <dd><label>Регион <span class="star">*</span></label></dd>
            <dt>
                <select name="region" id="selRegion" disabled="disabled">
                    <option value="">Выберите регион</option>
                </select>
            </dt>
            <dd><label>Город <span class="star">*</span></label></dd>
                <dt>
                <select name="city" id="selCity" disabled="disabled">
                    <option value="">Выберите город</option>
                </select>
            </dt>     
            <dd><label></label></dd><dt style="text-align: right;"><input type="submit" value="Зарегистрироваться" /></dt>
        </dl> 
    </form>
</div>