<?php
function myQuery($qry)
{
    $servername = "localhost";
    $username = "db_school_h_usr";
    $password = "12345Ha!";
    $dbname = "db_school_hel";

    $conn = new mysqli($servername, $username, $password, $dbname);

    $result = mysqli_query($conn, $qry);
    mysqli_close($conn);
    return $result;
}
