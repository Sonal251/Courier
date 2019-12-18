exports.submitForm = function (method, url, data) {
    var form = jQuery('<form>', {
        'action': url,
        'method': 'POST',
        'target': '_top',
        'style': 'display: none;'
    }).append(jQuery('<input>', {
        'name': '_method',
        'value': method
    })).append(jQuery('<input>', {
        'name': '_token',
        'value': $('meta[name="csrf-token"]').attr('content')
    }));
    for (var key in data) {
        form.append(jQuery('<input>', {
            'name': key,
            'value': data[key]
        }));
    }
    form.appendTo('body');
    form.submit();
};

exports.submitFormNoFollow = function (method, url, data, success) {
    if (success === undefined) {
        success = function () {
        };
    }
    data._method = method;
    data._token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: url,
        data: data,
        method: 'POST',
        success: success
    });
};

exports.destroy = function (route) {
    this.submitForm('DELETE', route, {});
};
