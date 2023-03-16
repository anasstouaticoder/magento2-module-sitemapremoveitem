define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'mage/translate',
    'jquery/ui',
    'jquery/file-uploader'
], function ($, modal) {
    'use strict';

    return function (config, element) {
        let options = {
            buttons: [{
                text: $.mage.__('Import'),
                class: 'action-primary sitemap-import',
                click: function () {
                    let formData = new FormData();
                    formData.append('import_file', $('#import_file_sitemap')[0].files[0]);
                    formData.append('store_id', config.storeId);
                    formData.append('webiste_id', config.webSiteId);
                    formData.append('form_key', config.formKey);

                    $.ajax({
                        url: config.url,
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        enctype: 'multipart/form-data',
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            location.reload();
                        },
                        error: function (xhr, status, error) {
                            location.reload();
                        }
                    });
                }
                },
                {
                    text: $.mage.__('Cancel'),
                    class: 'action-secondary action-dismiss',
                    click: function () {
                        this.closeModal();
                    }
                }
            ],
            innerScroll: true,
            responsive: true,
            title: $.mage.__('Import CSV'),
            type: 'popup'
        };
        $('#import_file_sitemap').fileupload({
            dataType: 'csv',
            add: function (e, data) {
                var fileName = data.files[0].name;
                $('#import_file_name').text(fileName);
            }
        });

        $(element).click(function () {
            if (document.getElementById('import_file_sitemap') === null) {
                let modalContent = '<p>' + _('Select a CSV file to import.') + '</p>' +
                    '<input type="file" name="import_file" id="import_file_sitemap"/>' +
                    '<span id="import_file_name"></span>';
                config.content += modalContent;
            }

            let popup = $('<div/>').html(config.content).modal(options);
            popup.modal('openModal');
        });
    };
});
