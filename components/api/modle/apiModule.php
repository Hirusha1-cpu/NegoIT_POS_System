<?php
function setSMSBalance($sub_system)
{
	include('config.php');
	$result = mysqli_query($conn, "SELECT value FROM settings WHERE setting='sms_data'");
	$row = mysqli_fetch_assoc($result);
	$sms_data = $row['value'];
	if ($sms_data == 'setting') {
		$query = "UPDATE `settings` SET `value`=`value`-1 WHERE `setting`='sms_balance'";
		$result = mysqli_query($conn, $query);
	} else {
		$query = "UPDATE `sub_system` SET `sms_balance`=`sms_balance`-1 WHERE `id`='$sub_system'";
		$result = mysqli_query($conn, $query);
	}
}

function smsStstusUpdate()
{
	$case = $_GET['ref1'];
	$ref2 = $_GET['ref2'];
	$st = $_GET['st'];
	if (($case != '') && ($ref2 != '') && ($st == 1)) {
		include('config.php');
		switch ($case) {
			case "bill":
				$query = "UPDATE `bill_main` SET `sms`='1' WHERE `invoice_no`='$ref2'";
				mysqli_query($conn, $query);
				$query = "SELECT sub_system FROM bill_main WHERE invoice_no='$ref2'";
				$row = mysqli_fetch_row(mysqli_query($conn, $query));
				setSMSBalance($row[0]);
				break;
			case "pay":
				$query = "UPDATE `payment` SET `sms`='1' WHERE `id`='$ref2'";
				mysqli_query($conn, $query);
				$query = "SELECT sub_system FROM payment WHERE id='$ref2'";
				$row = mysqli_fetch_row(mysqli_query($conn, $query));
				setSMSBalance($row[0]);
				break;
		}
	}
}

function smsPending()
{
	global $sms_pcount, $sms0_ref1, $sms0_ref2, $sms0_to, $sms0_text;
	$api_key = $_GET['api'];
	$today = dateNow();
	$sms_pcount = 0;
	$get_sms = true;
	$show_data = false;
	$sms0_ref1 = $sms0_ref2 = $sms0_to = $sms0_text = "";
	include('config.php');
	$query = "SELECT `value` FROM settings WHERE setting='sms_data'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$sms_data = $row[0];
	if ($sms_data == 'setting') {
		$sub_system_qry1 = $sub_system_qry2 = "";
		$query = "SELECT `value` FROM settings WHERE setting='sms_dev'";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		if ($api_key == $row[0])
			$show_data = true;
	} else {
		$query = "SELECT id FROM sub_system WHERE sms_dev='$api_key'";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		$sub_system = $row[0];
		$sub_system_qry1 = "AND bm.sub_system='$sub_system'";
		$sub_system_qry2 = "AND py.sub_system='$sub_system'";
		if ($row[0] != "")
			$show_data = true;
	}

	if ($show_data) {
		$query = "SELECT count(sm.id) FROM sms sm, bill_main bm WHERE sm.ref=bm.invoice_no AND sm.`case`='1' AND bm.`sms`='0' AND date(bm.billed_timestamp)='$today' $sub_system_qry1";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		$sms_pcount = $row[0];
		if ($row[0] > 0) {
			$get_sms = false;
			$query = "SELECT bm.invoice_no,cu.mobile,sm.`text` FROM sms sm, bill_main bm, cust cu WHERE sm.ref=bm.invoice_no AND cu.id=bm.`cust` AND sm.`case`='1' AND bm.`sms`='0' $sub_system_qry1 LIMIT 1";
			$row = mysqli_fetch_row(mysqli_query($conn, $query));
			$sms0_ref1 = 'bill';
			$sms0_ref2 = $row[0];
			$sms0_to = $row[1];
			$sms0_text = $row[2];
		}
		$query = "SELECT COUNT(sm.id) FROM sms sm, payment py WHERE sm.ref=py.id AND sm.`case`='2' AND py.`sms`='0' AND date(py.payment_date)='$today' $sub_system_qry2";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		$sms_pcount += $row[0];
		if (($row[0] > 0) && ($get_sms)) {
			$query = "SELECT py.id,cu.mobile,sm.`text` FROM sms sm, payment py, cust cu WHERE sm.ref=py.id AND cu.id=py.`cust` AND sm.`case`='2' AND py.`sms`='0' $sub_system_qry2 LIMIT 1";
			$row = mysqli_fetch_row(mysqli_query($conn, $query));
			$sms0_ref1 = 'bill';
			$sms0_ref2 = $row[0];
			$sms0_to = $row[1];
			$sms0_text = $row[2];
		}
	}

}

