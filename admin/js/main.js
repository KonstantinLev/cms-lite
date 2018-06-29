var defObj = {
    //dataType: 'json',
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

window.onbeforeunload = function () {
    loading('show');
};

function loading(action)
{
    if(action == 'show'){
        $('.lightbox').show();
    } else {
        $('.lightbox').hide();
    }

}

function saveMainTab()
{
    var form = $('#main-form');
    var url = form.prop('action');
    var tObj = Object.create(defObj);
    tObj.url = url;
    tObj.type = 'POST';
    tObj.data.action = 'main-tab';
    tObj.data.data = form.serialize();
    tObj.success = function (data) {
        console.log(data);
    },
    $.ajax(tObj);
}