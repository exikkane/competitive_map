{capture name="competitive_map_popup"}
    {include file="addons/competitive_map/components/form.tpl"}
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


<style>
    .ut2-cat-container .competitive_map_btn {
        margin-left: auto;
    }
</style>