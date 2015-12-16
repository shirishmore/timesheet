myApp.controller('adminController', ['$scope', 'action', 'timeEntry',
    function($scope, action, timeEntry) {

        /*check if users are loaded*/
        if (action && action.users != undefined) {
            action.users.success(function(response) {
                console.log('all users', response);
                $scope.users = response;
            });
        }

        /*Variables*/
        angular.extend($scope, {
            backdateEntry: {}
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
                });
            }
        });
    }
]);
