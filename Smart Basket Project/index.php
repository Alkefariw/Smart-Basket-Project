<?php
session_start();
if (isset($_SESSION['username'])) { ?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Book ISBN Search</title>
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    </head>

    <body>
        <div class="notify"><span id="notifyType" class=""></span></div>
        <div class="container main_page">
            <img src="./img/logo.png" alt="The Catholic University of America University" id="CUA-logo">
            <h2>Mullen Library </h2>

            <div class="row" style="clear:both">
                <div class="col-12 col-md-6 col-lg-4">
                    <table class="top-table">
                        <tbody>
                            <tr>
                                <td>Book ISBN:</td>
                                <td><input type="text" id="ISBN" placeholder="Scan Your Book"></td>
                            </tr>
                            <tr>
                                <td>Username:</td>
                                <td><input disabled value="<?php echo $_SESSION['username']; ?>"></td>
                            </tr>
                            <tr>
                                <td>Student ID:</td>
                                <td><input disabled value="<?php echo $_SESSION['uid']; ?>"></td>
                            </tr>
                        </tbody>
                    </table>

                </div>

            </div>

            <div class="">
                <table class="table table-striped table-bordered" id="bookTable">
                    <thead>
                        <tr>
                            <th>ISBN</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Publisher</th>
                            <th>Publication Date</th>
                            <th>Return Date</th>
                        </tr>
                    </thead>
                    <tbody id="bookTableBody">
                    </tbody>
                </table>
            </div>


            <div id="btn_area">
                <button id="printBtn" class="btn btn-primary" onclick="window.print();" style="display: none;">Print</button>
                <button id="clearBtn" class="btn btn-danger">Clear</button>
                <button id="sendBtn">Send to Email</button>
                <button id="addBookBtn">Add Book Manually</button>
                <button id="logOut">Log Out</button>
            </div>


        </div>


        <!-- Modal -->
        <div class="modal fade" id="modalAddBook" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form onsubmit="addBook(event)">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Add Book</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12 col-md-8 form-group">
                                    <label>ISBN</label>
                                    <input class="form-control" id="formISBN" required pattern="^[0-9-_]{3,}$">
                                </div>
                                <div class="col-12 col-md-12 form-group">
                                    <label>Book Title</label>
                                    <input class="form-control" id="title" required>
                                </div>
                                <div class="col-12 col-md-12 form-group">
                                    <label>Author</label>
                                    <input class="form-control" id="author" required>
                                </div>
                                <div class="col-12 col-md-12 form-group">
                                    <label>Publisher</label>
                                    <input class="form-control" id="publisher" required>
                                </div>
                                <div class="col-12 col-md-8 form-group">
                                    <label>Publication Date</label>
                                    <input class="form-control" id="pDate" required>
                                </div>
                                <div class="col-12 col-md-8 form-group">
                                    <label>Return Date</label>
                                    <input class="form-control" id="rDate" required>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary"> Add Book </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>



        <!-- Confirm Modal -->
        <div id="modal"></div>


        <!-- Email Modal -->
        <div class="modal fade" id="modalEmail" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form onsubmit="sendEmail(event)">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Send Email</h5>
                        </div>
                        <div class="modal-body" style="min-width:100%">
                            <div class="form_area p-3">
                                <div class="form-group">
                                    <label>Enter email address</label>
                                    <input class="form-control" type="email" id="email" required>
                                </div>
                            </div>
                            <div class="message_area text-center">
                            </div>
                        </div>
                        <div class="modal-footer" style="justify-content:space-between">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary action"> Send </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>



        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="./js/html2canvas.min.js"></script>
        <script src="./js/DNDAlert.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>
        <script src="./js/main.js"></script>
        <script src="./js/book.js"></script>
    </body>

    </html>
<?php } else {



    ///##################################################
    ///// SIGN IN / LOGIN
    ///####################################################
    if (isset($_POST['ch']) && $_POST['ch'] == "sign_in") {

        $errors = [];


        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username) || strlen($password) < 2) {
            $errors[] = ['aform', 'Wrong login details'];
        }
        if (count($errors) > 0) {
            die(json_encode($errors));
        }
        if (!file_exists("./db/user.json")) {
            $users = [];
        } else {
            // Get the user database
            $users = @json_decode(file_get_contents("./db/user.json"), true);
            if($users == null){
                $users = [];
            }
        }
        // Do check for duplicate entries
        foreach ($users as $user) {
            if (strtolower($username) == strtolower($user['username']) && $password == $user['password']) {
                // Successfull login
                $_SESSION['username'] = $user['username'];
                $_SESSION['uid'] = $user['uid'];
                die('PASS');
            }
        }

        //If here, No correct login
        $errors[] = ['aform', 'Wrong login details'];
        die(json_encode($errors));
    }


    ///##################################################
    ///// SIGN UP
    ///####################################################
    if (isset($_POST['ch']) && $_POST['ch'] == "sign_up") {

        $errors = [];

        $uid = htmlspecialchars($_POST['uid']);
        $email = htmlspecialchars($_POST['email']);
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
            $errors[] = ['username2', 'Username must be alphanumeric and underscore'];
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = ['email2', 'Please enter a valid email address'];
        }
        if (strlen($uid) < 3) {
            $errors[] = ['uid2', 'Invalid User ID'];
        }
        if (strlen($password) < 6) {
            $errors[] = ['password2', 'Minimum password length should be 6 characters'];
        }
        if (count($errors) > 0) {
            die(json_encode($errors));
        }

        if (!file_exists("./db/user.json")) {
            $users = [];
        } else {
            // Get the user database
            $users = @json_decode(file_get_contents("./db/user.json"), true);
            if($users == null){
                $users = [];
            }
        }
        // Do check for duplicate entries
        foreach ($users as $user) {
            if (strtolower($username) == strtolower($user['username'])) {
                $errors[] = ['username2', 'Username already exist'];
            }
            if (strtolower($email) == strtolower($user['email'])) {
                $errors[] = ['email2', 'Email address already exist'];
            }
            if (strtolower($uid) == strtolower($user['uid'])) {
                $errors[] = ['uid2', 'User ID already exist'];
            }
        }
        if (count($errors) > 0) {
            die(json_encode($errors));
        }

        // Now we can add the user
        $users[] = [
            "username" => $username,
            "email" => $email,
            "uid" => $uid,
            "password" => $password,
        ];

        //Update file
        file_put_contents("./db/user.json", json_encode($users));
        die("PASS");
    }


