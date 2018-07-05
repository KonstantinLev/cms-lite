var defObj = {
    dataType: 'json',
    data: {},
    beforeSend: function(){
        loading('show');
    },
    complete: function(){
        loading('hide');
    },
    success: function (data) {
        console.log(data);
    },
    error: function (e, str, t) {
        console.log(e);
        console.log(str);
        console.log(t);
        alert(e.responseText);
    }
};

$.fn.serializeObject = function () {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function () {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

window.onbeforeunload = function () {
    loading('show');
};

$('.cl-tab-nav a').click(function(e){
    e.preventDefault();
    $(this).tab('show');
    window.location.hash = $(this).attr('href');
});

var hash = window.location.hash;
$('.cl-tab-nav a[href="' + hash + '"]').tab('show');

/**
 * Toggle loader
 * @param action
 */
function loading(action)
{
    if(action === 'show'){
        $('.lightbox').show();
    } else {
        $('.lightbox').hide();
    }

}

/**
 * Save meta-tag's form
 */
function saveTags(obj)
{
    var form = $(obj).closest('form');
    var action = $(obj).data('action');
    var type = $(obj).data('type') || null;
    var url = form.prop('action');
    var tObj = Object.create(defObj);
    var data = form.serializeObject();
    form.find('div.row').each(function(key,val){
        var fields = {};
        var name = $(val).find('input[name="name"]');
        var value = $(val).find('input[name="value"]');
        fields['name'] = name.val();
        fields['value'] = value.val();
        data[key] = fields;
    });
    data.type = type;
    delete data.name;
    delete data.value;
    tObj.url = url;
    tObj.type = 'POST';
    tObj.data.action = action;
    tObj.data.data = data;
    tObj.success = function (data) {
        note({
            content: data.message,
            type: data.type,
            time: 4
        });
    },
    $.ajax(tObj);
}

/**
 *
 * @param obj
 */
function saveData(obj)
{
    var form = $(obj).closest('form');
    var action = $(obj).data('action');
    var type = $(obj).data('type') || null;
    var url = form.prop('action');
    var tObj = Object.create(defObj);
    var data = form.serializeObject();
    form.find('.cl-field').each(function(key,val){
        var fields = {};
        fields['name'] = $(val).attr('name');
        fields['value'] = $(val).val();
        data[key] = fields;
    });
    data.type = type;
    tObj.url = url;
    tObj.type = 'POST';
    tObj.data.action = action;
    tObj.data.data = data;

    tObj.success = function (data) {
        note({
            content: data.message,
            type: data.type,
            time: 4
        });
    },
    $.ajax(tObj);
}

/**
 * Add new meta-block
 */
function addMetaBlock(obj, type)
{
    var placeholderName = type === 'meta_tags' ? 'example: charset' : 'example: og:title';
    var placeholderValue = type === 'meta_tags' ? 'example: UTF-8' : 'example: The Rock';
    var fields = '<div class="block-meta-item">\n' +
        '                                        <div class="row">\n' +
        '                                            <input type="hidden" name="type" value="' + type + '">\n' +
        '                                            <div class="col-md-5">\n' +
        '                                                <div class="cl-form-group">\n' +
        '                                                    <input type="text" placeholder="' + placeholderName + '" class="cl-input" name="name">\n' +
        '                                                </div>\n' +
        '                                            </div>\n' +
        '                                            <div class="col-md-5">\n' +
        '                                                <div class="cl-form-group">\n' +
        '                                                    <input type="text" placeholder="' + placeholderValue + '" class="cl-input" name="value">\n' +
        '                                                </div>\n' +
        '                                            </div>\n' +
        '                                            <div class="col-md-2">\n' +
        '                                                <a href="#" class="meta-times" onclick="removeMetaBlock(this);"><i class="fal fa-times-circle"></i></a>\n' +
        '                                            </div>\n' +
        '                                        </div>\n' +
        '                                    </div>';
    $(obj).closest('.row').prev().append(fields);
}

/**
 * Remove meta block
 * @param obj
 */
function removeMetaBlock(obj)
{
    if($(obj).closest('.block-meta').find('.block-meta-item').length > 1) {
        $(obj).closest('.block-meta-item').remove();
    } else {
        $(obj).closest('.block-meta-item').find('input').val('');
    }
}

/**
 * Show prepare meta-tags
 */
$('.launch-demo-meta-tag').click(function () {
    var form = $(this).parent('form');
    var type = form.find('input[name="type"]').val();
    var result = '';
    form.find('.block-meta-item .row').each(function(key,val){
        var name = $(val).find('input[name="name"]').val();
        var value = $(val).find('input[name="value"]').val();
        if(name === '' || value === '') return true;
        switch (name){
            case 'charset':
                result += "<meta charset=\"" + value +"\">\n";
                break;
            default:
                if(type == 'meta_tags'){
                    result += "<meta name=\"" + name +"\" content=\"" + value +"\">\n";
                } else {
                    result += "<meta property=\"" + name +"\" content=\"" + value +"\">\n";
                }
        }
    });
    $('#modal-demo-meta-tags').find('.modal-body pre code').text(result);
    $('#modal-demo-meta-tags').modal('show');
});

/**
 *
 */
$('.cancel-change').click(function () {
    var form = $(this).parent('form');
    var type = form.find('input[name="type"]').val();
    var tObj = Object.create(defObj);
    tObj.url = form.prop('action');
    tObj.type = 'POST';
    tObj.dataType = 'html';
    tObj.data.action = 'render-meta-blocks';
    tObj.data.type = type;
    tObj.success = function (data) {
        form.find('.block-meta').html(data);
    },
    $.ajax(tObj);
});