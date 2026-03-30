<?php
    switch ($_REQUEST['action']){
        case "show" :
            include_once  'components/authenticate/modle/authenticateModule.php';
            generateToken();
            if(isMobile())           
                include_once  'components/authenticate/view/m_login.php';
            else
                include_once  'components/authenticate/view/m_login.php';
        break;
        
        case "login" :
            include_once  'components/authenticate/modle/authenticateModule.php';
            if(login()){
                header('Location: index.php?components=authenticate&action=reload&message='.$message.'&re=success');
            }else{
           	 header('Location: index.php?components=authenticate&action=show&message='.$message.'&re=fail');
            }
        break;
        
        case "reload" :
            include_once  'components/authenticate/modle/authenticateModule.php';
            if(isset($_COOKIE['userkey'])){
                header('Location: index.php?components=backend&action=lock');
            }
        break;
        
        case "logout" :
            include_once  'components/authenticate/modle/authenticateModule.php';
            if(logout())
                header('Location: index.php?components=authenticate&action=show');
        break;
            
        default:
            print '<p><srtong>Bad Request</strong></p>';
        break;
    }
?>