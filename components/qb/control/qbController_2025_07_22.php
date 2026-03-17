<?php
if (passwordExpire()) {
      header('Location: index.php?components=authenticate&action=change_pw&message=Your%20Password%20Has%20Expired.%20Please%20Change%20it%20NOW&re=fail');
}

switch ($_REQUEST['action']) {
      case "accounts":
            include_once 'components/qb/modle/qbModule.php';
            getAccounts();
            include_once 'components/qb/view/accounts.php';
            break;

      case "profit_and_loss":
            include_once 'components/qb/modle/qbModule.php';
            getProfitAndLossReport();
            getQBRegisteredSubsystems();
            include_once 'components/qb/view/profit_and_loss.php';
            break;

      case "trial_balance":
            include_once 'components/qb/modle/qbModule.php';
            getTrialBalanceReport();
            getQBRegisteredSubsystems();
            include_once 'components/qb/view/trail_balance.php';
            break;

      case "balance_sheet":
            include_once 'components/qb/modle/qbModule.php';
            getBalanceSheetReport();
            getQBRegisteredSubsystems();
            include_once 'components/qb/view/balance_sheet.php';
            break;

      // case "journal_report":
      //       include_once 'components/qb/modle/qbModule.php';
      //       getJournalReport();
      //       getQBRegisteredSubsystems();
      //       include_once 'components/qb/view/journal_report.php';
      //       break;

      case "journal_report":
            include_once 'components/qb/modle/qbModule.php';
            getAccountList();
            getDepartmentList();
            getJournalEntriesByDepartments();
            include_once 'components/qb/view/journal_report_to_stores.php';
            break;

      case "vendors":
            include_once 'components/qb/modle/qbModule.php';
            getVendors();
            include_once 'components/qb/view/vendors.php';
            break;

      case "create_qb_accounts_in_system":
            include_once 'components/qb/modle/qbModule.php';
            createQbAccountsInSystem();
            break;

      case "create_users_in_qb":
            include_once 'components/qb/modle/qbModule.php';
            createEmployeesInQB();
            break;

      case "general_ledger":
            include_once 'components/qb/modle/qbModule.php';
            getAccountActivity();
            include_once 'components/qb/view/ledger.php';
            break;

      case "customers":
            include_once 'components/qb/modle/qbModule.php';
            getCustomers();
            include_once 'components/qb/view/customers.php';
            break;

      case "create_customers_in_qb":
            include_once 'components/qb/modle/qbModule.php';
            createCustomersInQB();
            break;

      case "create_vendors_in_qb":
            include_once 'components/qb/modle/qbModule.php';
            createVendorsInQB();
            break;

      case "create_basic_accounts_in_qb":
            include_once 'components/qb/modle/qbModule.php';
            createBasicAccountsInQB();
            break;
      case "dashboard":
            include_once 'components/qb/modle/qbModule.php';
            getDashboard();
            include_once 'components/qb/view/dashboard.php';
            break;
      default:
            print '<p><strong>Bad Request</strong></p>';
            break;
}

?>