/**
 * Get the number of requests in the last hour for a specific API key
 */
function getRequestsInLastHour($conn2, $keyId)
{
	// For now, just return 0 since we're skipping API usage tracking
	return 0;
}

/**
 * Validate API key against MySQL database
 */
function validateApiKey($apiKey, $action = 'fetch_product_data')
{
	// Database configuration
	include('config.php');

	// Check connection
	if (!$conn2) {
		error_log("Database connection failed: " . mysqli_connect_error());
		return [
			'valid' => false,
			'message' => 'Error validating API key'
		];
	}

	// Escape the API key to prevent SQL injection
	$escapedApiKey = mysqli_real_escape_string($conn2, $apiKey);

	// Query to validate the API key
	$query = "SELECT * FROM api_keys
              WHERE `key` = '$escapedApiKey' AND is_active = 1
              AND (expires_at IS NULL OR expires_at > NOW())";

	$result = mysqli_query($conn2, $query);

	if (!$keyRecord = mysqli_fetch_assoc($result)) {
		mysqli_close($conn2);
		return [
			'valid' => false,
			'message' => 'Invalid or expired API key'
		];
	}

	// Check permissions if set
	if (!empty($keyRecord['permissions'])) {
		$permissions = json_decode($keyRecord['permissions'], true);

		if (!in_array($action, $permissions)) {
			mysqli_close($conn2);
			return [
				'valid' => false,
				'message' => 'API key does not have permission for this action'
			];
		}
	}

	// Check rate limit
	// $requestsInLastHour = getRequestsInLastHour($conn2, $keyRecord['id']);
	// if ($requestsInLastHour >= $keyRecord['rate_limit_per_hour']) {
	// 	mysqli_close($conn2);
	// 	return [
	// 		'valid' => false,
	// 		'message' => 'Rate limit exceeded'
	// 	];
	// }

	mysqli_close($conn2);

	return [
		'valid' => true,
		'key_id' => $keyRecord['id'],
		'key_record' => $keyRecord
	];
}

/**
 * Fetch product data from your own database based on product name
 * Requires valid API key for authentication
 * Returns JSON response with product information from your database
 */
