<?php

$baseUrl = 'http://localhost:8000/api';
$results = [];
$testNum = 0;

function request($method, $url, $data = null, $token = null) {
    $opts = [
        'http' => [
            'method' => $method,
            'header' => "Content-Type: application/json\r\nAccept: application/json\r\n",
            'ignore_errors' => true,
            'timeout' => 10,
        ],
    ];
    if ($token) {
        $opts['http']['header'] .= "Authorization: Bearer $token\r\n";
    }
    if ($data) {
        $opts['http']['content'] = json_encode($data);
    }
    $context = stream_context_create($opts);
    $response = @file_get_contents($url, false, $context);
    
    $statusCode = 0;
    if (isset($http_response_header[0])) {
        preg_match('/(\d{3})/', $http_response_header[0], $m);
        $statusCode = (int)($m[1] ?? 0);
    }
    
    return [
        'status' => $statusCode,
        'body' => json_decode($response, true),
        'raw' => $response,
    ];
}

function test($name, $method, $url, $data, $token, $expectedStatus) {
    global $results, $testNum, $baseUrl;
    $testNum++;
    $fullUrl = $baseUrl . $url;
    
    $res = request($method, $fullUrl, $data, $token);
    $pass = $res['status'] === $expectedStatus;
    
    $icon = $pass ? '✅' : '❌';
    $results[] = [
        'num' => $testNum,
        'name' => $name,
        'method' => $method,
        'url' => $url,
        'expected' => $expectedStatus,
        'actual' => $res['status'],
        'pass' => $pass,
    ];
    
    echo "$icon #$testNum $name\n";
    echo "   $method $url -> {$res['status']} (expected $expectedStatus)\n";
    if (!$pass && $res['body']) {
        echo "   ERROR: " . json_encode($res['body'], JSON_UNESCAPED_UNICODE) . "\n";
    }
    
    return $res;
}

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║           KINAJÁ API - FULL ENDPOINT TEST SUITE            ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

// ══════════════════════════════════════════════════════════════
echo "═══ 1. AUTHENTICATION ═══\n\n";

// Register Client
$res = test(
    'Register Client User',
    'POST', '/register',
    ['name' => 'Cliente Teste', 'phone' => '+244900000001', 'password' => 'password', 'password_confirmation' => 'password', 'role' => 'client'],
    null, 201
);
$clientToken = $res['body']['token'] ?? null;
$clientId = $res['body']['user']['id'] ?? null;

// Register Restaurant Owner
$res = test(
    'Register Restaurant Owner',
    'POST', '/register',
    ['name' => 'Dono Restaurante', 'phone' => '+244900000002', 'password' => 'password', 'password_confirmation' => 'password', 'role' => 'restaurant_owner'],
    null, 201
);
$ownerToken = $res['body']['token'] ?? null;
$ownerId = $res['body']['user']['id'] ?? null;

// Register Driver
$res = test(
    'Register Driver User',
    'POST', '/register',
    ['name' => 'Motorista Teste', 'phone' => '+244900000003', 'password' => 'password', 'password_confirmation' => 'password', 'role' => 'driver'],
    null, 201
);
$driverToken = $res['body']['token'] ?? null;
$driverId = $res['body']['user']['id'] ?? null;

// Register Admin
$res = test(
    'Register Admin User',
    'POST', '/register',
    ['name' => 'Admin Teste', 'phone' => '+244900000004', 'password' => 'password', 'password_confirmation' => 'password', 'role' => 'admin'],
    null, 201
);
$adminToken = $res['body']['token'] ?? null;

// Login
$res = test(
    'Login with Client',
    'POST', '/login',
    ['phone' => '+244900000001', 'password' => 'password'],
    null, 200
);

// Login with wrong password
$res = test(
    'Login with Wrong Password',
    'POST', '/login',
    ['phone' => '+244900000001', 'password' => 'wrongpassword'],
    null, 401
);

// Get user profile
$res = test(
    'Get Authenticated User Profile',
    'GET', '/user',
    null, $clientToken, 200
);

// Unauthenticated access
$res = test(
    'Unauthenticated Access to /user',
    'GET', '/user',
    null, null, 401
);

echo "\n";

// ══════════════════════════════════════════════════════════════
echo "═══ 2. CATEGORIES ═══\n\n";

// List categories
$res = test(
    'List Categories',
    'GET', '/categories',
    null, $clientToken, 200
);

// Create category (admin)
$res = test(
    'Create Category (Admin)',
    'POST', '/categories',
    ['name' => 'Pizzas Test'],
    $adminToken, 201
);
$categoryId = $res['body']['id'] ?? null;

// Create another category
$res = test(
    'Create Category 2 (Admin)',
    'POST', '/categories',
    ['name' => 'Bebidas Test'],
    $adminToken, 201
);
$categoryId2 = $res['body']['id'] ?? null;

