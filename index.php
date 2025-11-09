<?php


// Kết nối database
require_once __DIR__ . '/database/database.php';

// Helpers (hàm hỗ trợ chung)
require_once __DIR__ . '/helpers/responseHelper.php';
require_once __DIR__ . '/helpers/mailHelper.php';
require_once __DIR__ . '/helpers/validatorHelper.php';



// Middlewares
require_once __DIR__ . '/middlewares/auth.middleware.php';
require_once __DIR__ . '/middlewares/role.middleware.php';

// Models
require_once __DIR__ . '/models/user.model.php';
require_once __DIR__ . '/models/role.model.php';
require_once __DIR__ . '/models/permission.model.php';

// Services
require_once __DIR__ . '/services/auth.service.php';
require_once __DIR__ . '/services/user.service.php';
require_once __DIR__ . '/services/role.service.php';
require_once __DIR__ . '/services/permission.service.php';

// Controllers
require_once __DIR__ . '/controllers/user.controller.php';
require_once __DIR__ . '/controllers/role.controller.php';
require_once __DIR__ . '/controllers/permission.controller.php';

// Routes
require_once __DIR__ . '/routes/auth.route.php';
require_once __DIR__ . '/routes/user.route.php';
require_once __DIR__ . '/routes/role.route.php';
require_once __DIR__ . '/routes/permission.route.php';