function fetchProductsData()
{
	// Set response header
	header('Content-Type: application/json');

	// Get API key from request headers or POST data
	$apiKey = $_SERVER['HTTP_X_API_KEY'] ?? $_REQUEST['api_key'] ?? '';

	// Get product name from request
	$productName = $_REQUEST['product_name'] ?? '';
	$productCategory = $_REQUEST['product_category'] ?? '';
	$productCode = $_REQUEST['product_code'] ?? '';

	// Database configuration
	include('config.php');

	// Check connection
	if (!$conn2) {
		http_response_code(500);
		echo json_encode([
			'success' => false,
			'message' => 'Database connection failed',
			'error_code' => 'DB_CONNECTION_ERROR'
		]);
		return;
	}

	// Validate API key against database
	$keyValidation = validateApiKey($apiKey, 'fetch_product_data');

	if (!$keyValidation['valid']) {
		http_response_code(401);
		echo json_encode([
			'success' => false,
			'message' => $keyValidation['message'],
			'error_code' => 'INVALID_API_KEY'
		]);
		mysqli_close($conn2);
		return;
	}

	// Validate product name
	if (empty($productName)) {
		http_response_code(400);
		echo json_encode([
			'success' => false,
			'message' => 'Product name is required',
			'error_code' => 'MISSING_PRODUCT_NAME'
		]);
		mysqli_close($conn2);
		return;
	}

	// Sanitize input
	$productName = trim($productName);
	$escapedProductName = mysqli_real_escape_string($conn2, $productName);

	try {
		// Build search patterns
		$exactMatch = $escapedProductName;
		$startsWithMatch = $escapedProductName . '%';
		$containsMatch = '%' . $escapedProductName . '%';
		$wordsMatch = '%' . str_replace(' ', '%', $escapedProductName) . '%';

		// Improved query with ranking
		$query = "SELECT
					ii.id,
					ii.code,
					ii.description,
					MAX(iq.r_price) as default_price,
					CASE
						WHEN ii.code = '$exactMatch' THEN 1
						WHEN ii.code LIKE '$startsWithMatch' THEN 2
						WHEN ii.code LIKE '$containsMatch' THEN 3
						WHEN ii.code LIKE '$wordsMatch' THEN 4
						ELSE 5
					END as relevance
				FROM inventory_items ii
				INNER JOIN inventory_qty iq ON ii.id = iq.item
				WHERE (
					ii.code = '$productCategory' AND
					ii.code = '$exactMatch' OR
					ii.code LIKE '$startsWithMatch' OR
					ii.code LIKE '$containsMatch' OR
					ii.code LIKE '$wordsMatch'
				)
				AND ii.status = 1
				GROUP BY ii.id
				ORDER BY
					relevance ASC,
					LENGTH(ii.code) ASC,
					ii.code ASC";

		// Limit results
		$limit = isset($_REQUEST['limit']) ? (int) $_REQUEST['limit'] : 10;
		$query .= " LIMIT $limit";

		$result = mysqli_query($conn2, $query);

		if (!$result) {
			throw new Exception(mysqli_error($conn2));
		}

		$products = [];

		while ($row = mysqli_fetch_assoc($result)) {
			$product = [
				'id' => $row['id'],
				'code' => $row['code'] ?? '',
				'description' => $row['description'] ?? '',
				'default_price' => (float) ($row['default_price'] ?? 0),
			];

			$products[] = $product;
		}

		mysqli_close($conn2);

		echo json_encode([
			'success' => true,
			'data' => $products,
			'count' => count($products)
		]);

	} catch (Exception $e) {
		error_log("Error fetching product data: " . $e->getMessage());
		mysqli_close($conn2);

		http_response_code(500);
		echo json_encode([
			'success' => false,
			'message' => 'Failed to fetch product data: ' . $e->getMessage(),
			'error_code' => 'API_ERROR'
		]);
	}
}

/**
 * Get customers who purchased items in the last 6 months
 * Action/Permission name: get_customer_purchases
 */
function fetchProductSales()
{
	// Set response header
	header('Content-Type: application/json');

	// Get API key from request headers or POST data
	$apiKey = $_SERVER['HTTP_X_API_KEY'] ?? $_REQUEST['api_key'] ?? '';

	// Get product code from request
	$productCode = $_REQUEST['product_code'] ?? '';

	// Get optional parameters
	$limit = isset($_REQUEST['limit']) ? (int) $_REQUEST['limit'] : 50;
	$months = isset($_REQUEST['months']) ? (int) $_REQUEST['months'] : 6;


	// Get user location (optional)
	$userLat = isset($_REQUEST['user_lat']) ? (float) $_REQUEST['user_lat'] : null;
	$userLng = isset($_REQUEST['user_lng']) ? (float) $_REQUEST['user_lng'] : null;

	// Database configuration
	include('config.php');

	// Check connection
	if (!$conn2) {
		http_response_code(500);
		echo json_encode([
			'success' => false,
			'message' => 'Database connection failed',
			'error_code' => 'DB_CONNECTION_ERROR'
		]);
		return;
	}

	// Validate API key against database
	$keyValidation = validateApiKey($apiKey, 'fetch_product_sales');

	if (!$keyValidation['valid']) {
		http_response_code(401);
		echo json_encode([
			'success' => false,
			'message' => $keyValidation['message'],
			'error_code' => 'INVALID_API_KEY'
		]);
		mysqli_close($conn2);
		return;
	}

	// Validate product code
	if (empty($productCode)) {
		http_response_code(400);
		echo json_encode([
			'success' => false,
			'message' => 'product_code parameter is required',
			'error_code' => 'MISSING_PARAMETER'
		]);
		mysqli_close($conn2);
		return;
	}

	try {
		// Calculate date from X months ago
		$dateFrom = date('Y-m-d H:i:s', strtotime("-$months months"));
		$dateTo = date('Y-m-d H:i:s');

		// Query with optional distance calculation
		$distanceCalc = '';
		$orderBy = '';

		if ($userLat !== null && $userLng !== null) {
			// Calculate distance using Haversine formula
			$distanceCalc = "
                , (6371 * acos(
                    cos(radians($userLat))
                    * cos(radians(c.gps_x))
                    * cos(radians(c.gps_y) - radians($userLng))
                    + sin(radians($userLat))
                    * sin(radians(c.gps_x))
                )) as distance_km
            ";
			$orderBy = ' ORDER BY distance_km ASC, MAX(bm.order_timestamp) DESC';
		} else {
			$orderBy = ' ORDER BY MAX(bm.order_timestamp) DESC';
		}

		// Query with LEFT JOIN for nullable town
		$query = "SELECT
                c.id as shop_id,
                c.name as shop_name,
                c.shop_address as address,
                c.shop_tel as phone,
                c.gps_x as latitude,
                c.gps_y as longitude,
								SUM(b.qty) as sales_count,
                COALESCE(t.name, 'Unknown') as city,
                c.associated_town as city_id
								$distanceCalc
            FROM
                inventory_items ii
            INNER JOIN
                bill b ON b.item = ii.id
            INNER JOIN
                bill_main bm ON bm.invoice_no = b.invoice_no
            INNER JOIN
                cust c ON bm.cust = c.id
            LEFT JOIN
                town t ON c.associated_town = t.id
            WHERE
								c.`id` NOT IN(180) AND
                ii.code = '" . mysqli_real_escape_string($conn2, $productCode) . "'
                AND b.qty > 0
                AND bm.order_timestamp >= '" . $dateFrom . "'
                AND bm.order_timestamp <= '" . $dateTo . "'
            GROUP BY
                c.id
								$orderBy
            LIMIT " . $limit;
		// error_log("Product Sales Query: " . $query);

		$result = mysqli_query($conn2, $query);

		if (!$result) {
			throw new Exception("Query error: " . mysqli_error($conn2));
		}

		$shops = [];

		while ($row = mysqli_fetch_assoc($result)) {
			// Validate GPS coordinates
			$hasCoords = (float) $row['latitude'] !== 0 && (float) $row['longitude'] !== 0;

			$shop = [
				'id' => (int) $row['shop_id'],
				'shop_name' => $row['shop_name'],
				'address' => $row['address'],
				'city' => $row['city'],
				'phone' => $row['phone'],
				'sales_count' => (int) $row['sales_count'],
				'latitude' => (float) $row['latitude'],
				'longitude' => (float) $row['longitude'],
				'has_coordinates' => $hasCoords,
				'distance_km' => isset($row['distance_km']) ? round((float) $row['distance_km'], 2) : null,
			];

			$shops[] = $shop;
		}

		// Get summary statistics
		$summary = [
			'total_shops' => count($shops)
		];

		// Close database connection
		mysqli_close($conn2);

		// Return success response
		echo json_encode([
			'success' => true,
			'data' => $shops,
			'summary' => $summary,
			'user_location' => $userLat && $userLng ? [
				'latitude' => $userLat,
				'longitude' => $userLng
			] : null,
			'period' => [
				'from' => $dateFrom,
				'to' => $dateTo,
				'months' => $months
			],
			'product_code' => $productCode,
			'count' => count($shops)
		]);

	} catch (Exception $e) {
		// Log error for debugging
		error_log("Error fetching product sales: " . $e->getMessage());

		// Close database connection
		if ($conn2) {
			mysqli_close($conn2);
		}

		// Return error response
		http_response_code(500);
		echo json_encode([
			'success' => false,
			'message' => 'Failed to fetch product sales data',
			'error_code' => 'API_ERROR',
			'debug' => $e->getMessage()
		]);
	}
}

/**
 * Get shop details by shop IDs
 */
