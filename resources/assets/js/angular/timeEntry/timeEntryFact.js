myApp.factory('timeEntry', ['$http', function($http) {
    var timeEntry = {};

    timeEntry.getEntries = function() {
        return $http.get(baseUrl + 'api/time-report');
    }

    /*timeEntry.getUserList = function() {
        return $http.get(baseUrl + 'api/get-user-list');
    }*/

    timeEntry.getSearchResult = function(filterParams) {
        return $http({
            headers: {
                'Content-Type': 'application/json'
            },
            url: baseUrl + 'api/time-report-filter',
            method: 'POST',
            data: filterParams
        });
    }

    timeEntry.getTimeSheetEntryByDate = function() {
        return $http.get(baseUrl + 'api/get-timeentry-by-date');
    }

    timeEntry.getEntriesForEstimate = function(estimateId) {
        return $http.get(baseUrl + 'api/get-timeentry-for-estimate/' + estimateId);
    }

    timeEntry.saveBackDateEntry = function(entryData) {
        return $http({
            headers: {
                'Content-Type': 'application/json'
            },
            url: baseUrl + 'api/allow-backdate-entry',
            method: 'POST',
            data: entryData
        });
    }

    return timeEntry;
}]);
