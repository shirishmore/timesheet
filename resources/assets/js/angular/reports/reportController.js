myApp.controller('reportController', ['$scope', 'timeEntry', '$timeout', 'projectFactory', 'userFactory',
    function($scope, timeEntry, $timeout, projectFactory, userFactory) {

        timeEntry.getEntries().then(function(response) {
                console.log('time entries', response.data);
                $scope.timeEntries = response.data;
                angular.forEach(response.data, function(value, key) {
                    $scope.totalTime = $scope.totalTime + value.time;
                });
                return response;
            })
            .then(function() {
                userFactory.getUserList().then(function(response) {
                    console.log('user list', response.data);
                    angular.forEach(response.data, function(value, key) {
                        $scope.users.push(value);
                    });
                });
            })
            .then(function() {
                projectFactory.getProjectList().then(function(response) {
                    console.log('project list', response.data);
                    angular.forEach(response.data, function(value, key) {
                        $scope.projects.push(value);
                    });

                    $timeout(function() {
                        $scope.showData = true;
                    }, 500);
                });
            });

        angular.extend($scope, {
            totalTime: 0,
            showData: false,
            filters: {},
            users: [],
            projects: [],
            dt: new Date()
        });

        angular.extend($scope, {
            filterTime: function(filterTimeFrm) {
                console.log($scope.filters);
                var queryParams = {};

                if ($scope.filters.desc != "") {
                    queryParams.desc = $scope.filters.desc;
                }

                if ($scope.filters.users !== undefined && $scope.filters.users.length > 0) {
                    queryParams.users = [];
                    angular.forEach($scope.filters.users, function(value, key) {
                        queryParams.users.push(value.id);
                    });
                }

                if ($scope.filters.project !== undefined && $scope.filters.project.length > 0) {
                    queryParams.projects = [];
                    angular.forEach($scope.filters.project, function(value, key) {
                        queryParams.projects.push(value.id);
                    });
                }

                if ($scope.filters.startDate !== undefined) {
                    queryParams.startDate = $scope.filters.startDate;
                }

                if ($scope.filters.endDate !== undefined) {
                    queryParams.endDate = $scope.filters.endDate;
                    var startDateOfYear = moment(queryParams.startDate).dayOfYear();
                    var endDateOfYear = moment(queryParams.endDate).dayOfYear();

                    console.log(startDateOfYear, endDateOfYear);

                    if ($scope.filters.startDate !== undefined && endDateOfYear < startDateOfYear) {
                        alert('End date is before start date.');
                        return false;
                    }
                }

                timeEntry.getSearchResult(queryParams).then(function(response) {
                    console.log('search result', response.data);
                    $scope.timeEntries = response.data;
                    $scope.totalTime = 0;
                    angular.forEach(response.data, function(value, key) {
                        $scope.totalTime = $scope.totalTime + value.time;
                    });
                });
            },
            clearFilters: function() {
                $scope.filters = {};
            }
        });
    }
]);