function fetchShopDetails(): void
{
	// Set response header
	header('Content-Type: application/json');

	// Get API key from request headers or POST data
	$apiKey = $_SERVER['HTTP_X_API_KEY'] ?? $_REQUEST['api_key'] ?? '';

	// Get shop IDs from request
	$shopIds = $_REQUEST['shop_ids'] ?? '';

	// Database configuration
	include('config.php');

	// Check connection
	if (!$conn2) {
		http_response_code(500);
		echo json_encode([
			'success' => false,
			'message' => 'Database connection failed',
			'error_code' => 'DB_CONNECTION_ERROR'
		]);
		return;
	}

	// Validate API key against database
	$keyValidation = validateApiKey($apiKey, 'fetch_shop_details');

	if (!$keyValidation['valid']) {
		http_response_code(401);
		echo json_encode([
			'success' => false,
			'message' => $keyValidation['message'],
			'error_code' => 'INVALID_API_KEY'
		]);
		mysqli_close($conn2);
		return;
	}

	// Validate shop IDs
	if (empty($shopIds)) {
		http_response_code(400);
		echo json_encode([
			'success' => false,
			'message' => 'shop_ids parameter is required',
			'error_code' => 'MISSING_PARAMETER'
		]);
		mysqli_close($conn2);
		return;
	}

	// Convert to array if comma-separated
	$shopIdArray = is_array($shopIds) ? $shopIds : explode(',', $shopIds);

	// Filter out empty values
	$shopIdArray = array_filter($shopIdArray, function ($id) {
		return !empty($id) && is_numeric($id);
	});

	if (empty($shopIdArray)) {
		http_response_code(400);
		echo json_encode([
			'success' => false,
			'message' => 'No valid shop IDs provided',
			'error_code' => 'INVALID_SHOP_IDS'
		]);
		mysqli_close($conn2);
		return;
	}

	try {
		// Create IN clause for SQL
		$shopIdList = implode(',', array_map('intval', $shopIdArray));

		// Query to get shop details
		$query = "SELECT
                    c.id as shop_id,
                    c.name as shop_name,
                    c.shop_address as address,
                    t.name as city,
                    c.gps_x as latitude,
                    c.gps_y as longitude
                FROM
                    cust c
                LEFT JOIN
                    town t ON c.associated_town = t.id
                WHERE
                    c.id IN ({$shopIdList})
                ORDER BY
                    c.name ASC";

		error_log("Shop Details Query: " . $query);

		$result = mysqli_query($conn2, $query);

		if (!$result) {
			throw new Exception("Query error: " . mysqli_error($conn2));
		}

		$shops = [];
		while ($row = mysqli_fetch_assoc($result)) {
			$shop = [
				'id' => (int) $row['shop_id'],
				'name' => $row['shop_name'] ?? 'Unknown Shop',
				'address' => $row['address'] ?? 'Address not available',
				'phone' => $row['phone'] ?? '',
				'city' => $row['city'] ?? '',
				'latitude' => $row['latitude'] ?? 0,
				'longitude' => $row['longitude'] ?? 0,
			];
			$shops[] = $shop;
		}

		// Close database connection
		mysqli_close($conn2);

		// Return success response
		echo json_encode([
			'success' => true,
			'data' => $shops,
			'count' => count($shops)
		]);

	} catch (Exception $e) {
		// Log error for debugging
		error_log("Error fetching shop details: " . $e->getMessage());

		// Close database connection
		if ($conn2) {
			mysqli_close($conn2);
		}

		// Return error response
		http_response_code(500);
		echo json_encode([
			'success' => false,
			'message' => 'Failed to fetch shop details',
			'error_code' => 'API_ERROR',
			'debug' => $e->getMessage()
		]);
	}
}

/**
 * Search for shops by name
 */
