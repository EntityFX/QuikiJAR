<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />   
		<title>{$TITLE}</title>
		<link rel="stylesheet" href="/css/style.css" type="text/css">  
		<link type="text/css" href="/css/qtheme/jquery-ui-1.8.5.custom.css" rel="stylesheet" />    
		<script type="text/javascript" src="/engine/js/jquery-1.4.2.min.js"></script>
		<script type="text/javascript" src="/engine/js/jquery-ui-1.8.5.custom.min.js"></script>
		<script type="text/javascript" src="/engine/js/jquery.ui.datepicker-ru.js"></script>
		{literal}
		<script type="text/javascript">
		
		/**
		* Получить дамп переменной в формате JSON
		*
		* @param obj Object Переменная
		* @return String
		*/
		function JSONdump(obj)
		{
			return dump(obj,0,null);
			
			function dump(toObj, shift,elName)
			{
				var res="";
				var tab="    ";
				var tabs="";
				for(var i=1;i<=shift;i++)
				{
					tabs+=tab;
				}         
				switch (typeof toObj)
				{
					case "object":
						if (elName)
						{
							res+=tabs+"\""+elName+"\": \n";
						}
						res+=tabs+"{\n"; 
						shift++;
						var pos=1;
						var count=0;
						for(var q in toObj)
						{
							count++;    
						}
						for (var el in toObj)    
						{
							res+=dump(toObj[el],shift,el);
							if (pos==count)
							{
								res+="\n";    
							}
							else
							{
								res+=",\n"
							}
							pos++;
						}
						res+=tabs+"}";
						break;
					case "number":
						res+=tabs+"\""+elName+"\": "+toObj;
						break;
					case "string":
						res+=tabs+"\""+elName+"\": \""+toObj+"\"";
						break;
				}
				return res;
			}
		}
			
			var x=0;
			$(function() 
			{
				$.datepicker.setDefaults($.datepicker.regional["ru"]);
				$( "#datepicker" ).datepicker(
					{ 
						dateFormat: 'yy-mm-dd',
						changeMonth: true,
						changeYear: true
					});
				$("#findTabs").tabs();
				$("input:submit, .jAnc").button();

				
				function fillSelect(elementId,data)
				{
					$(elementId).empty();
					$(elementId).append("<option value=empty>Не выбрано</option>"); 
					$.each(data,
					function(n,val)
					{ 
						$(elementId).append("<option value="+val.id+">"+val.text+"</option>");
					});
				}
				
				//Получить данные о странах
				$("#selCountry").click( function(){  
					$.getJSON("/ajax/country/country.php?location=country",
					function(data){
						fillSelect("#selCountry",data);
						$("#selCountry").unbind("click");
						$("#selRegion").removeAttr("disabled");
					});
					
				});
				
				//При смене страны загрузить регионы
				$("#selCountry").change(function(){
					$countryId=$(this).val();
					$.getJSON("/ajax/country/country.php?location=region&countryid="+$countryId,
					function(data)	{
						fillSelect("#selRegion",data);
						fillSelect("#selCity",null);
					});
				});
				
				//При смене региона загрузить города
				$("#selRegion").change(function(){
					$regionId=$(this).val();
					$.getJSON("/ajax/country/country.php?location=city&regionid="+$regionId,
					function(data)    {
						fillSelect("#selCity",data);
					});
					$("#selCity").removeAttr("disabled");
				})                
			});
		</script>
		{/literal}
	</head>                                                   
	<body>
		Главная менюшка: <br />
		{if $MENU != NULL}
			<ul>
			{foreach from=$MENU item=rec name=menu}
				<li><a href="{$rec.href}">{$rec.title}</a></li>
			{/foreach}
			</ul>
		{/if}
		Подразделы раздела: <br />
		{if $SUB_SECTIONS != NULL}
			<ul>        
			{foreach from=$SUB_SECTIONS item=rec} {* Выводит подразделы*}
				<li><a href="{$rec.link}">{$rec.title}</a></li>
			{/foreach}  
			</ul>
		{/if}
		<hr />Список от главной страницы: 
		{foreach from=$PATH item=rec name=path}  {* Выводит путь *}
			<a href="{$rec.link}">{$rec.title}</a>
			{if !$smarty.foreach.path.last} :: {/if}
		{/foreach}
		<hr />
		{$TEXT_VAR}  {*Выводимый текст*}  
	</body>
</html>