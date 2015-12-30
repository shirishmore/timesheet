myApp.controller('adminController', ['$scope', 'action', 'timeEntry','$routeParams', '$location', 'snackbar',
    function($scope, action, timeEntry,$routeParams ,$location, snackbar) {

        /*check if users are loaded*/
        if (action && action.users != undefined) {
            action.users.success(function(response) {
                console.log('all users', response);
                $scope.users = response;
            });
        }

        if (action && action.allEntries != undefined) {
            window.document.title = 'Backdate entry';

            action.allEntries.success(function(response) {
                if (response.length != 0) {
                    console.log('all Entries', response.length);
                    $scope.allEntries = response;
                    $scope.showEntries = true;
                }
            });
        }

        if (action && action.singleBackdateEntry != undefined) {

            action.singleBackdateEntry.success(function(response) {
                if (response.length != 0) {
                    console.log('Single Backdate Entry ', response.length);
                    $scope.singleBackdateEntry = response;
                }
            });
        }

        /*Variables*/
        angular.extend($scope, {
            backdateEntry: {},
            allEntries: {},
            singleBackdateEntry: {},
            showEntries: false
        });

        /*Methods*/
        angular.extend($scope, {
            backdateEntrySubmit: function(backdateEntryForm) {
                if (backdateEntryForm.$valid) {
                    /*get all the user ids*/
                    var userIds = [];
                    if ($scope.backdateEntry != undefined) {
                        angular.forEach($scope.backdateEntry.users, function(value, key) {
                            userIds.push(value.id);
                        });
                    }

                    /*create the post data*/
                    var entryData = {
                        date: $scope.backdateEntry.backdate,
                        users: userIds,
                        comment: $scope.backdateEntry.reason
                    };

                    timeEntry.saveBackDateEntry(entryData).success(function(response) {
                        console.log('backdate entries', response);
                        $scope.allEntries = response;
                        $scope.backdateEntry = {};
                        $scope.showEntries = true;
                        snackbar.create("Entry added and mail sent.", 1000);
                    });
                }
            },
            deleteBackDate: function() {
                var r = confirm("This will delete the backdate entry . Ok?");
                if (r === true) {
                    timeFactory.deleteBackDate($routeParams.id).success(function(response) {
                        $location.path('/manage/back-date-entry');
                        snackbar.create("Backdate deleted", 1000);
                    });
                }
            }
        });
    }
]);