function searchShops(): void
{
	// Set response header
	header('Content-Type: application/json');

	// Get API key from request headers or POST data
	$apiKey = $_SERVER['HTTP_X_API_KEY'] ?? $_REQUEST['api_key'] ?? '';

	// Get search parameters from request
	$shopName = $_REQUEST['shop_name'] ?? '';
	$limit = (int) ($_REQUEST['limit'] ?? 20);

	// Database configuration
	include('config.php');

	// Check connection
	if (!$conn2) {
		http_response_code(500);
		echo json_encode([
			'success' => false,
			'message' => 'Database connection failed',
			'error_code' => 'DB_CONNECTION_ERROR'
		]);
		return;
	}

	// Validate API key against database
	$keyValidation = validateApiKey($apiKey, 'search_shops');

	if (!$keyValidation['valid']) {
		http_response_code(401);
		echo json_encode([
			'success' => false,
			'message' => $keyValidation['message'],
			'error_code' => 'INVALID_API_KEY'
		]);
		mysqli_close($conn2);
		return;
	}

	// Validate shop_name parameter
	if (empty($shopName)) {
		http_response_code(400);
		echo json_encode([
			'success' => false,
			'message' => 'shop_name parameter is required',
			'error_code' => 'MISSING_PARAMETER'
		]);
		mysqli_close($conn2);
		return;
	}

	// Validate limit
	if ($limit < 1 || $limit > 100) {
		$limit = 20;
	}

	try {
		// Escape search parameter for SQL
		$searchTerm = mysqli_real_escape_string($conn2, "%{$shopName}%");

		// Query to search shops by name
		$query = "SELECT
                    c.id as shop_id,
                    c.name as shop_name,
                    c.shop_address as address
                FROM
                    cust c
                LEFT JOIN
                    town t ON c.associated_town = t.id
                WHERE
                    c.name LIKE '%{$searchTerm}%'
                GROUP BY
                    c.id
                ORDER BY
                    c.name ASC
                LIMIT {$limit}";

		$result = mysqli_query($conn2, $query);

		if (!$result) {
			throw new Exception("Query error: " . mysqli_error($conn2));
		}

		$shops = [];
		while ($row = mysqli_fetch_assoc($result)) {
			$shop = [
				'id' => (int) $row['shop_id'],
				'name' => $row['shop_name'] ?? 'Unknown Shop',
				'address' => $row['address'] ?? 'Address not available',
			];
			$shops[] = $shop;
		}

		// Close database connection
		mysqli_close($conn2);

		// Return success response
		echo json_encode([
			'success' => true,
			'data' => $shops,
			'count' => count($shops),
			'search_term' => $shopName
		]);

	} catch (Exception $e) {
		// Log error for debugging
		error_log("Error searching shops: " . $e->getMessage());

		// Close database connection
		if ($conn2) {
			mysqli_close($conn2);
		}

		// Return error response
		http_response_code(500);
		echo json_encode([
			'success' => false,
			'message' => 'Failed to search shops',
			'error_code' => 'API_ERROR',
			'debug' => $e->getMessage()
		]);
	}
}

/**
 * Helper function to get recent purchases for a specific customer
 */
function getRecentPurchases($conn, $customerId, $limit = 3)
{
	$query = "
        SELECT
            p.id,
            p.purchase_date,
            p.total_amount,
            GROUP_CONCAT(pr.name SEPARATOR ', ') as products
        FROM
            purchases p
        JOIN
            purchase_items pi ON p.id = pi.purchase_id
        JOIN
            products pr ON pi.product_id = pr.id
        WHERE
            p.customer_id = $customerId
            AND p.status = 'completed'
        GROUP BY
            p.id
        ORDER BY
            p.purchase_date DESC
        LIMIT $limit
    ";

	$result = mysqli_query($conn, $query);
	$purchases = [];

	if ($result) {
		while ($row = mysqli_fetch_assoc($result)) {
			$purchases[] = [
				'id' => $row['id'],
				'purchase_date' => $row['purchase_date'],
				'total_amount' => (float) $row['total_amount'],
				'products' => $row['products']
			];
		}
	}

	return $purchases;
}

/**
 * Helper function to get purchase summary statistics
 */
function getPurchaseSummary($conn, $dateFrom)
{
	$query = "
        SELECT
            COUNT(DISTINCT c.id) as total_customers,
            COUNT(DISTINCT p.id) as total_purchases,
            SUM(p.total_amount) as total_revenue,
            AVG(p.total_amount) as avg_purchase_value
        FROM
            customers c
        JOIN
            purchases p ON c.id = p.customer_id
        WHERE
            p.purchase_date >= '$dateFrom'
            AND p.status = 'completed'
    ";

	$result = mysqli_query($conn, $query);

	if ($row = mysqli_fetch_assoc($result)) {
		return [
			'total_customers' => (int) $row['total_customers'],
			'total_purchases' => (int) $row['total_purchases'],
			'total_revenue' => (float) $row['total_revenue'],
			'avg_purchase_value' => (float) $row['avg_purchase_value']
		];
	}

	return [
		'total_customers' => 0,
		'total_purchases' => 0,
		'total_revenue' => 0,
		'avg_purchase_value' => 0
	];
}
?>