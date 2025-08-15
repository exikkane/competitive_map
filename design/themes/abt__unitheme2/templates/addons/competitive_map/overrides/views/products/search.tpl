<div id="products_search_{$block.block_id}">
    {capture name="competitive_map_popup"}
        {include file="addons/competitive_map/components/form_search.tpl"}
    {/capture}
    <div class="competitive_map_btn">
        {include file="common/popupbox.tpl"
        link_text="{__("competitive_map_download")}"
        title="{__("competitive_map_download")}"
        id="request_dialog_competitive_map_popup_{$category_data.category_id}"
        content=$smarty.capture.competitive_map_popup
        link_meta="ty-btn__primary ty-btn__big ty-btn ty-float-right"
        }
    </div>

    {assign var="products_search" value="Y"}
    {$is_selected_filters = $smarty.request.features_hash}

    {if $products}
        {assign var="title_extra" value="{__("products_found")}: `$search.total_items`"}
        {assign var="layouts" value=""|fn_get_products_views:false:0}

        {if $layouts.$selected_layout.template}
            {include file="`$layouts.$selected_layout.template`" columns=$settings.Appearance.columns_in_products_list show_qty=true}
        {/if}
    {else}
        {hook name="products:search_results_no_matching_found"}
        {if !$show_not_found_notification && $is_selected_filters}
            {include file="common/no_items.tpl"
            text_no_found=__("text_no_products_found")
            no_items_extended=true
            reset_url=$config.current_url|fn_query_remove:"features_hash"
            }
        {else}
            {include file="common/no_items.tpl"
            text_no_found=__("text_no_matching_products_found")
            }
        {/if}
        {/hook}
    {/if}

    <!--products_search_{$block.block_id}--></div>

{hook name="products:search_results_mainbox_title"}
{capture name="mainbox_title"}<span class="ty-mainbox-title__left">{__("search_results")}</span><span class="ty-mainbox-title__right" id="products_search_total_found_{$block.block_id}">{$title_extra nofilter}<!--products_search_total_found_{$block.block_id}--></span>{/capture}
{/hook}
