//Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');

$.ajaxSetup({
    headers: {'X-CSRF-Token': $('meta[name=token]').attr('value')}
});

var host = window.location.hostname;
var dev = true;
if (host == 'go2youth.com')
    dev = false;

// Post data to url via POST method
function postAndRedirect(url, postData) {
    var postFormStr = "<form method='POST' action='" + url + "'>\n";

    for (var key in postData) {
        if (postData.hasOwnProperty(key))
            postFormStr += "<input type='hidden' name='" + key + "' value='" + postData[key] + "'></input>";
    }

    postFormStr += "</form>";
    var formElement = $(postFormStr);

    $('body').append(formElement);
    $(formElement).submit();
}

// Search through array of object with given 'key' and 'value'
function objectFindByKey(array, key, value) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][key] == value) {
            return array[i];
        }
    }
    return null;
}