<?php 
$host="localhost";
$user="root";
$password= "";
$db="admin";

$data=mysqli_connect($host,$user,$password,$db);
if($data===false){
    die("connection error");
}
if($_SERVER["REQUEST_METHOD"]=="POST"){ 
    $username=$_POST["username"];
    $password=$_POST["password"];
    $sql="select * from login where username= '".$username. "'AND password='".$password."' ";
    $result=mysqli_query($data,$sql);
    $row=mysqli_fetch_array($result);

    if($row["usertype"]== "admin"){
        header("location:imo.php");
    }
    else{
        echo "username or password wrong";
    }
}

?>

<html>
    <head>
        <title></title>

    </head>

    <body>
        <center>
            
            <div style="background-color:grey; width:500px">
            <form action="#" method="POST">
        <div>
            <label>username</label>
            <input type="text" name="username" required>
        </div>

        <div>
            <label>password</label>
            <input type="password" name="password" required>
        </div>

        <div>
            <input type="submit" value="Login">
        </div>
            </form>
            </div>
        </center>

    </body>
</html>