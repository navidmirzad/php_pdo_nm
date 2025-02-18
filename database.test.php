/*
    $db_server = getenv("DB_SERVER") ?? "";
    $db_user = getenv("DB_USER") ?? "";
    $db_password = getenv("DB_PASSWORD") ?? "";
    $db_name = getenv("DB_DATABASE") ?? "";
    $connection = '';

    try {
        $connection = mysqli_connect(
            $db_server, 
            $db_user, 
            $db_password, 
            $db_name
        );
        echo "Database connection is established.";
    } catch (mysqli_sql_exception $e) {
        echo "Could not connecet to database: ", $e->getMessage();
    }
    
?>
*/