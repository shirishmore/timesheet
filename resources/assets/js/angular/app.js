var myApp = angular.module('myApp', [
    'ngRoute',
    'ngCookies',
    'oi.select',
    '720kb.datepicker',
    'chart.js',
    'angular.snackbar',
    'angular-loading-bar',
    'textAngular'
]);

myApp.run(['userFactory', '$cookies', function(userFactory, $cookies) {
    if ($cookies.get('userObj') === undefined) {
        userFactory.getUserObj().success(function(response) {
            console.log('created user object', response);
            $cookies.putObject('userObj', response);
        });
    }
}]);

myApp.filter('unsafe', function($sce) {
    return $sce.trustAsHtml;
});

myApp.controller('globalController', ['$scope', '$location',
    function($scope, $location) {
        angular.extend($scope, {
            reportTabUrl: '/templates/manager/reportTabs.html',
            singleProjectTab: '/templates/projects/singleProjectTab.html',
            checkActiveLink: function(currLink) {
                if ($location.path() == currLink) {
                    return 'active';
                }
            },
            timeAgo: function(string) {
                return moment(string).fromNow();
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

        $routeProvider.when('/logout', {
            templateUrl: '/templates/users/user-logout.html',
            controller: 'userController'
        });

        $routeProvider.when('/report', {
            templateUrl: '/templates/manager/reports.html',
            controller: 'reportController'
        });

        $routeProvider.when('/projects', {
            templateUrl: '/templates/projects/projects-listing.html',
            controller: 'projectController',
            resolve: {
                action: function(projectFactory) {
                    return {
                        projects: projectFactory.getProjectList()
                    }
                }
            }
        });

        $routeProvider.when('/projects/add', {
            templateUrl: '/templates/projects/add-project.html',
            controller: 'projectController',
            resolve: {
                action: function(clientFactory) {
                    return {
                        clients: clientFactory.getClientList()
                    }
                }
            }
        });

        $routeProvider.when('/projects/:id', {
            templateUrl: '/templates/projects/projects-details.html',
            controller: 'projectController',
            resolve: {
                action: function() {
                    return 'single';
                }
            }
        });

        $routeProvider.when('/projects/:pid/comments', {
            templateUrl: '/templates/projects/project-comments.html',
            controller: 'projectController',
            resolve: {
                action: function(commentFactory, $route) {
                    return {
                        comments: commentFactory.getProjectComments($route.current.params.pid)
                    };
                }
            }
        });

        $routeProvider.when('/projects/:id/estimate/add', {
            templateUrl: '/templates/projects/project-estimate-add.html',
            controller: 'projectController',
            resolve: {
                action: function() {
                    return 'single';
                }
            }
        });

        $routeProvider.when('/projects/estimate/:estimateId', {
            templateUrl: '/templates/projects/estimate-edit.html',
            controller: 'projectController',
            resolve: {
                action: function() {
                    return 'single';
                }
            }
        });

        /*Management URLs*/
        $routeProvider.when('/manage/back-date-entry', {
            templateUrl: '/templates/admin/backdateentry.html',
            controller: 'adminController',
            resolve: {
                action: function(userFactory, timeEntry) {
                    return {
                        users: userFactory.getUserList(),
                        allEntries: timeEntry.getBackDateEntries()
                    };
                }
            }
        });

        $routeProvider.when('/manage/view-back-date-entry/:backdateentryId', {
            templateUrl: '/templates/admin/view-backdateentry.html',
            controller: 'adminController',
            resolve: {
                action: function(userFactory, timeEntry,$route) {
                    return {
                        singleBackDateEntry: timeEntry.getBackDateEntriesById($route.current.params.backdateentryId)
                    };
                }
            }
        });


        $routeProvider.when('/user/request-backdate-entry', {
            templateUrl: '/templates/users/request-backdate.html',
            controller: 'userController',
            resolve: {
                action: function(userFactory, timeEntry) {
                    return {
                        users: userFactory.getUserListByRole(),
                        allEntries: timeEntry.getRequestBackDateEntries()
                    };

                }
            }
        });

        $routeProvider.when('/user/view-request-backdate/:backdateentryId', {
            templateUrl: '/templates/users/view-request-backdate.html',
            controller: 'userController',
            resolve: {
                action: function(userFactory, timeEntry,$route) {
                    return {
                        singleRequestBackdateEntry: timeEntry.getRequestBackDateEntriesById($route.current.params.backdateentryId)

                    };
                }
            }
        });
        $routeProvider.otherwise('/');
    }
]);