// Update category
$res = test(
    'Update Category (Admin)',
    'PUT', "/categories/$categoryId",
    ['name' => 'Pizzas Renamed'],
    $adminToken, 200
);

// Show category (extra route from apiResource)
$res = test(
    'Show Category',
    'GET', "/categories/$categoryId",
    null, $clientToken, 200
);

echo "\n";

// ══════════════════════════════════════════════════════════════
echo "═══ 3. RESTAURANTS ═══\n\n";

// Create restaurant (owner)
$res = test(
    'Create Restaurant (Owner)',
    'POST', '/restaurants',
    ['name' => 'Restaurante Luanda Grill', 'cuisine_type' => 'Angolana', 'prep_time_mins' => 25, 'is_open' => true],
    $ownerToken, 201
);
$restaurantId = $res['body']['id'] ?? null;

// Create second restaurant
$res = test(
    'Create Restaurant 2 (Owner)',
    'POST', '/restaurants',
    ['name' => 'Sushi Marginal', 'cuisine_type' => 'Japonesa', 'prep_time_mins' => 40, 'is_open' => true],
    $ownerToken, 201
);
$restaurantId2 = $res['body']['id'] ?? null;

// List restaurants (client)
$res = test(
    'List Open Restaurants (Client)',
    'GET', '/restaurants',
    null, $clientToken, 200
);

// Show restaurant details
$res = test(
    'Show Restaurant Details + Menu',
    'GET', "/restaurants/$restaurantId",
    null, $clientToken, 200
);

// Update restaurant
$res = test(
    'Update Restaurant (Owner)',
    'PUT', "/restaurants/$restaurantId",
    ['name' => 'Luanda Grill Premium', 'rating' => 4.5],
    $ownerToken, 200
);

echo "\n";

// ══════════════════════════════════════════════════════════════
echo "═══ 4. PRODUCTS ═══\n\n";

// Add product to restaurant
$res = test(
    'Add Product to Restaurant (Owner)',
    'POST', "/restaurants/$restaurantId/products",
    ['category_id' => $categoryId, 'name' => 'Pizza Margherita', 'description' => 'Tomate, mozzarella e manjericão', 'price' => 2500.00, 'is_available' => true],
    $ownerToken, 201
);
$productId = $res['body']['id'] ?? null;

// Add second product
$res = test(
    'Add Product 2 to Restaurant (Owner)',
    'POST', "/restaurants/$restaurantId/products",
    ['category_id' => $categoryId2, 'name' => 'Coca-Cola 330ml', 'price' => 300.00, 'is_available' => true],
    $ownerToken, 201
);
$productId2 = $res['body']['id'] ?? null;

// List products
$res = test(
    'List Products of Restaurant (Client)',
    'GET', "/restaurants/$restaurantId/products",
    null, $clientToken, 200
);

// Update product
$res = test(
    'Update Product (Owner)',
    'PUT', "/products/$productId",
    ['price' => 2800.00],
    $ownerToken, 200
);

echo "\n";

// ══════════════════════════════════════════════════════════════
echo "═══ 5. DRIVER PROFILE ═══\n\n";

// Create driver profile
$res = test(
    'Create Driver Profile',
    'POST', '/driver/profile',
    ['vehicle_type' => 'mota', 'license_plate' => 'LD-12-34-AB'],
    $driverToken, 200
);
$driverProfileId = $res['body']['id'] ?? null;

// Update driver profile
$res = test(
    'Update Driver Profile',
    'PUT', '/driver/profile',
    ['vehicle_type' => 'carro', 'license_plate' => 'LD-56-78-CD'],
    $driverToken, 200
);

// Toggle online
$res = test(
    'Toggle Driver Online',
    'PATCH', '/driver/toggle-online',
    null, $driverToken, 200
);
$isOnline = $res['body']['is_online'] ?? false;
echo "   Driver is_online: " . ($isOnline ? 'true' : 'false') . "\n";

// Update location
$res = test(
    'Update Driver Location',
    'PATCH', '/driver/location',
    ['lat' => -8.8383333, 'lng' => 13.2344444],
    $driverToken, 200
);

echo "\n";

// ══════════════════════════════════════════════════════════════
echo "═══ 6. ORDERS ═══\n\n";

// Place order (client)
$res = test(
    'Place Order (Client)',
    'POST', '/orders',
    [
        'restaurant_id' => $restaurantId,
        'delivery_fee' => 500.00,
        'items' => [
            ['product_id' => $productId, 'quantity' => 2, 'notes' => 'Sem cebola'],
            ['product_id' => $productId2, 'quantity' => 1, 'notes' => null],
        ]
    ],
    $clientToken, 201
);
$orderId = $res['body']['id'] ?? null;
if ($res['body']) {
    echo "   Order total: " . ($res['body']['total_amount'] ?? 'N/A') . "\n";
}

// List orders (client)
$res = test(
    'List Orders (Client)',
    'GET', '/orders',
    null, $clientToken, 200
);
echo "   Orders count: " . (is_array($res['body']) ? count($res['body']) : 'N/A') . "\n";

