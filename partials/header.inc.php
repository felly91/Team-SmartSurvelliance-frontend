<?php session_start();
    namespace AWSCognitoApp;
    require_once('vendor/autoload.php');
    use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
    // putenv('CLIENT_ID=73nkbeiki4s2q5c2v9v8ek4aue');
    // putenv('USERPOOL_ID=us-east-1_yXMlljTfq');
    // putenv('AWS_ACCESS_KEY_ID=AKIATBV3IPRIMW7QIUPG,OAz');
    // putenv('AWS_SECRET_ACCESS_KEY=b57ImT2yIfXkVWTA+uicOO8t2hDs4UIaJd5c');
    // putenv('REGION=us-east-1');
    // putenv('VERSION=latest');
    
    $entercode = false; 

    if(!isset($_GET["id_token"]) && !isset($_GET['access_token'])){
        // header('Location: ./auth/404.php');
        $entercode = false;
    }else{
        $entercode = true;
        $id_token = $_GET["id_token"];
        $access_token = $_GET["access_token"];
        // Access token to query storage
        $_SESSION['access_token'] = $access_token;

        echo 'ID Token is ' .$id_token ."<br>";
        echo 'Access Token is ' .$access_token.'<br>';
        echo 'Session Token is ' .$_SESSION['access_token'];

        $region = 'ap-south-1';
        $version = '<AWS_REGION>';

        //Authenticate with AWS Acess Key and Secret
        $client = new CognitoIdentityProviderClient([
            'version' => getenv('VERSION'),
            'region' => getenv('REGION'),
            'credentials' => [
                'key'    => getenv('AWS_ACCESS_KEY_ID'),
                'secret' => getenv('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        try {
            //Get the User data by passing the access token received from Cognito
            $result = $client->getUser([
                'AccessToken' => $access_token,
            ]);
            
            //print_r($result);
            
            $user_email = "";
            $user_phone_number = "";
                
            //Iterate all the user attributes and get email and phone number
            $userAttributesArray = $result["UserAttributes"];
            foreach ($userAttributesArray as $key => $val) {
                if($val["Name"] == "email"){
                    $user_email = $val["Value"];
                }
                if($val["Name"] == "phone_number"){
                    $user_phone_number = $val["Value"];
                }
            }   
            
            
            if(isset($_GET["logout"]) && $_GET["logout"] == 'true'){
                //This will invalidate the access token
                $result = $client->globalSignOut([
                    'AccessToken' => $access_token,
                ]);
                
                header("Location: <COGNITO_HOSTED_UI_URL>");
                
            }
            
            
        } catch (\Aws\CognitoIdentityProvider\Exception\CognitoIdentityProviderException $e) {
            echo 'FAILED TO VALIDATE THE ACCESS TOKEN. ERROR = ' . $e->getMessage();
        }
        catch (\Aws\Exception\CredentialsException $e) {
            echo 'FAILED TO AUTHENTICATE AWS KEY AND SECRET. ERROR = ' . $e->getMessage();
        }
    

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- <base href="https://myhomeulom/"> -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
        <title>Dashboard | myHome Connectivity</title>
        <meta name="description" content="Holy blaze entertainment is a music record label signing on with young artist with talents accross the world.">
        <link rel="icon" type="image/png" href="../../img/logo.jpg" sizes="32x32">

        <meta name="author" content="HBE Records">
        <!-- Remove Tap Highlight on Windows Phone IE -->
        <meta name="msapplication-tap-highlight" content="no"/>
        <!-- The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags -->

        <!-- Styles -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet">
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
        <link href="https:////cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
        
        <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">
        <link rel="stylesheet" href="assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
        <link rel="stylesheet" href="assets/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
        <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/plugins/font-awesome/css/all.min.css" rel="stylesheet">
        <link href="assets/plugins/waves/waves.min.css" rel="stylesheet">
        <link href="assets/plugins/nvd3/nv.d3.min.css" rel="stylesheet">
        <link href="assets/plugins/toastr/toastr.min.css" rel="stylesheet"> 
        <!-- Theme style -->  
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet">

        <!-- Theme Styles -->
        <link href="assets/css/alpha.min.css" rel="stylesheet">
        <!-- <link rel="stylesheet" href="assets/dist/css/adminlte.min.css"> -->
        <link href="assets/css/custom.css" rel="stylesheet">
        <script type="text/javascript" src="assets/js/custom.js"></script>
        <!-- jQuery library -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
        <!-- video html5 player -->
        <link href="https://vjs.zencdn.net/7.8.4/video-js.css" rel="stylesheet" />
        <script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <!-- <div class="loader">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div> -->
        <div class="alpha-app">
            <div class="page-header">
                <nav class="navbar navbar-expand primary">
                    <section class="material-design-hamburger navigation-toggle">
                        <a href="javascript:void(0)" data-activates="slide-out" class="button-collapse material-design-hamburger__icon">
                            <span class="material-design-hamburger__layer"></span>
                        </a>
                    </section>
                    <a class="navbar-brand" href="#">Alpha</a> <br> <a class="navbar-brand" href="#">Welcome <?= $firstname; ?></a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <form class="form-inline my-2 my-lg-0 search">
                            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                            <label for="search" class="active"><i class="material-icons search-icon">search</i></label>
                            <a href="#" id="close-search-input"><i class="material-icons">close</i></a>
                        </form>
                        <ul class="navbar-nav ml-auto">
                            <li class="d-md-block d-lg-none nav-item">
                                <a class="nav-link search-link" href="#"><i class="material-icons">search</i></a>
                            </li>
                            <li class="nav-item dropdown d-none d-lg-block">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">notifications_none</i>
                                    <span class="badge"><?php if ($messageNum) { echo $messageNum; } ?></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right dd-notifications" aria-labelledby="navbarDropdown">
                                    <li class="notification-drop-title">Today</li>
                                    <li>
                                        <a href="#!">
                                        <div class="notification">
                                            <div class="notification-icon circle cyan"><i class="material-icons">done</i></div>
                                            <div class="notification-text"><p><b>Alan Grey</b> uploaded new theme</p><span>7 min ago</span></div>
                                        </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#!">
                                        <div class="notification">
                                            <div class="notification-icon circle deep-purple"><i class="material-icons">cached</i></div>
                                            <div class="notification-text"><p><b>Tom</b> updated status</p><span>14 min ago</span></div>
                                        </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#!">
                                        <div class="notification">
                                            <div class="notification-icon circle red"><i class="material-icons">delete</i></div>
                                            <div class="notification-text"><p><b>Amily Lee</b> deleted account</p><span>28 min ago</span></div>
                                        </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#!">
                                        <div class="notification">
                                            <div class="notification-icon circle cyan"><i class="material-icons">person_add</i></div>
                                            <div class="notification-text"><p><b>Tom Simpson</b> registered</p><span>2 hrs ago</span></div>
                                        </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#!">
                                        <div class="notification">
                                            <div class="notification-icon circle green"><i class="material-icons">file_upload</i></div>
                                            <div class="notification-text"><p>Finished uploading files</p><span>4 hrs ago</span></div>
                                        </div>
                                        </a>
                                    </li>
                                    <li class="notification-drop-title">Yestarday</li>
                                    <li>
                                        <a href="#!">
                                        <div class="notification">
                                            <div class="notification-icon circle green"><i class="material-icons">security</i></div>
                                            <div class="notification-text"><p>Security issues fixed</p><span>16 hrs ago</span></div>
                                        </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#!">
                                        <div class="notification">
                                            <div class="notification-icon circle indigo"><i class="material-icons">file_download</i></div>
                                            <div class="notification-text"><p>Finished downloading files</p><span>22 hrs ago</span></div>
                                        </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#!">
                                        <div class="notification">
                                            <div class="notification-icon circle cyan"><i class="material-icons">code</i></div>
                                            <div class="notification-text"><p>Code changes were saved</p><span>1 day ago</span></div>
                                        </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link right-sidebar-link" href="#"><i class="material-icons">more_vert</i></a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div><!-- Page Header -->

            <!-- <div id="container">
                <h1>Space-O Browser notification demo</h1>

                <h4>Generate Notification with tap on Notification</h4>
                <a href="#" id="notificationlabel" class="button">Notification</a>
            </div> -->
            <?php echo '<h2>Logged-In User Attributes</h2>';
            echo '<p>User E-Mail : ' . $user_email . '</p>';
            echo '<p>User Phone Number : ' . $user_phone_number . '</p>';
            echo "<a href='secure_page.php?logout=true&access_token=$access_token'>SIGN OUT</a>"; ?>

            <script>
                $(document).ready(function() {
                    $('#notificationlabel').click('on', function(e) {
                        e.preventDefault();
                        // alert('Hey');
                        showNotification();
                        // setInterval(function(){ showNotification(); }, 15000);
                    })
                    
                });
            </script>
<?php } ?>