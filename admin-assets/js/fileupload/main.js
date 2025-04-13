/*
 * jQuery File Upload Plugin JS Example 7.0
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

/*jslint nomen: true, unparam: true, regexp: true */
/*global $, window, document */

$(function () {
    'use strict';

    loadjQueryUpload();

});

function loadjQueryUpload()
{
    // Initialize the jQuery File Upload widget:
    $('form.fileupload').fileupload({
        autoUpload: true,
        // The maximum width of the preview images:
        previewMaxWidth: 160,
        // The maximum height of the preview images:
        previewMaxHeight: 120,
        dropZone: $('form.fileupload'),
        uploadTemplateId: null,
        downloadTemplateId: null,
        uploadTemplate: function (o) {
            var rows = $();
            $.each(o.files, function (index, file) {
                var row = $('<li class="img-rounded template-upload">' +
                    '<div class="preview"><span class="fade"></span></div>' +
                    '<div class="filename"><code>'+file.name+'</code></div>'+
                    '<div class="options-container">' +
                    '<span class="cancel"><button  class="btn btn-mini btn-warning"><i class="icon-ban-circle icon-white"></i></button></span></div>' +
                    (file.error ? '<div class="error"></div>' :
                            '<div class="progress">' +
                                '<div class="bar" style="width:0%;"></div></div></div>'
                    )+'</li>');
                row.find('.name').text(file.name);
                row.find('.size').text(o.formatFileSize(file.size));
                if (file.error) {
                    row.find('.error').text(
                        locale.fileupload.errors[file.error] || file.error
                    );
                }
                rows = rows.add(row);
            });
            return rows;
        },
        downloadTemplate: function (o) {
            var rows = $();
            $.each(o.files, function (index, file) {
                var row = $('<li class="img-rounded template-download fade">' +
                    '<div class="preview"><span class="fade"></span></div>' +
                    '<div class="filename"><code>'+file.short_name+'</code></div>'+
                    '<div class="options-container">' +
                    (file.zoom_enabled?
                        '<a data-gallery="gallery" class="btn btn-mini btn-success" download="'+file.name+'"><i class="icon-search icon-white"></i></a>'
                        : '<a target="_blank" class="btn btn-mini btn-success" download="'+file.name+'"><i class="icon-search icon-white"></i></a>') +
                    ' <span class="delete"><button class="btn btn-mini btn-danger" data-type="'+file.delete_type+'" data-url="'+file.delete_url+'"><i class="icon-trash icon-white"></i></button>' +
                    ' <input type="checkbox" value="1" name="delete"></span>' +
                    '</div>' +
                    (file.error ? '<div class="error"></div>' : '')+'</li>');

                if (file.error) {
                    row.find('.name').text(file.name);
                    row.find('.error').text(
                        file.error
                    );
                } else {
                    row.find('.name a').text(file.name);
                    if (file.thumbnail_url) {
                        row.find('.preview').html('<img class="img-rounded" alt="'+file.name+'" data-src="'+file.thumbnail_url+'" src="'+file.thumbnail_url+'">');  
                    }
                    row.find('a').prop('href', file.url);
                    row.find('a').prop('title', file.name);
                    row.find('.delete button')
                        .attr('data-type', file.delete_type)
                        .attr('data-url', file.delete_url);
                }
                rows = rows.add(row);
            });
            return rows;
        }
    });

    $("ul.files").each(function (i) {
        $(this).sortable({
            update: saveFilesOrder
        });
        $(this).disableSelection();
    });
    



}


