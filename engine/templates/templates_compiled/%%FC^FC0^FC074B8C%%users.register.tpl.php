<?php /* Smarty version 2.6.26, created on 2010-09-19 01:06:53
         compiled from users.register.tpl */ ?>
<html>
<head>
    <title></title>
    <link rel="stylesheet" href="/css/style.css" type="text/css">
</head>
<body>
    <h1>����������� �� �����</h1>
    <div class="reg_form">
        <form action="<?php echo $this->_tpl_vars['links']['create']; ?>
" method="post">
            <dl>
                <dd><label>����� <span class="star">*</span></label></dd><dt><input type="text" name="mail"></dt>
                <dd><label>��� <span class="star">*</span></label></dd><dt><input type="text" name="name"></dt>
                <dd><label>������� <span class="star">*</span></label></dd><dt><input type="text" name="surname"></dt>
                <dd><label>��� <span class="star">*</span></label></dd>
                <dt>
                    <select name="gender">
                        <option value="1">�������</option>
                        <option value="0">�������</option>
                    </select>
                </dt> 
                <dd><label>���� �������� <span class="star">*</span></label></dd><dt><input type="text" name="burthday"></dt><hr/>
                <dd><label>������ <span class="star">*</span></label></dd><dt><input type="password" name="password1"></dt>
                <dd><label>����������� ������ <span class="star">*</span></label></dd><dt><input type="password" name="password2"></dt>
                <dd><label></label></dd><dt style="text-align: right;"><input type="submit" value="������������������" /></dt>
            </dl> 
        </form>
    </div>
</body>
</html>