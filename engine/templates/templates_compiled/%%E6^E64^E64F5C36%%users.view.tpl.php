<?php /* Smarty version 2.6.26, created on 2010-10-04 22:48:15
         compiled from users.view.tpl */ ?>
������� �������:<?php echo $this->_tpl_vars['accLevel']; ?>
<br/>
<?php if ($this->_tpl_vars['user']->isOnline == true): ?>
<span style="background-color: #fcc; color: #fff; font-weight: bold;">�� �����</span>
<?php else: ?>
<span style="background-color: #ccf; color: #fff; font-weight: bold;">�� �� �����</span>
<?php endif; ?><br/>
<img src="<?php echo $this->_tpl_vars['photo']; ?>
" alt="<?php echo $this->_tpl_vars['user']->name; ?>
 <?php echo $this->_tpl_vars['user']->secondName; ?>
" /><hr/>
<h3>ID: <?php echo $this->_tpl_vars['user']->id; ?>
</h3>
<strong><?php echo $this->_tpl_vars['user']->name; ?>
 <?php echo $this->_tpl_vars['user']->secondName; ?>
</strong><br/>
<i><?php echo $this->_tpl_vars['user']->burthday; ?>
</i><br/>
<span style="color: blue">���:</span> <?php if ($this->_tpl_vars['user']->gender == 0): ?>�������<?php else: ?>�������<?php endif; ?><br/>
mail: <strong><?php echo $this->_tpl_vars['user']->mail; ?>
</strong><br/>
Registration IP: <strong><?php echo $this->_tpl_vars['user']->ip; ?>
</strong><br/>
<a href="/friends">��� ��������</a><br/>
<?php if ($this->_tpl_vars['info'] != NULL): ?>
��� ������������: 
    <table border="1">
        <thead>
            <tr><th>���������</th><th>����������</th></tr>
        </thead>
        <tbody>
            <?php $_from = $this->_tpl_vars['info']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['obj']):
?>                 <tr><td><?php echo $this->_tpl_vars['obj']->title; ?>
</td><td><?php echo $this->_tpl_vars['obj']->text; ?>
</td></tr>
            <?php endforeach; endif; unset($_from); ?> 
        </tbody>
    </table>
<?php else: ?>
� ��� ��� ������������. <a href="" >����������� ��������</a>.<br/>
<?php endif; ?>
<a href="<?php echo $this->_tpl_vars['links']['signOutPath']; ?>
">�����</a>