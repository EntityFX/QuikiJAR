<?php /* Smarty version 2.6.26, created on 2010-10-18 22:25:41
         compiled from finder.users.tpl */ ?>
<?php if ($this->_tpl_vars['FINDERS'] != NULL): ?>
    Всего найдено: <?php echo $this->_tpl_vars['COUNT']; ?>
<br />
    <?php $_from = $this->_tpl_vars['PAGES']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['pg'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['pg']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['rec']):
        $this->_foreach['pg']['iteration']++;
?>         <?php if ($this->_tpl_vars['rec']['current'] != true): ?>
            <span style="background-color: #7d7; font-weight: bold; color: #fff;"><a href="<?php echo $this->_tpl_vars['links']['search']; ?>
<?php echo $this->_tpl_vars['rec']['page']; ?>
" style="color: #fff; text-decoration: none"><?php if ($this->_tpl_vars['rec']['from'] == "*"): ?>&#x2190;<?php elseif ($this->_tpl_vars['rec']['to'] == "*"): ?>&#x2192;<?php else: ?><?php echo $this->_tpl_vars['rec']['from']; ?>
...<?php echo $this->_tpl_vars['rec']['to']; ?>
<?php endif; ?></a></span>
        <?php else: ?>
            <span style="background-color: #8e0; font-weight: bold; color: #fff;"><a href="<?php echo $this->_tpl_vars['links']['search']; ?>
<?php echo $this->_tpl_vars['rec']['page']; ?>
" style="color: #fff; text-decoration: none"><?php echo $this->_tpl_vars['rec']['from']; ?>
...<?php echo $this->_tpl_vars['rec']['to']; ?>
</a></span>
        <?php endif; ?>
    <?php endforeach; endif; unset($_from); ?> 
    <ul>        
    <?php $_from = $this->_tpl_vars['FINDERS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['rec']):
?>         <div>
            <a href="/user/view/<?php echo $this->_tpl_vars['rec']['id']; ?>
/"><?php echo $this->_tpl_vars['rec']['name']; ?>
</a>
            <?php if ($this->_tpl_vars['rec']['online'] == true): ?>
                <span style="background-color: #fcc; color: #fff; font-weight: bold;">На сайте</span>
            <?php else: ?>
                <span style="background-color: #ccf; color: #fff; font-weight: bold;">Не на сайте</span>
            <?php endif; ?><br/>
            <?php if ($this->_tpl_vars['rec']['isFriend'] == true): ?>
                Твой друг
            <?php else: ?>
                Не твой друг, добавить?
            <?php endif; ?><br/>
        </div>
    <?php endforeach; endif; unset($_from); ?>  
    </ul>
<?php else: ?>
    <u>Поиск не дал результатов</u>
<?php endif; ?>