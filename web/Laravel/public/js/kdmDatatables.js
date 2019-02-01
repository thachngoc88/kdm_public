/*
 Template Name: Color Admin - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.7
 Version: 2.1.0
 Author: Sean Ngu
 Website: http://www.seantheme.com/color-admin-v2.1/admin/html/
 */



var createDetailButton = function(page, id){
    return createTableButton("/" + page + "/detail/" + id, "詳細", "file-text-o");
};
var createModifyButton = function(page, id){
    return createTableButton("/" + page + "/edit/" + id, "編集", "pencil");
};
var createTableButton = function(url, text, icon){
    return "<a href=\"" + url + "\" class=\"btn btn-primary btn-sm command\"><i class=\"fa fa-" + icon + "\"></i> " + text + "</a>";
};
var createTableCheckbox = function(id){
    return "<input id='checkbox-" + id + "' type='checkbox' name='rowCheckbox' value='' />";
};
var createDeleteButton = function(page, id, token, name){
    var formId = "delete-form-" + id;
    return "<form id=\"" + formId + "\" method=\"post\" action=\"/" + page + "/delete/" + id + "\">" +
        "<input type=\"hidden\" name=\"_token\" value=\"" + token + "\">" +
        "<button onclick=\"confirmToDelete('" + formId + "','" + (name ? name : "") + "');\" type=\"button\"  class=\"btn btn-danger btn-sm btn-destroy command\"><i class=\"fa fa-remove\"></i> 削除</button>" +
        "</form>";
};
var confirmToDelete = function(formId, name){
    var message = name ? '本当に' + name + 'を削除しますか？' : '本当に削除しますか？';
    modal('important', '削除確認', message, function(e){
        $("#" + formId).submit();
    });
    return false;
};

var viewUploader = function(){
    $('#excel-dropzone-block').fadeToggle('fast');
};

var hideUploader = function(){
    $('#excel-dropzone-block').hide('fade', 'fast');
};

var buildUrl = function(dt, action) {
    var url = dt.ajax.url() || '';
    var params = dt.ajax.params();
    params.action = action;
    var pStr = $.param(params);
    return url + '?' + decodeURIComponent(pStr);
};

jQuery.fn.dataTable.ext.buttons.postExcel = {
    className: jQuery.fn.dataTable.ext.buttons.postExcel.className,

    text: jQuery.fn.dataTable.ext.buttons.postExcel.text,

    action: function (e, dt, button, config) {
        var url = dt.ajax.url() || window.location.href;
        var params = jQuery.fn.dataTable.ext.buttons._buildParams(dt, 'excel');

        url = jQuery.fn.dataTable.ext.buttons._reformatUrl(url);
        jQuery.fn.dataTable.ext.buttons._downloadFromUrl(url, params);
    }
};

jQuery.fn.dataTable.ext.buttons._reformatUrl = function(url){
    return url.replace(/^(.+?)\/*?$/, "$1");
};

jQuery.fn.dataTable.ext.buttons._buildParams = function (dt, action) {
    var params = dt.ajax.params();
    params.action = action;
    params._token = $('meta[name="csrf-token"]').attr('content');

    return params;
};

jQuery.fn.dataTable.ext.buttons._downloadFromUrl = function (url, params) {
    var postUrl = url + '/export';
    var xhr = new XMLHttpRequest();
    xhr.open('POST', postUrl, true);
    xhr.responseType = 'arraybuffer';
    xhr.onload = function () {
        if (this.status === 200) {
            var filename = "";
            var disposition = xhr.getResponseHeader('Content-Disposition');
            if (disposition && disposition.indexOf('attachment') !== -1) {
                var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                var matches = filenameRegex.exec(disposition);
                if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
            }
            var type = xhr.getResponseHeader('Content-Type');

            var blob = new Blob([this.response], {type: type});
            if (typeof window.navigator.msSaveBlob !== 'undefined') {
                // IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
                window.navigator.msSaveBlob(blob, filename);
            } else {
                var URL = window.URL || window.webkitURL;
                var downloadUrl = URL.createObjectURL(blob);

                if (filename) {
                    // use HTML5 a[download] attribute to specify filename
                    var a = document.createElement("a");
                    // safari doesn't support this yet
                    if (typeof a.download === 'undefined') {
                        window.location = downloadUrl;
                    } else {
                        a.href = downloadUrl;
                        a.download = filename;
                        document.body.appendChild(a);
                        a.click();
                    }
                } else {
                    window.location = downloadUrl;
                }

                setTimeout(function () {
                    URL.revokeObjectURL(downloadUrl);
                }, 100); // cleanup
            }
        }
    };
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.send($.param(params));
};