{literal}
    <script type="text/javascript" src="/engine/js/jquery.form.js"></script>    
    
    <script type="text/javascript">

        
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

        function addVideo(videoId, title)
        {
			$.post("/ajax/video/init.php", {type: "add",  vId : videoId, vT: title}, function(data)
					{
						$('#added'+videoId).html(data);
						$('#addVideo'+videoId).attr('disabled', true);
					});
        }

        function delVideo(vId,user)
        {
			$.post("/ajax/video/init.php", {type: "del",  id : vId, userId: user}, function(data)
					{
						$('#video'+vId).html(data);
						//$('#addVideo'+videoId).attr('disabled', true);
					});
        }
        </script>
{/literal}
<a href="/video/search/"> Поиск видео</a> <br />
<div style="float: left;">
<form action="/video/search/" method="post">
	<input value="" name="searchStr">
	<input type="submit" value="Поиск">
</form>
</div>
<div style="float: left;">
{$numerator}
</div>
<div style="float: left;">
{$searchResults}
</div>
<div style="float: left;">
{$numerator}
</div>		
