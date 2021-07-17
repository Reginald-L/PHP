<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/bootstrap.min.css">
    <link rel="stylesheet" href="style/searchprocess.css">
    <title>Search Status Process</title>
</head>
<body>
    <div class="header">
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="index.html">
                        STATUS
                    </a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <li class="item">
                            <a href="poststatusform.php">Post a new status</a>
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
    <div class="content">
        <div class="jumbotron">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <h1>Status Information</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <?php main();?>
                    </div>
                </div>
                <div class="row links">
                    <div class="col-md-4 col-md-offset-2">
                        <a href='searchstatusform.html'><img src="images/search.svg" alt="">Search for another status</a>
                    </div>
                    <div class="col-md-3 col-md-offset-1 right">
                        <a href='index.html'><img src="images/return.svg" alt="">Return to Home Page</a>
                    </div>
                </div> 
            </div>
        </div>
        <?php
            # Checking if status is empty
            function checkStatusSearch(){
                $searchstr = $_GET['Search'];
                if(strlen($searchstr) == 0){
                    echo "
                        <div class='panel panel-danger'>
                            <div class='panel-heading'>
                                <h3 class='panel-title'>RESULT</h3>
                            </div>
                            <div class='panel-body message'>
                                <p>Sorry, typed an empty character</p>
                            </div>
                        </div>
                    ";
                    return false;
                }
                return true;
            }

            # Check if the table exists
            function checkExistenceOfTable($conn){
                $table = "posttable";
                $dbresult = mysqli_query($conn, "show tables;");
                foreach($dbresult as $row){
                    if($row['Tables_in_yourDBName'] === $table){
                        return true;
                    }
                }
		# no table
                $message = "Connection failed: The database server is not available;<br/> please click <a href='poststatusform.php'>HERE</a> to post a status;";
                printError($message);
            }

            #connecting DB
            function connectDB()  {
                require_once('../../conf/sqlinfo.inc.php');
                $conn = @mysqli_connect($servername, $username, $password, $dbname);       
		        if(!$conn){
                    //failed
                    $message = "
                        <div class='panel panel-danger'>
                            <div class='panel-heading'>
                                <h3 class='panel-title'>RESULT</h3>
                            </div>
                            <div class='panel-body message'>
                                <p>Connection failed: The database server is not available;<br/> please click <a href='index.html'>HERE</a> to return to home page;</p>
                            </div>
                        </div>
                    ";
                    die($message);
                }
                // success
                return $conn;
		
            }

            # Querying data using status
            function queryDataByStatus($conn, $status){
                $status = strtolower(trim($status));
                $status = str_replace("'", "", $status);
                $sql = "
                    select 
                	id,
                        post_code as code, 
                        post_desc as status, 
                        post_date as date, 
                        post_share as share, 
                        post_permission as permission 
                    from 
                        posttable 
                    where 
                        lower(post_desc) = '{$status}'
                    order by
                        right(code, 4), date, id
                    ;";
                $dbresult = mysqli_query($conn, $sql);
                if(mysqli_num_rows($dbresult) == 0){
                    # no result, query all data
                    $sql = "
                        select 
                            id,
                            post_code as code, 
                            post_desc as status, 
                            post_date as date, 
                            post_share as share, 
                            post_permission as permission 
                        from 
                            posttable
                        order by
                            right(code, 4), date, id
                        ;";
                    $dbresult = mysqli_query($conn, $sql);
                    echo "<h4>No results were found, all records are shown below</h4>";
                }
                echo "<table class='table table-hover result-table'>";
                echo "
                    <thead>
                        <tr>
                            <th>Status Code</th>
                            <th>Status</th>
                            <th>Date Posted</th>
                            <th>Share</th>
                            <th>Permission</th>
                        </tr>
                    </thead>
                    <tbody>";

                foreach($dbresult as $row){
                    echo "
                        <tr>
                            <td>{$row['code']}</td>
                            <td>{$row['status']}</td>
                            <td>{$row['date']}</td>
                            <td>{$row['share']}</td>
                            <td>{$row['permission']}</td>
                        </tr>
                    ";
                }
                echo "</tbody></table>";
            }

            function printError($message){
                echo "
                    <div class='panel panel-danger'>
                        <div class='panel-heading'>
                            <h3 class='panel-title'>RESULT</h3>
                        </div>
                        <div class='panel-body message'>
                            <p>{$message}</p>
                        </div>
                    </div>
                ";
            }

            # main
            function main(){
		$conn = connectDB();
                $result = checkStatusSearch() && checkExistenceOfTable($conn);
                if($result === true){
                    queryDataByStatus($conn, $_GET['Search']);
                }
		mysqli_close($conn);
            }
        ?>
    </div>
</body>
</html>