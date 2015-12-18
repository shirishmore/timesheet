myApp.controller('dashboardController', ['$scope', 'timeEntry', '$parse',
    function($scope, timeEntry, $parse) {
        timeEntry.getTimeSheetEntryByDate().success(function(response) {
            $scope.timeEntryOverview = response;
        });

        angular.extend($scope, {
            graphLabels: {}
        });

        angular.extend($scope, {
            changeTag: function(url) {
                $scope.tabUrl = url;
            }
        });

        $scope.labels = ["Download Sales", "In-Store Sales", "Mail-Order Sales"];
        $scope.data = [300, 500, 100];
    }
]);
