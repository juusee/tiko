 <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <h1>{{$image->description}}</h1>
                <div class="image-container">
                    <a target="_blank" href="{{url('images/' . $image->id . '.' . $image->extension)}}"><img src="{{url('/imgs/' . $image->id . '.' . $image->extension)}}" alt=""></a>
                </div>
                <div class="image-info">
                    <p>By <a href="{{url('/users/' . $user->username)}}">{{$user->username}}</a></p>
                    <p>Uploaded at {{$image->created_at}}</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div id="commentBox" ng-app="commentApp" ng-controller="CommentController">

                    <h3>Comments</h3>

                    @if(Auth::check())
                    <form id="commentForm" ng-submit="submitComment()">
                        <div class="form-group">
                            <textarea form="commentForm" class="form-control" placeholder="Add comment!"
                                      name="content" ng-model="commentFormData.content"></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-default">Add</button>
                        </div>
                    </form>
                    @else
                        <p>You have to login or register to comment</p>
                    @endif

                    <ul class="commentList">
                        <li ng-repeat="comment in comments">
                            <div class="commentText">
                                <a class="commenter" href='/user/{[{comment.user}]}'>
                                    {[{comment.user}]}
                                </a>
                                <p class="sub-text">{[{comment.updated_at}]}</p>
                                <p class="content">{[{comment.content}]}</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection