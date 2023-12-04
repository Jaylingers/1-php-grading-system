<?php global $conn;
$var = "logout";
include '../../students_page/header.php';

if (isset($_POST['logout1'])) {
    unset($_SESSION['user_type']); // remove it now we have used it
    unset($_SESSION['ids']); // remove it now we have used it
    echo '<script>';
    echo '   
                history.pushState({page: "another page"}, "another page", "/1-php-grading-system");
                window.location.reload();
            ';
    echo '</script>';

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <style>
        header {
            position: relative;
        }

        .change-password-container {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 90vh;
        }

        .change-password-container form {
            display: flex;
            flex-direction: column;
            justify-content: center;
            border-radius: var(--border-radius-2);
            padding: 3.5rem;
            background-color: var(--color-white);
            box-shadow: var(--box-shadow);
            width: 95%;
            max-width: 32rem;
        }

        .change-password-container form:hover {
            box-shadow: none;
        }

        .change-password-container form input[type="password"] {
            border: none;
            outline: none;
            border: 1px solid var(--color-light);
            background: transparent;
            height: 2rem;
            width: 100%;
            padding: 0 0.5rem;
        }

        .change-password-container form .box {
            padding: 0.5rem 0;
        }

        .change-password-container form .box p {
            line-height: 2;
        }

        .change-password-container form h2 + p {
            margin: 0.4rem 0 1.2rem 0;
        }

        .btn {
            background: none;
            border: none;
            border: 2px solid var(--color-primary) !important;
            border-radius: var(--border-radius-1);
            padding: 0.5rem 1rem;
            color: var(--color-white);
            background-color: var(--color-primary);
            cursor: pointer;
            margin: 1rem 1.5rem 1rem 0;
            margin-top: 1.5rem;
        }

        .btn:hover {
            color: var(--color-primary);
            background-color: transparent;
        }
    </style>
</head>
<body>
<div class="change-password-container">
    <form action="index.php" method="post">
        <h2>Are you sure?</h2>
        <p class="text-muted">
            Do you really want to logout? This process cannot be undone.
        </p>

        <div class="button">
            <input type="submit" name="logout1" value="Proceed" class="btn"/>
        </div>
    </form>
</div>
</body>

