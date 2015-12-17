myApp.controller('adminController', ['$scope', 'action', 'timeEntry', 'snackbar',
    function($scope, action, timeEntry, snackbar) {

        /*check if users are loaded*/
        if (action && action.users != undefined) {
            action.users.success(function(response) {
                console.log('all users', response);
                $scope.users = response;
            });
        }

        if (action && action.allEntries != undefined) {
            action.allEntries.success(function(response) {
                console.log('all Entries', response);
                $scope.allEntries = response;
            });
        }

        /*Variables*/
        angular.extend($scope, {
            backdateEntry: {},
            allEntries: {}
        });

        /*Methods*/
        angular.extend($scope, {
            backdateEntrySubmit: function(backdateEntryForm) {
                /*get all the user ids*/
                var userIds = [];
                angular.forEach($scope.backdateEntry.users, function(value, key) {
                    userIds.push(value.id);
                });

                /*create the post data*/
                var entryData = {
                    date: $scope.backdateEntry.backdate,
                    users: userIds
                };

                timeEntry.saveBackDateEntry(entryData).success(function(response) {
                    console.log('response', response);
                    $scope.allEntries = response;
                    snackbar.create("Entry added and mail sent.", 1000);
                });
            }
        });
    }
]);
