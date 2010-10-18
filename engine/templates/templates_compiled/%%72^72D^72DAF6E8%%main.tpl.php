<?php /* Smarty version 2.6.26, created on 2010-10-18 21:35:34
         compiled from main.tpl */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />   
        <title><?php echo $this->_tpl_vars['TITLE']; ?>
</title>
        <link rel="stylesheet" href="/css/style.css" type="text/css">  
        <link type="text/css" href="/css/qtheme/jquery-ui-1.8.5.custom.css" rel="stylesheet" />    
        <script type="text/javascript" src="/engine/js/jquery-1.4.2.min.js"></script>
        <script type="text/javascript" src="/engine/js/jquery-ui-1.8.5.custom.min.js"></script>
        <script type="text/javascript" src="/engine/js/jquery.ui.datepicker-ru.js"></script>
        <?php echo '
        <script type="text/javascript">
            $(function() 
            {
                $.datepicker.setDefaults($.datepicker.regional["ru"]);
                $( "#datepicker" ).datepicker(
                    { 
                        dateFormat: \'yy-mm-dd\',
                        changeMonth: true,
                        changeYear: true
                    });
            });
        </script>
        '; ?>

    </head>                                                   
    <body>
    <div id="slider"></div>
        Главная менюшка: <br />
        <?php if ($this->_tpl_vars['MENU'] != NULL): ?>
            <ul>
            <?php $_from = $this->_tpl_vars['MENU']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['menu'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['menu']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['rec']):
        $this->_foreach['menu']['iteration']++;
?>
                <li><a href="<?php echo $this->_tpl_vars['rec']['href']; ?>
"><?php echo $this->_tpl_vars['rec']['title']; ?>
</a></li>
            <?php endforeach; endif; unset($_from); ?>
            </ul>
        <?php endif; ?>
        Подразделы раздела: <br />
        <?php if ($this->_tpl_vars['SUB_SECTIONS'] != NULL): ?>
            <ul>        
            <?php $_from = $this->_tpl_vars['SUB_SECTIONS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['rec']):
?>                 <li><a href="<?php echo $this->_tpl_vars['rec']['link']; ?>
"><?php echo $this->_tpl_vars['rec']['title']; ?>
</a></li>
            <?php endforeach; endif; unset($_from); ?>  
            </ul>
        <?php endif; ?>
        <hr />Список от главной страницы: 
        <?php $_from = $this->_tpl_vars['PATH']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['path'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['path']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['rec']):
        $this->_foreach['path']['iteration']++;
?>              <a href="<?php echo $this->_tpl_vars['rec']['link']; ?>
"><?php echo $this->_tpl_vars['rec']['title']; ?>
</a>
            <?php if (! ($this->_foreach['path']['iteration'] == $this->_foreach['path']['total'])): ?> :: <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?>
        <hr />
        <?php echo $this->_tpl_vars['TEXT_VAR']; ?>
    
    </body>
</html>