?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Login & Signup - Book ISBN Search</title>
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/login.css">
    </head>

    <body>




        <section class="forms-section">
            <h1 class="section-title">Login & Signup Forms</h1>
            <div class="forms">
                <div class="form-wrapper is-active">
                    <button type="button" class="switcher switcher-login">
                        Login
                        <span class="underline"></span>
                    </button>
                    <form class="form form-login" id="cform" onsubmit="sign_in_form(event)">
                        <div class="text-center">
                            <span id="aform"></span>
                        </div>
                        <fieldset>
                            <legend>Please, enter your username and Password for login.</legend>
                            <div class="input-block">
                                <label for="username">Username</label>
                                <input id="username" required>
                            </div>
                            <div class="input-block">
                                <label for="password">Password</label>
                                <input id="password" type="password" required>
                            </div>
                        </fieldset>
                        <div id="sbutton">
                            <button type="submit" class="btn-login">Login</button>
                        </div>
                    </form>
                </div>
                <div class="form-wrapper">
                    <button type="button" class="switcher switcher-signup">
                        Sign Up
                        <span class="underline"></span>
                    </button>
                    <form class="form form-signup" id="cform2" onsubmit="sign_up_form(event)">
                        <div class="text-center">
                            <span id="aform2"></span>
                        </div>
                        <fieldset>
                            <legend>Please, enter your email, password and password confirmation for sign up.</legend>
                            <div class="input-block">
                                <label for="email">Email</label>
                                <input id="email2" type="email" required placeholder="Enter your Email Address">
                            </div>
                            <div class="input-block">
                                <label for="username2">Username</label>
                                <input id="username2" type="text" required placeholder="Enter your Username">
                            </div>
                            <div class="input-block">
                                <label for=password2">Password</label>
                                <input id="password2" type="password" required placeholder="Enter your Password">
                            </div>
                            <div class="input-block">
                                <label for="uid2">ID Number</label>
                                <input id="uid2" minlength="5" required placeholder="Enter your ID Number">
                            </div>
                        </fieldset>
                        <div id="sbutton2">
                            <button type="submit" class="btn-signup">Sign Up</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>


        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <script src="./js/DNDAlert.js"></script>
        <script src="./js//main.js"></script>
    </body>

    </html>




<?php } ?>