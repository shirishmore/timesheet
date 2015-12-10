var myApp = angular.module('myApp', [
    'ngRoute',
    'oi.select',
    '720kb.datepicker',
    'chart.js',
    'angular.snackbar'
]);

myApp.controller('globalController', ['$scope', '$location',
    function($scope, $location) {
        angular.extend($scope, {
            reportTabUrl: '/templates/manager/reportTabs.html',
            singleProjectTab: '/templates/projects/singleProjectTab.html',
            checkActiveLink: function(currLink) {
                if ($location.path() == currLink) {
                    return 'active';
                }
            }
        })
    }
]);

/*Routes*/
myApp.config(['$routeProvider', '$locationProvider',
    function($routeProvider, $locationProvider) {
        $routeProvider.when('/', {
            templateUrl: '/templates/manager/managerReports.html',
            controller: 'dashboardController'
        });

        $routeProvider.when('/report', {
            templateUrl: '/templates/manager/reports.html',
            controller: 'reportController'
        });

        $routeProvider.when('/projects', {
            templateUrl: '/templates/projects/projects-listing.html',
            controller: 'projectController'
        });

        $routeProvider.when('/projects/:id', {
            templateUrl: '/templates/projects/projects-details.html',
            controller: 'projectController'
        });

        $routeProvider.when('/projects/:id/estimate/add', {
            templateUrl: '/templates/projects/project-estiamte-add.html',
            controller: 'projectController'
        });

        $routeProvider.otherwise('/');
    }
]);
