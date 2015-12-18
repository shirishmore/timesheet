myApp.factory('userFactory', ['$http', function($http) {
    var userFactory = {};

    userFactory.getUserList = function() {
        return $http.get(baseUrl + 'api/get-user-list');
    }

    return userFactory;
}]);
