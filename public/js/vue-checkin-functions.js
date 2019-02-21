$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

// Add person to Database Attendance and return a 'promise'
function addAttendanceDB(person, method) {
    person.in = moment().format('YYYY-MM-DD HH:mm:ss');
    person.method = method;
    //console.log('Adding person:' + person.name + ' method:' + person.method);
    return new Promise(function (resolve, reject) {
        delete person._method; // ensure _method not set else throws a Laravel MethodNotAllowedHttpException error. Requires a POST request to store
        $.ajax({
            url: '/attendance',
            type: 'POST',
            data: person,
            success: function (result) {
                //console.log('DB added person:[' + result.eid + '] ' + person.name);
                resolve(result);
            },
            error: function (result) {
                alert("Failed check-in for " + person.name + '. Please refresh the page to resync attendance');
                //console.log('DB added person FAILED:[' + result.eid + '] ' + person.name);
                reject(false);
            }
        });
    });
}

// Delete person from Database Attendance and return a 'promise'
function delAttendanceDB(person) {
    //console.log('Deleting person:[' + person.eid + '] ' + person.name);
    return new Promise(function (resolve, reject) {
        person._method = 'delete';
        $.ajax({
            url: '/attendance/' + person.eid,
            type: 'POST',
            data: person,
            success: function (result) {
                delete person._method;
                person.in = null;
                //console.log('DB deleted person:[' + person.eid + '] ' + person.name);
                resolve(person);
            },
            error: function (result) {
                alert("Failed check-out " + person.name + '. Please refresh the page to resync attendance');
                //console.log('DB deleted person FAILED:[' + person.eid + '] ' + person.name);
                reject(false);
            }
        });
    });
}

// Update person in Database Attendance and return a 'promise'
function updateAttendanceDB(person) {
    return new Promise(function (resolve, reject) {
        person._method = 'patch';
        $.ajax({
            url: '/attendance/' + person.eid,
            type: 'POST',
            data: person,
            success: function (result) {
                delete person._method;
                //console.log('DB updated person:[' + person.eid + '] ' + person.name);
                resolve(task);
            },
            error: function (result) {
                alert("failed updating attendance " + person.name + '. Please refresh the page to resync attendance');
                //console.log('DB updated person FAILED:[' + person.eid + '] ' + person.name);
                reject(false);
            }
        });
    });
}
