{if $FINDERS != NULL}
    Всего найдено: {$COUNT}<br />
    {foreach name=pg from=$PAGES item=rec} {* Выводит подразделы*}
        {if $rec.current neq true }
            <span style="background-color: #7d7; font-weight: bold; color: #fff;"><a href="{$links.search}{$rec.page}" style="color: #fff; text-decoration: none">{if $rec.from eq "*"}&#x2190;{elseif $rec.to eq "*"}&#x2192;{else}{$rec.from}...{$rec.to}{/if}</a></span>
        {else}
            <span style="background-color: #8e0; font-weight: bold; color: #fff;"><a href="{$links.search}{$rec.page}" style="color: #fff; text-decoration: none">{$rec.from}...{$rec.to}</a></span>
        {/if}
    {/foreach} 
    <ul>        
    {foreach from=$FINDERS item=rec} {* Выводит подразделы*}
        <div>
            <a href="/user/view/{$rec.id}/">{$rec.name}</a>
            {if $rec.online eq true}
                <span style="background-color: #fcc; color: #fff; font-weight: bold;">На сайте</span>
            {else}
                <span style="background-color: #ccf; color: #fff; font-weight: bold;">Не на сайте</span>
            {/if}<br/>
            {if $rec.isFriend eq true}
                Твой друг
            {else}
                Не твой друг, добавить?
            {/if}<br/>
        </div>
    {/foreach}  
    </ul>
{else}
    <u>Поиск не дал результатов</u>
{/if}