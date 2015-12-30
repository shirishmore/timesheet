myApp.controller('userController', ['$scope','action', 'timeEntry', '$routeParams','$location', 'userFactory','snackbar', function($scope,  action, timeEntry,$routeParams,$location, userFactory,snackbar) {
    if ($location.$$path == '/logout') {
        userFactory.logoutUser().success(function(response) {
            console.log('logout', response);
            window.location = baseUrl;
        });
    }
    /*check if users are loaded*/
    if (action && action.users != undefined) {
        action.users.success(function(response) {
            console.log('all users', response);
            $scope.users = response;
        });
    }

    if (action && action.allEntries != undefined) {
        window.document.title = 'Request Backdate entry';

        action.allEntries.success(function(response) {
            if (response.length != 0) {
                console.log('all Entries', response.length);
                $scope.allEntries = response;
                $scope.showEntries = true;
            }
        });

    }

    if (action && action.singleRequestBackdateEntry != undefined) {

            action.singleRequestBackdateEntry.success(function(response) {
                if (response.length != 0) {
                    console.log('Single Request Backdate Entry ', response.length);
                    $scope.singleRequestBackdateEntry = response;
                }
            });
    }


        /*Variables*/
    angular.extend($scope, {
        requestBackdate: {},
        allEntries: {},
        singleRequestBackdateEntry: {},
        showEntries: false
    });

    /*Methods*/
    angular.extend($scope, {
        requestBackdateSubmit: function(requestBackdateForm) {
            if (requestBackdateForm.$valid) {
                /*get all the user ids*/
                var userIds = [];
                if ($scope.requestBackdate != undefined) {
                    angular.forEach($scope.requestBackdate.users, function(value, key) {
                        userIds.push(value.id);
                    });
                }

                /*create the post data*/
                var entryData = {
                    date: $scope.requestBackdate.backdate,
                    users: userIds,
                    comment: $scope.requestBackdate.reason
                };

                timeEntry.saveRequestBackDateEntry(entryData).success(function(response) {
                    console.log('backdate entries', response);
                    $scope.allEntries = response;
                    $scope.requestBackdate = {};
                    $scope.showEntries = true;
                    snackbar.create("Entry added and mail sent.", 1000);
                });
            }
        },
        deleteBackDateRequest: function() {
            var r = confirm("This will delete the backdate request entry. Ok?");
            if (r === true) {
                timeEntry.deleteBackDateRequest($routeParams.id).success(function(response) {
                    $location.path('/user/request-backdate-entry');
                    snackbar.create("Requested backdate deleted", 1000);
                });
            }
        }

    });
    
}]);
