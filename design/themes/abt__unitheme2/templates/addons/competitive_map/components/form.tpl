{if $auth.user_id}
    <div title="{__("competitive_map_download")}" id="competitive_map_popup">
        <p>
            {__("competitive_map_popup_descr")}
        </p>
        <div class="buttons-container">
            <button class="cm-dialog-closer ty-btn ty-btn__primary">{__("cancel")}</button>
            <a class="ty-btn ty-btn__secondary ty-float-right" href="{"competitive_map.download?category_id=`$category_data.category_id`"|fn_url}">{__("download")}</a>
        </div>
    </div>
{else}
    {include file="views/auth/popup_login_form.tpl" title=__("authorize_before_order")}
{/if}