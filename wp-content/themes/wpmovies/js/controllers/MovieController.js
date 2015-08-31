app.controller('MovieController', ['$scope', '$http', function($scope, $http) {
  $scope.loading_text = "Movie loading...";
  $scope.movie_ready = false;
  $scope.movie_ready_class = 'hide';
  $http.defaults.useXDomain = true;
  $scope.countdown_class = '';
  // $scope.video_source = '';
  $scope.video_sources = [];
  $scope.get_movie_source = function(movie_name, movie_id, video_source, crawl_url, video_status) {
    console.log('Starting to get data...');
    $scope.video_source = video_source;
    var api_url = "http://api.moviechannel24.com/kissanime/crawl_episode?m_id=" + movie_id + "&m_name=" + movie_name + "&crawl_source=" + crawl_url;
    if (video_status == '302' || video_status == '301' || video_status == '200') {
      $scope.video_source = video_source;
      $scope.video_sources = [video_source];
      setTimeout(function(){
        console.log('Movie ready after timeout');
        $scope.movie_ready = true;
        $scope.movie_ready_class = 'unhide';
        $scope.movie_wait_class = 'hide';
        $scope.$apply();
      }, 3000);

    } else {
      $http.get(api_url).
        then(function(response) {
          console.log('Movie ready!!!');
          console.log(response.data);
          $scope.episode = response.data;
          $scope.video_source = response.data['direct_links'][0];
          $scope.video_sources = response.data['direct_links'];
          console.log('video source is ' + response.data['direct_links'][0]);
          $scope.movie_ready = true;
          $scope.movie_ready_class = 'unhide';
          $scope.movie_wait_class = 'hide';
          // $scope.$apply();
        }, function(response) {
          console.log('Fail !!!');
          console.log(response);
          $scope.movie_ready = false;
          $scope.movie_ready_class = 'hide';
          $scope.movie_wait_class = 'unhide';
        });
    }

    console.log('Completed getting data!!!' + crawl_url);
  };

  $scope.countdown_ready = function() {
    // $scope.movie_ready = true;
    // $scope.movie_ready_class = 'unhide';
    // $scope.movie_wait_class = 'hide';
    $scope.countdown_class = 'hide';
    $scope.loading_text = "Please wait...";
    $scope.$apply();
    console.log('[countdown_ready] - countdown is ready!!!');
  }
  $scope.callbackTimer = {};
  // $scope.callbackTimer.status = 'Running';
  // $scope.callbackTimer.callbackCount = 0;
  $scope.callbackTimer.finished = function() {
      // $scope.callbackTimer.status='COMPLETE!!';
      // $scope.callbackTimer.callbackCount++;

      $scope.movie_ready = true;
      $scope.movie_ready_class = 'unhide';
      console.log('countdown is ready!!!');

      $scope.$apply();
  };



}]);
