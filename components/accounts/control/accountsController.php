<?php
    switch ($_REQUEST['action'])
    {
        //---------------------------------- EXPENSES ----------------------------------//  
        case "expense":
            include_once  'components/fin/modle/finModule.php';
            getExpenseFormData();
            include_once  'components/fin/view/expense_add.php';
        break;

        case "list_expense":
            include_once  'components/fin/modle/finModule.php';
            listExpenseYears();
            listExpense();
            include_once  'components/fin/view/expense_list.php';
        break;

        case "add_expense":
            include_once  'components/fin/modle/finModule.php';
            if(addExpense())
                header('Location: index.php?components=accounts&action=one_expense&id='.$expense_id.'&message='.$message.'&re=success');
            else
                header('Location: index.php?components=accounts&action=expense&message='.$message.'&re=fail');
        break;

        case "one_expense" :
            include_once  'components/fin/modle/finModule.php';
            if(getOneExpense())
                include_once  'components/fin/view/expense_one.php';
            else 
                header('Location: index.php?components=accounts&action=list_expense&year='.date("Y",time()));
        break;

        case "delete_expense" :
           include_once  'components/fin/modle/finModule.php';
           if(deleteExpense())
           		header('Location: index.php?components=accounts&action=list_expense&year='.date("Y",time()).'&message='.$message.'&re=success');
           	else
           		header('Location: index.php?components=accounts&action=one_expense&id='.$expense_id.'&message='.$message.'&re=fail');
        break;

        //---------------------------------- CHART OF ACCOUNTS ----------------------------------//
        case "chart_of_accounts" :
               include_once  'components/fin/modle/finModule.php';
               getCahrtOfAccounts();
               getAccountFormData();
               include_once  'components/fin/view/chart_of_accounts.php';
        break;

        case "acount_history" :
           include_once  'components/fin/modle/finModule.php';
           getAccountHistory();
           include_once  'components/fin/view/view_account.php';
        break;

        default:
            print '<p><srtong>Bad Request</strong></p>';
        break;
    }
?>