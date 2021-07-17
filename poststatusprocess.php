<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/bootstrap.min.css">
    <link rel="stylesheet" href="style/postprocess.css">
    <title>Post Status Process</title>
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
    <div class="content">
        <div class="jumbotron">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <h1><?php main()?></h1>
                    </div>
                </div>
            </div>
        </div>

        <?php
            # the function for checking status_code;
            function checkCode($status_code){
                if(strlen($status_code) == 5){
                    $is_s = strpos($status_code, "S", 0);
                    $is_num = is_numeric(substr($status_code, 1, 4));
                    if($is_s === 0 && $is_num){
                        return true;
                    }
                }
                return false;
            }
            
            # the function for checking status;
            function checkDesc($status_desc){
                $status_desc = trim($status_desc);
                if(strlen($status_desc) != 0){
                    $rex = "/[^A-Za-z0-9\s\.!\?]/";
                    $value = preg_match($rex, $status_desc);
                    return $value ? false : true;
                }
                return false;
            }

            #connecting DB
            function connectDB()  {
                require_once('../../conf/sqlinfo.inc.php');
		        $conn = @mysqli_connect($servername, $username, $password, $dbname);
		        if(!$conn){
                    //failed
                    $message = "Connection failed: The database server is not available;<br/> please click <a href='index.html'>HERE</a> to return to home page";
                    $result = printInfo("danger", $message, "conn");
                    die($result);
                }
                // success
                return $conn;
            }

            # check date
            function validateDate($date, $format = 'Y-m-d'){
                $d = DateTime::createFromFormat($format, $date);
                return $d && $d->format($format) === $date;
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
                //create table
                $sql = "
                    create table {$table}(
                        id int(11) primary key auto_increment,
                        post_code varchar(20) not null unique,
                        post_desc varchar(20) not null,
                        post_date date,
                        post_share varchar(20),
                        post_permission varchar(20));
                ";
                return mysqli_query($conn, $sql);
            }

            # inserting data
            function insertData($conn){
                $datestr = $_POST['Date'];
                if($datestr === ""){
                    $datestr = date('Y-m-d');
                }
                if(validateDate($datestr)){
                    $date = $datestr;
                }else{
                    throw new Exception("date is wrong");
                }
                $sql = "
                    insert into posttable
                        (post_code, post_desc, post_date, post_share, post_permission)
                    values
                        ('{$_POST['statuscode']}', '{$_POST['status']}', '{$date}', '{$_POST['share']}', '". implode(',', $_POST['permission']) ."');
                ";
	            return mysqli_query($conn, $sql);
            }

            # saveData
            function saveData(){
                $conn = connectDB();
                $table_exist = checkExistenceOfTable($conn);
                if($table_exist){
                    $insertResult = insertData($conn);
                }
                if($insertResult){
                    $message = "Congratulations, your status has been successfully posted!!!<br/>
                                Please click  <a href='index.html'>HERE</a> to return to home page";
                    printInfo("success", $message);
                }else{
                    $message = "Sorry, the information you have entered does not match the requirements,<br/> 
                                please click <a href='poststatusform.php'>HERE</a> to try again";
                    printInfo("danger", $message);
                }
                mysqli_close($conn);
            }

            # print message
            function printInfo($type, $message, $key=''){
                $result = "
                    <div class='panel panel-{$type}'>
                        <div class='panel-heading'>
                            <h3 class='panel-title'>RESULT</h3>
                        </div>
                        <div class='panel-body message'>
                            {$message}
                        </div>
                    </div>
                ";
                if($key === 'conn'){
                    return $result;
                }
                echo $result;
            }

            # main
            function main(){
                $status_code = $_POST["statuscode"];
                $status_desc = $_POST["status"];
                $code_result = checkCode($status_code);
                $desc_result = checkDesc($status_desc);
                if($code_result === true && $desc_result === true){
                    saveData();
                }else{
                    $message = "Sorry, the information you have entered does not match the requirements,<br/>
                                please click <a href='poststatusform.php'>HERE</a> to try again";
                    printInfo("danger", $message);
                }
            }
        ?>
    </div>
</body>
</html>
