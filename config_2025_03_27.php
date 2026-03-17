<?php

switch ($_SERVER['SERVER_NAME']) {
		//--------------------------ZigoBilling------------------------------------//
	case "billing1.negohosting.com":
		$conn = mysqli_connect('database-3.cluster-cvr3hgtayido.me-central-1.rds.amazonaws.com', 'zigo_dbuser', 'zigo1234', 'zigotech');
		$conn2 = mysqli_connect('database-3.cluster-ro-cvr3hgtayido.me-central-1.rds.amazonaws.com', 'zigo_dbuser', 'zigo1234', 'zigotech');
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		break;

	case "billing1.negoit.info":
		$conn = mysqli_connect('database-3.cluster-cvr3hgtayido.me-central-1.rds.amazonaws.com', 'zigo_dbuser', 'zigo1234', 'zigotech');
		$conn2 = mysqli_connect('database-3.cluster-ro-cvr3hgtayido.me-central-1.rds.amazonaws.com', 'zigo_dbuser', 'zigo1234', 'zigotech');
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		break;

	case "test1.negohosting.com":
		$conn = mysqli_connect('localhost', 'zigo_dbuser_dr', 'zngo@4951', 'zigobilling_dr');
		$conn2 = mysqli_connect('localhost', 'zigo_dbuser_dr', 'zngo@4951', 'zigobilling_dr');
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		break;

	case "billing25.negohosting.com":
		$conn = mysqli_connect('database-3.cluster-cvr3hgtayido.me-central-1.rds.amazonaws.com', 'zigo_dbuser', 'zigo1234', 'zigotech25');
		$conn2 = mysqli_connect('database-3.cluster-ro-cvr3hgtayido.me-central-1.rds.amazonaws.com', 'zigo_dbuser', 'zigo1234', 'zigotech25');
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		break;

	case "billing1.localhost.local":
		$conn = mysqli_connect('localhost', 'zigo_dbuser', 'zigo1234', 'zigobilling');
		$conn2 = mysqli_connect('localhost', 'zigo_dbuser', 'zigo1234', 'zigobilling');
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		break;


		//--------------------------OMS-UAE------------------------------------//
	case "billing17.negohosting.com":
		$conn = mysqli_connect('database-3.cluster-cvr3hgtayido.me-central-1.rds.amazonaws.com', 'zigo_dbuser', 'zigo1234', 'oms_uae');
		$conn2 = mysqli_connect('database-3.cluster-ro-cvr3hgtayido.me-central-1.rds.amazonaws.com', 'zigo_dbuser', 'zigo1234', 'oms_uae');
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		break;

	case "test17.negohosting.com":
		$conn = mysqli_connect('localhost', 'zigo_dbuser_dr', 'zngo@4951', 'oms_uae_dr');
		$conn2 = mysqli_connect('localhost', 'zigo_dbuser_dr', 'zngo@4951', 'oms_uae_dr');
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		break;

	case "billing17.localhost.local":
		$conn = mysqli_connect('localhost', 'zigo_dbuser', 'zigo1234', 'oms_uae');
		$conn2 = mysqli_connect('localhost', 'zigo_dbuser', 'zigo1234', 'oms_uae');
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		break;

		//---------------------------------Zigo Shop--------------------------------//
	case "billing4.negohosting.com":
		$conn = mysqli_connect('database-3.cluster-cvr3hgtayido.me-central-1.rds.amazonaws.com', 'zigo_dbuser', 'zigo1234', 'zigoshop');
		$conn2 = mysqli_connect('database-3.cluster-ro-cvr3hgtayido.me-central-1.rds.amazonaws.com', 'zigo_dbuser', 'zigo1234', 'zigoshop');
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		break;

	case "test4.negohosting.com":
		$conn = mysqli_connect('localhost', 'zigo_dbuser_dr', 'zngo@4951', 'zigoshop_dr');
		$conn2 = mysqli_connect('localhost', 'zigo_dbuser_dr', 'zngo@4951', 'zigoshop_dr');
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		break;

	case "billing4.localhost.local":
		$conn = mysqli_connect('localhost', 'zigo_dbuser', 'zigo1234', 'zigoshop');
		$conn2 = mysqli_connect('localhost', 'zigo_dbuser', 'zigo1234', 'zigoshop');
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		break;
		//---------------------------------TechNeeds--------------------------------//
	case "billing13.negohosting.com":
		$conn = mysqli_connect('database-3.cluster-cvr3hgtayido.me-central-1.rds.amazonaws.com', 'techneeds_dbuser', 'tech#76132', 'techneeds');
		$conn2 = mysqli_connect('database-3.cluster-ro-cvr3hgtayido.me-central-1.rds.amazonaws.com', 'techneeds_dbuser', 'tech#76132', 'techneeds');
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		break;

	case "test13.negohosting.com":
		$conn = mysqli_connect('localhost', 'techneeds_dbuser_dr', 'tech#76132', 'techneeds_dr');
		$conn2 = mysqli_connect('localhost', 'techneeds_dbuser_dr', 'tech#76132', 'techneeds_dr');
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		break;

	case "billing13.localhost.local":
		$conn = mysqli_connect('localhost', 'techneeds_dbuser', 'tech#76132', 'techneeds');
		$conn2 = mysqli_connect('localhost', 'techneeds_dbuser', 'tech#76132', 'techneeds');
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		break;
		//---------------------------------OrientCare--------------------------------//
	case "billing24.negohosting.com":
		$conn = mysqli_connect('database-3.cluster-cvr3hgtayido.me-central-1.rds.amazonaws.com', 'orient_dbuser', 'orient#44512', 'orientcare24');
		$conn2 = mysqli_connect('database-3.cluster-ro-cvr3hgtayido.me-central-1.rds.amazonaws.com', 'orient_dbuser', 'orient#44512', 'orientcare24');
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		break;

	case "billing14.negohosting.com":
		$conn = mysqli_connect('database-3.cluster-cvr3hgtayido.me-central-1.rds.amazonaws.com', 'orient_dbuser', 'orient#44512', 'orientcare');
		$conn2 = mysqli_connect('database-3.cluster-ro-cvr3hgtayido.me-central-1.rds.amazonaws.com', 'orient_dbuser', 'orient#44512', 'orientcare');
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		break;

	case "test14.negohosting.com":
		$conn = mysqli_connect('localhost', 'orient_dbuser_dr', 'orient#44512', 'orientcare_dr');
		$conn2 = mysqli_connect('localhost', 'orient_dbuser_dr', 'orient#44512', 'orientcare_dr');
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		break;

	case "billing14.localhost.local":
		$conn = mysqli_connect('localhost', 'orient_dbuser', 'orient#44512', 'orientcare');
		$conn2 = mysqli_connect('localhost', 'orient_dbuser', 'orient#44512', 'orientcare');
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		break;
		//---------------------------------OrientCare--------------------------------//
	case "billing14.negohosting.com":
		$conn = mysqli_connect('database-3.cluster-cvr3hgtayido.me-central-1.rds.amazonaws.com', 'orient_dbuser', 'orient#44512', 'orientcare');
		$conn2 = mysqli_connect('database-3.cluster-ro-cvr3hgtayido.me-central-1.rds.amazonaws.com', 'orient_dbuser', 'orient#44512', 'orientcare');
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		break;

	case "test14.negohosting.com":
		$conn = mysqli_connect('localhost', 'orient_dbuser_dr', 'orient#44512', 'orientcare_dr');
		$conn2 = mysqli_connect('localhost', 'orient_dbuser_dr', 'orient#44512', 'orientcare_dr');
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		break;

	case "billing14.localhost.local":
		$conn = mysqli_connect('localhost', 'orient_dbuser', 'orient#44512', 'orientcare');
		$conn2 = mysqli_connect('localhost', 'orient_dbuser', 'orient#44512', 'orientcare');
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		break;
		//---------------------------------EB Enterprises--------------------------------//
	case "billing15.negohosting.com":
		$conn = mysqli_connect('database-3.cluster-cvr3hgtayido.me-central-1.rds.amazonaws.com', 'ebenterprises_dbuser', 'eb#42195', 'eb_enterprises');
		$conn2 = mysqli_connect('database-3.cluster-ro-cvr3hgtayido.me-central-1.rds.amazonaws.com', 'ebenterprises_dbuser', 'eb#42195', 'eb_enterprises');
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		break;

	case "test15.negohosting.com":
		$conn = mysqli_connect('localhost', 'ebenterprises_dbuser_dr', 'eb#42195', 'eb_enterprises_dr');
		$conn2 = mysqli_connect('localhost', 'ebenterprises_dbuser_dr', 'eb#42195', 'eb_enterprises_dr');
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		break;

	case "billing15.localhost.local":
		$conn = mysqli_connect('localhost', 'ebenterprises_dbuser', 'eb#42195', 'eb_enterprises');
		$conn2 = mysqli_connect('localhost', 'ebenterprises_dbuser', 'eb#42195', 'eb_enterprises');
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		break;

		//---------------------------------Mobi Crown--------------------------------//
	case "billing20.negohosting.com":
		$conn = mysqli_connect('database-3.cluster-cvr3hgtayido.me-central-1.rds.amazonaws.com', 'mobicrown_dbuser', 'kms#46129', 'mobicrown');
		$conn2 = mysqli_connect('database-3.cluster-ro-cvr3hgtayido.me-central-1.rds.amazonaws.com', 'mobicrown_dbuser', 'kms#46129', 'mobicrown');
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		break;

	case "test20.negohosting.com":
		$conn = mysqli_connect('localhost', 'mobicrown_dbuser_dr', 'kms#46129', 'mobicrown_dr');
		$conn2 = mysqli_connect('localhost', 'mobicrown_dbuser_dr', 'kms#46129', 'mobicrown_dr');
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		break;

	case "billing20.localhost.local":
		$conn = mysqli_connect('localhost', 'mobicrown_dbuser', 'kms#46129', 'mobicrown');
		$conn2 = mysqli_connect('localhost', 'mobicrown_dbuser', 'kms#46129', 'mobicrown');
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		break;

		//---------------------------------Mobile Arcade--------------------------------//
	case "billing21.negohosting.com":
		$conn = mysqli_connect('database-3.cluster-cvr3hgtayido.me-central-1.rds.amazonaws.com', 'mobiarcade_dbuser', 'cdJ@39107', 'mobile_arcade');
		$conn2 = mysqli_connect('database-3.cluster-ro-cvr3hgtayido.me-central-1.rds.amazonaws.com', 'mobiarcade_dbuser', 'cdJ@39107', 'mobile_arcade');
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		break;

	case "test21.negohosting.com":
		$conn = mysqli_connect('localhost', 'mobiarcade_dbuser_dr', 'cdJ@39107', 'mobile_arcade_dr');
		$conn2 = mysqli_connect('localhost', 'mobiarcade_dbuser_dr', 'cdJ@39107', 'mobile_arcade_dr');
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		break;

	case "billing21.localhost.local":
		$conn = mysqli_connect('localhost', 'mobiarcade_dbuser', 'cdJ@39107', 'mobile_arcade');
		$conn2 = mysqli_connect('localhost', 'mobiarcade_dbuser', 'cdJ@39107', 'mobile_arcade');
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		break;

		//---------------------------------NegoIT-Test--------------------------------//
	case "billing26.negohosting.com":
		$conn = mysqli_connect('database-3.cluster-cvr3hgtayido.me-central-1.rds.amazonaws.com', 'nego_test', 'ggdjJs612#212', 'testbilling26');
		$conn2 = mysqli_connect('database-3.cluster-ro-cvr3hgtayido.me-central-1.rds.amazonaws.com', 'nego_test', 'ggdjJs612#212', 'testbilling26');
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		break;

		//---------------------------------Test--------------------------------//

	case "test.localhost.local":
		$conn = mysqli_connect('localhost', 'test', 'test123', 'test');
		$conn2 = mysqli_connect('localhost', 'test', 'test123', 'test');
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		break;

		//---------------------------------Test--------------------------------//
	case "dev.negoit.net":
		$conn = mysqli_connect('localhost', 'negodev_dbuser', 'dev&T2180', 'dev_billing');
		$conn2 = mysqli_connect('localhost', 'negodev_dbuser', 'dev&T2180', 'dev_billing');
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		break;

	case "negoit.negohosting.com":
		$conn = mysqli_connect('database-3.cluster-cvr3hgtayido.me-central-1.rds.amazonaws.com', 'nego_user', 'Hhksa@454187ca', 'negoit');
		$conn2 = mysqli_connect('database-3.cluster-ro-cvr3hgtayido.me-central-1.rds.amazonaws.com', 'nego_user', 'Hhksa@454187ca', 'negoit');
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		break;
}
