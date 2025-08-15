{if $auth.user_id}
    <div title="{__("competitive_map_download")}" id="competitive_map_popup">
        <p>
            {__("competitive_map_popup_descr")}
        </p>

        <form method="post" action="{"competitive_map.download"|fn_url}">
            {foreach from=$products key=pid item=product}
                <input type="hidden" name="product_ids[]" value="{$pid}">
            {/foreach}

            <div class="buttons-container">
                <button class="cm-dialog-closer ty-btn ty-btn__primary">{__("cancel")}</button>
                <button type="submit" class="ty-btn ty-btn__secondary ty-float-right">
                    {__("download")}
                </button>
            </div>
        </form>
    </div>
{else}
    {include file="views/auth/popup_login_form.tpl" title=__("authorize_before_order")}
{/if}