myApp.controller('userController', ['$scope', '$location', 'userFactory', function($scope, $location, userFactory) {
    if ($location.$$path == '/logout') {
        userFactory.logoutUser().success(function(response) {
            console.log('logout', response);
            window.location = baseUrl;
        });
    }
}]);
