$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

// Add user to Database Attendance and return a 'promise'
function addAttendanceDB(user, method) {
    user.in = moment().format('YYYY-MM-DD HH:mm:ss');
    user.method = method;
    //console.log('Adding user:' + user.name + ' method:' + user.method);
    return new Promise(function (resolve, reject) {
        delete user._method; // ensure _method not set else throws a Laravel MethodNotAllowedHttpException error. Requires a POST request to store
        $.ajax({
            url: '/attendance',
            type: 'POST',
            data: user,
            success: function (result) {
                //console.log('DB added user:[' + result.eid + '] ' + user.name);
                resolve(result);
            },
            error: function (result) {
                alert("Failed check-in for " + user.name + '. Please refresh the page to resync attendance');
                //console.log('DB added user FAILED:[' + result.eid + '] ' + user.name);
                reject(false);
            }
        });
    });
}

// Delete user from Database Attendance and return a 'promise'
function delAttendanceDB(user) {
    //console.log('Deleting user:[' + user.eid + '] ' + user.name);
    return new Promise(function (resolve, reject) {
        user._method = 'delete';
        $.ajax({
            url: '/attendance/' + user.eid,
            type: 'POST',
            data: user,
            success: function (result) {
                delete user._method;
                user.in = null;
                //console.log('DB deleted user:[' + user.eid + '] ' + user.name);
                resolve(user);
            },
            error: function (result) {
                alert("Failed check-out " + user.name + '. Please refresh the page to resync attendance');
                //console.log('DB deleted user FAILED:[' + user.eid + '] ' + user.name);
                reject(false);
            }
        });
    });
}

// Update user in Database Attendance and return a 'promise'
function updateAttendanceDB(user) {
    return new Promise(function (resolve, reject) {
        user._method = 'patch';
        $.ajax({
            url: '/attendance/' + user.eid,
            type: 'POST',
            data: user,
            success: function (result) {
                delete user._method;
                //console.log('DB updated user:[' + user.eid + '] ' + user.name);
                resolve(task);
            },
            error: function (result) {
                alert("failed updating attendance " + user.name + '. Please refresh the page to resync attendance');
                //console.log('DB updated user FAILED:[' + user.eid + '] ' + user.name);
                reject(false);
            }
        });
    });
}
