var app = angular.module("movieApp", ['timer'], function($interpolateProvider) {
    // change synxtax {{}} to <% %>
    // $interpolateProvider.startSymbol('<%');
    // $interpolateProvider.endSymbol('%>');
});

app.config(['$httpProvider', function($httpProvider) {
    $httpProvider.defaults.headers.common["X-Requested-With"] = 'XMLHttpRequest';
}]);

app.filter('trusted', ['$sce', function ($sce) {
    return function(url) {
        return $sce.trustAsResourceUrl(url);
    };
}]);

// allow asign url address to variable
app.config(function($sceDelegateProvider) {
  $sceDelegateProvider.resourceUrlWhitelist(['**']);
});
