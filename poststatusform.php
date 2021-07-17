<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/bootstrap.min.css">
    <link rel="stylesheet" href="style/poststatus.css">
    <title>POST_STATUS_FORM</title>
</head>
<body>
    <div class="header">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="index.html">
                        STATUS
                    </a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <li class="item">
                            <a href="index.html">Return to Home Page</a>
                        </li>
                        <li class="item">
                            <a href="searchstatusform.html">Search status</a>
                        </li>
                        <li class="item">
                            <a href="about.html">About this assignment</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div class="section">
        <div class="jumbotron">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <h1>POST A NEW STATUS</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <form class="form-horizontal" action="poststatusprocess.php" method="post">
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="code">Status Code <span class="sign">*</span></label>
                                <div class="col-sm-7">
                                    <input class="form-control" id="code" type="text" name="statuscode">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="status">Status <span class="sign">*</span></label>
                                <div class="col-sm-7">
                                    <input class="form-control" id="status" type="text" name="status">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="date">Date</label>
                                <div class="col-sm-7">
                                    <?php requireDate() ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Share</label>
                                <div class="col-sm-7">
                                    <label class="radio-inline">
                                        <input type="radio" name="share" id="public" value="public" checked>Public
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="share" id="friends" value="friends">Friends
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="share" id="onlyme" value="onlyme">Only me
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Permission Type</label>
                                <div class="col-sm-7">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="permission[]" id="like" value="like" checked>Allow Like
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="permission[]" id="comment" value="comment">Allow Comment
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="permission[]" id="share" value="share">Allow Share
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-2 col-sm-offset-4">
                                    <input class="btn btn-primary" type="submit" value="POST">
                                </div>
                                <div class="col-sm-2">
                                    <input class="btn btn-warning" type="reset" value="RESET">
                                </div>
                            </div>
                            <a href="index.html"><img src="images/return.svg" alt="">Return to Home Page</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
	# require server date 
        function requireDate(){
            $datestr = date("Y-m-d");
            echo '<input class="form-control" id="date" type="date" name="Date" value="'.$datestr.'">';
        }
    ?>
</body>
</html>
