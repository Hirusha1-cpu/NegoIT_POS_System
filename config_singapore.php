<?php

switch ($_SERVER['SERVER_NAME']){
	//--------------------------ZigoBilling------------------------------------//
	case "billing1.negoit.info" :
	$conn=mysqli_connect('database-1.cluster-cvqsqpr9ukhw.ap-southeast-1.rds.amazonaws.com','zigo_dbuser','zigo1234','zigotech');
	$conn2=mysqli_connect('database-1.cluster-ro-cvqsqpr9ukhw.ap-southeast-1.rds.amazonaws.com','zigo_dbuser','zigo1234','zigotech');
	if(mysqli_connect_errno()){  echo "Failed to connect to MySQL: " . mysqli_connect_error();  }	
	break;
	/*
	case "billing1.negoit.info" :
	$conn=mysqli_connect('localhost','zigo_dbuser','zigo1234','zigobilling3');
	$conn2=mysqli_connect('localhost','zigo_dbuser','zigo1234','zigobilling3');
	if(mysqli_connect_errno()){  echo "Failed to connect to MySQL: " . mysqli_connect_error();  }	
	break;
	*/
	case "billing1.negohosting.com" :
	$conn=mysqli_connect('localhost','zigo_dbuser_dr','zngo@4951','zigobilling_dr');
	$conn2=mysqli_connect('localhost','zigo_dbuser_dr','zngo@4951','zigobilling_dr');
	if(mysqli_connect_errno()){  echo "Failed to connect to MySQL: " . mysqli_connect_error();  }	
	break;
	
	case "billing1.localhost.local" :
	$conn=mysqli_connect('localhost','zigo_dbuser','zigo1234','zigobilling');
	$conn2=mysqli_connect('localhost','zigo_dbuser','zigo1234','zigobilling');
	if(mysqli_connect_errno()){  echo "Failed to connect to MySQL: " . mysqli_connect_error();  }	
	break;	
	
	//--------------------------ZigoBilling- Bill2--------------------------//
	case "billing21.negoit.info" :
		$conn=mysqli_connect('database-1.cluster-cvqsqpr9ukhw.ap-southeast-1.rds.amazonaws.com','zigo_dbuser21','zigo1234','zigotech21');
		$conn2=mysqli_connect('database-1.cluster-ro-cvqsqpr9ukhw.ap-southeast-1.rds.amazonaws.com','zigo_dbuser21','zigo1234','zigotech21');
		if(mysqli_connect_errno()){  echo "Failed to connect to MySQL: " . mysqli_connect_error();  }	
	break;
	//--------------------------OMS-UAE------------------------------------//
	case "billing17.negoit.info" :
		$conn=mysqli_connect('database-1.cluster-cvqsqpr9ukhw.ap-southeast-1.rds.amazonaws.com','zigo_dbuser','zigo1234','oms_uae');
		$conn2=mysqli_connect('database-1.cluster-ro-cvqsqpr9ukhw.ap-southeast-1.rds.amazonaws.com','zigo_dbuser','zigo1234','oms_uae');
		if(mysqli_connect_errno()){  echo "Failed to connect to MySQL: " . mysqli_connect_error();  }	
		break;
	
		case "billing17.negohosting.com" :
		$conn=mysqli_connect('database-1.cluster-cvr3hgtayido.me-central-1.rds.amazonaws.com','zigo_dbuser','zigo1234','oms_uae');
		$conn2=mysqli_connect('database-1.cluster-ro-cvr3hgtayido.me-central-1.rds.amazonaws.com','zigo_dbuser','zigo1234','oms_uae');	
		#$conn=mysqli_connect('localhost','zigo_dbuser_dr','zngo@4951','oms_uae_dr');
		#$conn2=mysqli_connect('localhost','zigo_dbuser_dr','zngo@4951','oms_uae_dr');
		if(mysqli_connect_errno()){  echo "Failed to connect to MySQL: " . mysqli_connect_error();  }	
		break;
		
		case "billing17.localhost.local" :
		$conn=mysqli_connect('localhost','zigo_dbuser','zigo1234','oms_uae');
		$conn2=mysqli_connect('localhost','zigo_dbuser','zigo1234','oms_uae');
		if(mysqli_connect_errno()){  echo "Failed to connect to MySQL: " . mysqli_connect_error();  }	
		break;	
	
	//--------------------------The Phone Arcade-------------------------------//
	case "billing2.negoit.info" :
	$conn=mysqli_connect('localhost','phonearcade','kaluthara1124','thephonearcade');
	if(mysqli_connect_errno()){  echo "Failed to connect to MySQL: " . mysqli_connect_error();  }	
	break;
	
	case "billing2.negohosting.com" :
	$conn=mysqli_connect('localhost','phonearcade_dr','pho#ne1154','thephonearcade_dr');
	if(mysqli_connect_errno()){  echo "Failed to connect to MySQL: " . mysqli_connect_error();  }	
	break;
	
	case "billing2.localhost.local" :
	$conn=mysqli_connect('localhost','phonearcade','kaluthara1124','thephonearcade');
	if(mysqli_connect_errno()){  echo "Failed to connect to MySQL: " . mysqli_connect_error();  }	
	break;
	
	//---------------------------------Techneeds--------------------------------//
	case "billing13.negoit.info" :
	$conn=mysqli_connect('localhost','techneeds','tech#456248','techneeds');
	if(mysqli_connect_errno()){  echo "Failed to connect to MySQL: " . mysqli_connect_error();  }	
	break;
	
	case "billing13.negohosting.com" :
	$conn=mysqli_connect('localhost','techneeds_dr','tech@455178','techneeds_dr');
	if(mysqli_connect_errno()){  echo "Failed to connect to MySQL: " . mysqli_connect_error();  }	
	break;
	
	case "billing13.localhost.local" :
	$conn=mysqli_connect('localhost','techneeds','tech#456248','techneeds');
	if(mysqli_connect_errno()){  echo "Failed to connect to MySQL: " . mysqli_connect_error();  }	
	break;
	//---------------------------------Zigo Shop--------------------------------//
	case "billing4.negoit.info" :
	$conn=mysqli_connect('database-1.cluster-cvqsqpr9ukhw.ap-southeast-1.rds.amazonaws.com','zigo_dbuser','zigo1234','zigoshop');
	$conn2=mysqli_connect('database-1.cluster-ro-cvqsqpr9ukhw.ap-southeast-1.rds.amazonaws.com','zigo_dbuser','zigo1234','zigoshop');
	if(mysqli_connect_errno()){  echo "Failed to connect to MySQL: " . mysqli_connect_error();  }	
	break;

	case "billing4.localhost.local" :
	$conn=mysqli_connect('localhost','zigo_dbuser','zigo1234','zigoshop');
	$conn2=mysqli_connect('localhost','zigo_dbuser','zigo1234','zigoshop');
	if(mysqli_connect_errno()){  echo "Failed to connect to MySQL: " . mysqli_connect_error();  }	
	break;
	
	case "billing4.negohosting.com" :
	$conn=mysqli_connect('localhost','zigo_dbuser_dr','zngo@4951','zigoshop_dr');
	$conn2=mysqli_connect('localhost','zigo_dbuser_dr','zngo@4951','zigoshop_dr');
	if(mysqli_connect_errno()){  echo "Failed to connect to MySQL: " . mysqli_connect_error();  }	
	break;
	//---------------------------------OrientCare--------------------------------//
	case "billing14.negoit.info" :
	$conn=mysqli_connect('database-1.cluster-cvqsqpr9ukhw.ap-southeast-1.rds.amazonaws.com','orient_dbuser','orient#44512','orientcare');
	$conn2=mysqli_connect('database-1.cluster-ro-cvqsqpr9ukhw.ap-southeast-1.rds.amazonaws.com','orient_dbuser','orient#44512','orientcare');
	if(mysqli_connect_errno()){  echo "Failed to connect to MySQL: " . mysqli_connect_error();  }	
	break;

	case "billing14.localhost.local" :
	$conn=mysqli_connect('localhost','orient_dbuser','orient#44512','orientcare');
	$conn2=mysqli_connect('localhost','orient_dbuser','orient#44512','orientcare');
	if(mysqli_connect_errno()){  echo "Failed to connect to MySQL: " . mysqli_connect_error();  }	
	break;
	
	case "billing14.negohosting.com" :
	$conn=mysqli_connect('localhost','orient_dbuser_dr','orient#44512','orientcare_dr');
	$conn2=mysqli_connect('localhost','orient_dbuser_dr','orient#44512','orientcare_dr');
	if(mysqli_connect_errno()){  echo "Failed to connect to MySQL: " . mysqli_connect_error();  }	
	break;
	//---------------------------------EB Enterprises--------------------------------//
	case "billing15.negoit.info" :
	$conn=mysqli_connect('database-1.cluster-cvqsqpr9ukhw.ap-southeast-1.rds.amazonaws.com','ebenterprises_dbuser','eb#42195','eb_enterprises');
	$conn2=mysqli_connect('database-1.cluster-ro-cvqsqpr9ukhw.ap-southeast-1.rds.amazonaws.com','ebenterprises_dbuser','eb#42195','eb_enterprises');
	if(mysqli_connect_errno()){  echo "Failed to connect to MySQL: " . mysqli_connect_error();  }	
	break;

	case "billing15.localhost.local" :
	$conn=mysqli_connect('localhost','ebenterprises_dbuser','eb#42195','eb_enterprises');
	$conn2=mysqli_connect('localhost','ebenterprises_dbuser','eb#42195','eb_enterprises');
	if(mysqli_connect_errno()){  echo "Failed to connect to MySQL: " . mysqli_connect_error();  }	
	break;
	
	case "billing15.negohosting.com" :
	$conn=mysqli_connect('localhost','ebenterprises_dbuser_dr','eb#42195','eb_enterprises_dr');
	$conn2=mysqli_connect('localhost','ebenterprises_dbuser_dr','eb#42195','eb_enterprises_dr');
	if(mysqli_connect_errno()){  echo "Failed to connect to MySQL: " . mysqli_connect_error();  }	
	break;
	//---------------------------------Nanall Enterprises--------------------------------//
	case "billing16.negoit.info" :
	$conn=mysqli_connect('database-1.cluster-cvqsqpr9ukhw.ap-southeast-1.rds.amazonaws.com','nanall_dbuser','nan@75421','nanall_billing');
	$conn2=mysqli_connect('database-1.cluster-ro-cvqsqpr9ukhw.ap-southeast-1.rds.amazonaws.com','nanall_dbuser','nan@75421','nanall_billing');
	if(mysqli_connect_errno()){  echo "Failed to connect to MySQL: " . mysqli_connect_error();  }	
	break;

	case "billing16.localhost.local" :
	$conn=mysqli_connect('localhost','nanall_dbuser','nan@75421','nanall_billing');
	$conn2=mysqli_connect('localhost','nanall_dbuser','nan@75421','nanall_billing');
	if(mysqli_connect_errno()){  echo "Failed to connect to MySQL: " . mysqli_connect_error();  }	
	break;
	
	case "billing16.negohosting.com" :
	$conn=mysqli_connect('localhost','nanall_dbuser_dr','nan@75421','nanall_billing_dr');
	$conn2=mysqli_connect('localhost','nanall_dbuser_dr','nan@75421','nanall_billing_dr');
	if(mysqli_connect_errno()){  echo "Failed to connect to MySQL: " . mysqli_connect_error();  }	
	break;
	//---------------------------------Mobi Crown--------------------------------//
	case "billing20.negoit.info" :
	$conn=mysqli_connect('database-1.cluster-cvqsqpr9ukhw.ap-southeast-1.rds.amazonaws.com','mobicrown_dbuser','kms#46129','mobicrown');
	$conn2=mysqli_connect('database-1.cluster-ro-cvqsqpr9ukhw.ap-southeast-1.rds.amazonaws.com','mobicrown_dbuser','kms#46129','mobicrown');
	if(mysqli_connect_errno()){  echo "Failed to connect to MySQL: " . mysqli_connect_error();  }	
	break;

	case "billing20.localhost.local" :
	$conn=mysqli_connect('localhost','mobicrown_dbuser','kms#46129','mobicrown');
	$conn2=mysqli_connect('localhost','mobicrown_dbuser','kms#46129','mobicrown');
	if(mysqli_connect_errno()){  echo "Failed to connect to MySQL: " . mysqli_connect_error();  }	
	break;
	
	case "billing20.negohosting.com" :
	$conn=mysqli_connect('localhost','mobicrown_dbuser_dr','kms#46129','mobicrown_dr');
	$conn2=mysqli_connect('localhost','mobicrown_dbuser_dr','kms#46129','mobicrown_dr');
	if(mysqli_connect_errno()){  echo "Failed to connect to MySQL: " . mysqli_connect_error();  }	
	break;
	//---------------------------------Test--------------------------------//
	case "test1.negoit.info" :
	$conn=mysqli_connect('database-1.cluster-cvqsqpr9ukhw.ap-southeast-1.rds.amazonaws.com','nego_test','nego&1T521','test1_billing');
	$conn2=mysqli_connect('database-1.cluster-ro-cvqsqpr9ukhw.ap-southeast-1.rds.amazonaws.com','nego_test','nego&1T521','test1_billing');
	if(mysqli_connect_errno()){  echo "Failed to connect to MySQL: " . mysqli_connect_error();  }	
	break;
	
	case "test2.negoit.info" :
	$conn=mysqli_connect('database-1.cluster-cvqsqpr9ukhw.ap-southeast-1.rds.amazonaws.com','nego_test','nego&1T521','test2_billing');
	$conn2=mysqli_connect('database-1.cluster-ro-cvqsqpr9ukhw.ap-southeast-1.rds.amazonaws.com','nego_test','nego&1T521','test2_billing');
	if(mysqli_connect_errno()){  echo "Failed to connect to MySQL: " . mysqli_connect_error();  }	
	break;

	case "test.localhost.local" :
	$conn=mysqli_connect('localhost','test','test123','test');
	$conn2=mysqli_connect('localhost','test','test123','test');
	if(mysqli_connect_errno()){  echo "Failed to connect to MySQL: " . mysqli_connect_error();  }	
	break;
	
	case "test.negohosting.com" :
	$conn=mysqli_connect('localhost','negotest_dbuser_dr','nego&1T521','test_dr');
	$conn2=mysqli_connect('localhost','negotest_dbuser_dr','nego&1T521','test_dr');
	if(mysqli_connect_errno()){  echo "Failed to connect to MySQL: " . mysqli_connect_error();  }	
	break;
	//---------------------------------Test--------------------------------//
	case "dev.negoit.net" :
	$conn=mysqli_connect('localhost','negodev_dbuser','dev&T2180','dev_billing');
	$conn2=mysqli_connect('localhost','negodev_dbuser','dev&T2180','dev_billing');
	if(mysqli_connect_errno()){  echo "Failed to connect to MySQL: " . mysqli_connect_error();  }	
	break;
}

?>