define([
    'jquery',

], function ($) {
    'use strict';

    return function (config, element) {
        $(element).click(function () {
            $.ajax({
                url: config.url,
                type: 'POST',
                data: {
                    form_key: window.FORM_KEY,
                    store_id: config.storeId,
                    webiste_id: config.webSiteId
                },
                success: function (data) {
                    // Create a hidden anchor tag with the download link
                    var link = document.createElement('a');
                    link.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(data));
                    link.setAttribute('download', 'Sitemap.csv');
                    link.style.display = 'none';
                    document.body.appendChild(link);
                    link.click();
                    // Remove the anchor tag from the document
                    document.body.removeChild(link);
                },
                error: function (xhr, status, error) {
                    // Handle AJAX errors
                }
            });
        });
    };
});
