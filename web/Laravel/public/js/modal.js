function modal(type, title, message, okHandler, hideHandler){
    var $m = $("#modal-" + type);
    $m.find('.modal-body .title').text(title);
    $m.find('.modal-body .message').text(message);
    if(typeof okHandler === 'function'){
        var $okButton = getOkButton($m);
        $m.on('hide.bs.modal', function () {
            $okButton.off('click');
        });
        $okButton.on('click', okHandler);
    }
    $m.modal('show');

    if(typeof hideHandler === 'function') {
        $m.on('hide.bs.modal', hideHandler);
    }
}

function getOkButton($modal){
    return $modal.find('.modal-footer .ok');
}
