{has_page_images}
<h2>{lang_Imagegallery}</h2>
<ul data-target="#modal-gallery" data-toggle="modal-gallery" class="files files-list ui-sortable content-images">  
    {page_images}
    <li class="template-download fade in">
        <a data-gallery="gallery" href="{url}" title="{description}" download="{url}" class="preview show-icon">
            <img src="assets/img/preview-icon.png" class="" alt="{alt}"/>
        </a>
        <div class="preview-img"><img src="{thumbnail_url}" data-src="{url}" alt="{alt}" class="" /></div>
    </li>
    {/page_images}
</ul>

<br style="clear: both;" />
{/has_page_images}