{literal}
    <script type="text/javascript" src="/engine/js/jquery.form.js"></script>    
    
    <script type="text/javascript">
        // ожидаем загрузки всего документа
        $(document).ready(function() {
            $('#addForm').ajaxForm(function(data) {
				$('#added').html(data);
            });
        });
            function loadTask(text)
            {
            	$.post("/crm/check/",{d: text},function(data)
                {
					$('#'+text).html(data);
				});
            }

		function deleteItem(idNum)
		{
			//alert(idNum);
			
			//$('#'+idNum).remove();
			$.post("/crm/check/",{d: 'del', i: idNum},function(data)
					{
						//$('#'+idNum).html(data);
						$('#'+idNum).css('background-color', '#ffaaaa');
					});	
		}
        
        </script>
{/literal}
<div id="findTabs" style="width: 1000px;">
    <ul>
        <li><a href="#today" onClick="loadTask('today');">Сегодня</a></li>
        <li><a href="#late" onClick="loadTask('late');">Ранее</a></li>
        <li><a href="#future" onClick="loadTask('future');">В будущем</a></li>
        <li><a href="#all" onClick="loadTask('all');">Все записи</a></li>
        <li><a href="#add">Добавить</a></li>
    </ul>
    <div id="today" style="margin: 0 0;">
		{$todayForm}
    </div>
    <div id="late">
		{$lateForm}
    </div>
    <div id="future">
		{$futureForm}       
    </div>
    <div id="add">
    {$addForm}
    </div>
    <div id="all">
    {$allForm}
    </div>
</div>

		