// Show order details
$res = test(
    'Show Order Details (Client)',
    'GET', "/orders/$orderId",
    null, $clientToken, 200
);

// Update order status: pending -> accepted (restaurant owner)
$res = test(
    'Accept Order (Owner: pending→accepted)',
    'PATCH', "/orders/$orderId/status",
    ['status' => 'accepted'],
    $ownerToken, 200
);

// Update order status: accepted -> preparing
$res = test(
    'Prepare Order (Owner: accepted→preparing)',
    'PATCH', "/orders/$orderId/status",
    ['status' => 'preparing'],
    $ownerToken, 200
);

// Update order status: preparing -> ready
$res = test(
    'Ready Order (Owner: preparing→ready)',
    'PATCH', "/orders/$orderId/status",
    ['status' => 'ready'],
    $ownerToken, 200
);

// List available orders (driver)
$res = test(
    'List Available Orders (Driver)',
    'GET', '/driver/available-orders',
    null, $driverToken, 200
);
echo "   Available orders: " . (is_array($res['body']) ? count($res['body']) : 'N/A') . "\n";

// Accept order (driver)
$res = test(
    'Accept Order for Delivery (Driver)',
    'PATCH', "/driver/orders/$orderId/accept",
    null, $driverToken, 200
);

// Update status: in_transit -> delivered
$res = test(
    'Deliver Order (Driver: in_transit→delivered)',
    'PATCH', "/orders/$orderId/status",
    ['status' => 'delivered'],
    $driverToken, 200
);

// Place another order to test cancel
$res = test(
    'Place Order 2 for Cancel Test',
    'POST', '/orders',
    [
        'restaurant_id' => $restaurantId,
        'delivery_fee' => 500.00,
        'items' => [
            ['product_id' => $productId, 'quantity' => 1],
        ]
    ],
    $clientToken, 201
);
$orderId2 = $res['body']['id'] ?? null;

// Cancel order
$res = test(
    'Cancel Order (Client)',
    'PATCH', "/orders/$orderId2/cancel",
    null, $clientToken, 200
);

// List orders (restaurant owner)
$res = test(
    'List Orders (Restaurant Owner)',
    'GET', '/orders',
    null, $ownerToken, 200
);

// List orders (driver)
$res = test(
    'List Orders (Driver)',
    'GET', '/orders',
    null, $driverToken, 200
);

echo "\n";

// ══════════════════════════════════════════════════════════════
echo "═══ 7. ADMIN ═══\n\n";

// Admin: list users
$res = test(
    'Admin: List All Users',
    'GET', '/admin/users',
    null, $adminToken, 200
);
echo "   Users count: " . (is_array($res['body']) ? count($res['body']) : 'N/A') . "\n";

// Admin: list orders
$res = test(
    'Admin: List All Orders',
    'GET', '/admin/orders',
    null, $adminToken, 200
);
echo "   Orders count: " . (is_array($res['body']) ? count($res['body']) : 'N/A') . "\n";

// Admin: dashboard
$res = test(
    'Admin: Dashboard Statistics',
    'GET', '/admin/dashboard',
    null, $adminToken, 200
);
if ($res['body'] && isset($res['body']['stats'])) {
    echo "   Stats: " . json_encode($res['body']['stats']) . "\n";
}

echo "\n";

// ══════════════════════════════════════════════════════════════
echo "═══ 8. CLEANUP / DESTRUCTIVE TESTS ═══\n\n";

// Delete product
$res = test(
    'Delete Product (Owner)',
    'DELETE', "/products/$productId2",
    null, $ownerToken, 204
);

// Delete category
$res = test(
    'Delete Category (Admin)',
    'DELETE', "/categories/$categoryId2",
    null, $adminToken, 204
);

// Delete restaurant
$res = test(
    'Delete Restaurant 2 (Owner)',
    'DELETE', "/restaurants/$restaurantId2",
    null, $ownerToken, 204
);

// Logout
$res = test(
    'Logout Client',
    'POST', '/logout',
    null, $clientToken, 200
);

// Verify token is revoked
$res = test(
    'Verify Token Revoked After Logout',
    'GET', '/user',
    null, $clientToken, 401
);

echo "\n";

// ══════════════════════════════════════════════════════════════
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║                     TEST RESULTS SUMMARY                   ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

$passed = array_filter($results, fn($r) => $r['pass']);
$failed = array_filter($results, fn($r) => !$r['pass']);

echo "Total: " . count($results) . " | ✅ Passed: " . count($passed) . " | ❌ Failed: " . count($failed) . "\n\n";

if (count($failed) > 0) {
    echo "FAILED TESTS:\n";
    foreach ($failed as $f) {
        echo "  ❌ #{$f['num']} {$f['name']} — {$f['method']} {$f['url']} (expected {$f['expected']}, got {$f['actual']})\n";
    }
}
