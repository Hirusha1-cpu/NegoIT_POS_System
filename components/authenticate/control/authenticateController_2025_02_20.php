<?php
        switch ($_REQUEST['action'])
        {
            case "show" :
                include_once  'components/authenticate/modle/authenticateModule.php';
                generateToken();
               if(isMobile())           
                	include_once  'components/authenticate/view/m_login.php';
                else
                	include_once  'components/authenticate/view/login.php';
            break;
            
            case "login" :
                include_once  'components/authenticate/modle/authenticateModule.php';
                if(login()){
                	if($force_check_in)
                		header('Location: index.php?components=authenticate&action=check_in');
                	else
                		header('Location: index.php?components=authenticate&action=reload&message='.$message.'&re=success');
                }else{
                	header('Location: index.php?components=authenticate&action=show&message='.$message.'&re=fail');
                }
            break;
            
            case "reload" :
                include_once  'components/authenticate/modle/authenticateModule.php';
		            if(isset($_COOKIE['billing'])) header('Location: index.php?components=billing&action=home&s='.$_COOKIE['user_id'].'&cust_odr=no'); else
		            if(isset($_COOKIE['bill2'])) header('Location: index.php?components=bill2&action=home&s='.$_COOKIE['user_id'].'&cust_odr=no'); else
		            if(isset($_COOKIE['order_process'])) header('Location: index.php?components=order_process&action=list_pending'); else
		            if(isset($_COOKIE['repair'])) header('Location: index.php?components=repair&action=list_pending'); else
                	if(isset($_COOKIE['inventory'])) header('Location: index.php?components=inventory&action=show_add_item&type=1'); else
		            if(isset($_COOKIE['check_availability'])) header('Location: index.php?components=availability&action=home&category=all'); else
		            if(isset($_COOKIE['trans'])) header('Location: index.php?components=trans&action=home'); else
		            if(isset($_COOKIE['supervisor'])) header('Location: index.php?components=supervisor&action=sale&store=all&salesman=all'); else
		            if(isset($_COOKIE['manager'])) header('Location: index.php?components=manager&action=daily_sale&store=all&salesman=all'); else
		            if(isset($_COOKIE['hire_purchase'])) header('Location: index.php?components=hire_purchase&action=home'); else
		            if(isset($_COOKIE['accounts'])) header('Location: index.php?components=accounts&action=home'); else
		            if(isset($_COOKIE['fin'])) header('Location: index.php?components=fin&action=home&id=35&from_date=2000-01-01'); else
		            if(isset($_COOKIE['hr'])) header('Location: index.php?components=hr&action=home'); else
                    if(isset($_COOKIE['to'])) header('Location: index.php?components=to&action=home'); else
		            if(isset($_COOKIE['report'])) header('Location: index.php?components=report&action=daily&cust=all'); else
		            if(isset($_COOKIE['settings'])) header('Location: index.php?components=settings&action=manage_user');
		            if(isset($_COOKIE['portal_supplier'])) header('Location: index.php?components=portalsup&action=dashboard');
            break;
            
            case "logout" :
                include_once  'components/authenticate/modle/authenticateModule.php';
                logout();
                if(isset($_GET['type']))
                	header('Location: index.php?components=authenticate&action=show&message='.$message.'&re=fail');
                else
                	header('Location: index.php?components=authenticate&action=show');
            break;
            
            case "check_in" :
               if(isMobile())           
                	include_once  'components/authenticate/view/m_check_in.php';
                else
                	header('Location: index.php?components=authenticate&action=reload');
            break;
            
            case "set_check_in" :
                include_once  'components/authenticate/modle/authenticateModule.php';
                if(setCheckIn())
                	header('Location: index.php?components=authenticate&action=reload');
                else
                	header('Location: index.php?components=authenticate&action=check_in&message='.$message.'&re=fail');
            break;

            case "expire" :
                include_once  'components/authenticate/modle/authenticateModule.php';
                logout();
               if(isMobile())           
                	include_once  'components/authenticate/view/m_expire.php';
                else
                	include_once  'components/authenticate/view/expire.php';
            break;
            
            case "change_pw" :
            if((isset($_SESSION['userkey']))||(isset($_COOKIE['userkey']))){
                include_once  'components/authenticate/modle/authenticateModule.php';
                generateToken();
               if(isMobile())           
                	include_once  'components/authenticate/view/m_change_pw.php';
                else
                	include_once  'components/authenticate/view/change_pw.php';
            }else 	header('Location: index.php?components=authenticate&action=login');
            break;
            
            case "set_pw" :
            if((isset($_SESSION['userkey']))||(isset($_COOKIE['userkey']))){
                include_once  'components/authenticate/modle/authenticateModule.php';
                if(changePassword()){
                	header('Location: index.php?components=authenticate&action=change_pw&message='.$message.'&re=success');
                }else{
                	header('Location: index.php?components=authenticate&action=change_pw&message='.$message.'&re=fail');
                }
            }else 	header('Location: index.php?components=authenticate&action=login');
            break;
                
            default:
                print '<p><srtong>Bad Request</strong></p>';
            break;
        }
?>