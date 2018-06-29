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

/**
 * Toggle loader
 * @param action
 */
function loading(action)
{
    if(action == 'show'){
        $('.lightbox').show();
    } else {
        $('.lightbox').hide();
    }

}

/**
 * Save main form
 */
function saveMainTab()
{
    var form = $('#main-form');
    var url = form.prop('action');
    var tObj = Object.create(defObj);
    tObj.url = url;
    tObj.type = 'POST';
    tObj.data.action = 'main-tab';
    tObj.data.data = form.serializeArray();
    tObj.success = function (data) {
        var text = data.error || "Настройки успешно сохранены!";
        note({
            content: text,
            type: data.type,
            time: 4
        });
    },
    $.ajax(tObj);
}

/**
 * Save meta-tag's form
 */
function saveMetaTagTab()
{
    var form = $('#meta-tag-form');
    var url = form.prop('action');
    var tObj = Object.create(defObj);
    var data = form.serializeObject();
    form.find('div.row').each(function(key,val){
        var fields = {};
        var type = $(val).find('input[name="type"]');
        var name = $(val).find('input[name="name"]');
        var value = $(val).find('input[name="value"]');
        fields['type'] = type.val();
        fields['name'] = name.val();
        fields['value'] = value.val();
        data[key] = fields;
    });
    delete data.type;
    delete data.name;
    delete data.value;
    tObj.url = url;
    tObj.type = 'POST';
    tObj.data.action = 'meta-tag-tab';
    tObj.data.data = data;

    tObj.success = function (data) {
        var text = data.error || "Мета-теги успешно сохранены!";
        note({
            content: text,
            type: data.type,
            time: 4
        });
    },
    $.ajax(tObj);
}

/**
 * Save meta-tag's form
 */
function saveOGTagTab()
{
    var form = $('#og-tag-form');
    var url = form.prop('action');
    var tObj = Object.create(defObj);
    var data = form.serializeObject();
    form.find('div.row').each(function(key,val){
        var fields = {};
        var type = $(val).find('input[name="type"]');
        var name = $(val).find('input[name="name"]');
        var value = $(val).find('input[name="value"]');
        fields['type'] = type.val();
        fields['name'] = name.val();
        fields['value'] = value.val();
        data[key] = fields;
    });
    delete data.type;
    delete data.name;
    delete data.value;
    tObj.url = url;
    tObj.type = 'POST';
    tObj.data.action = 'og-tag-tab';
    tObj.data.data = data;

    tObj.success = function (data) {
        var text = data.error || "OG-теги успешно сохранены!";
        note({
            content: text,
            type: data.type,
            time: 4
        });
    },
        $.ajax(tObj);
}

/**
 * Add new meta-block
 */
function addMetaBlock(type)
{
    var fields = '<div class="block-meta-item">\n' +
        '                                        <div class="row">\n' +
        '                                            <input type="hidden" name="type" value="' + type + '">\n' +
        '                                            <div class="col-md-5">\n' +
        '                                                <div class="cl-form-group">\n' +
        '                                                    <input type="text" placeholder="example: charset" class="cl-input" name="name">\n' +
        '                                                </div>\n' +
        '                                            </div>\n' +
        '                                            <div class="col-md-5">\n' +
        '                                                <div class="cl-form-group">\n' +
        '                                                    <input type="text" placeholder="example: UTF-8" class="cl-input" name="value">\n' +
        '                                                </div>\n' +
        '                                            </div>\n' +
        '                                            <div class="col-md-2">\n' +
        '                                                <a href="#" class="meta-times" onclick="removeMetaBlock(this);"><i class="fal fa-times-circle"></i></a>\n' +
        '                                            </div>\n' +
        '                                        </div>\n' +
        '                                    </div>';
    $('.block-meta').append(fields);
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
    var form = $('#meta-tag-form');
    var data = form.serializeObject();
    form.find('div.row').each(function(key,val){
        var fields = {};
        var type = $(val).find('input[name="type"]');
        var name = $(val).find('input[name="name"]');
        var value = $(val).find('input[name="value"]');
        fields['type'] = type.val();
        fields['name'] = name.val();
        fields['value'] = value.val();
        data[key] = fields;
    });
    delete data.type;
    delete data.name;
    delete data.value;
    var url = form.prop('action');
    var tObj = Object.create(defObj);
    tObj.url = url;
    tObj.type = 'POST';
    tObj.dataType = 'text';
    tObj.data.action = 'demo-meta-tag';
    tObj.data.data = data;
    tObj.success = function (data) {
        console.log(data);
        $('#modal-demo-meta-tags').find('.modal-body').html(data);
        $('#modal-demo-meta-tags').modal('show');
    },
    $.ajax(tObj);
});

/**
 * Show prepare og-tags
 */
$('.launch-demo-og-tag').click(function () {
    var form = $('#og-tag-form');
    var data = form.serializeObject();
    form.find('div.row').each(function(key,val){
        var fields = {};
        var type = $(val).find('input[name="type"]');
        var name = $(val).find('input[name="name"]');
        var value = $(val).find('input[name="value"]');
        fields['type'] = type.val();
        fields['name'] = name.val();
        fields['value'] = value.val();
        data[key] = fields;
    });
    delete data.type;
    delete data.name;
    delete data.value;
    var url = form.prop('action');
    var tObj = Object.create(defObj);
    tObj.url = url;
    tObj.type = 'POST';
    tObj.dataType = 'text';
    tObj.data.action = 'demo-meta-tag';
    tObj.data.data = data;
    tObj.success = function (data) {
        console.log(data);
        $('#modal-demo-meta-tags').find('.modal-body').html(data);
        $('#modal-demo-meta-tags').modal('show');
    },
        $.ajax(tObj);
});