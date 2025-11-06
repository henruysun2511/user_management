<?php
// Kết nối database
require_once __DIR__ . '/database/database.php';

// Helpers (hàm hỗ trợ chung)
require_once __DIR__ . '/helpers/responseHelper.php';

// Middlewares
require_once __DIR__ . '/middlewares/auth.middleware.php';
require_once __DIR__ . '/middlewares/role.middleware.php';

// Models
require_once __DIR__ . '/models/user.model.php';
require_once __DIR__ . '/models/role.model.php';

// Services
require_once __DIR__ . '/services/auth.service.php';
require_once __DIR__ . '/services/user.service.php';
require_once __DIR__ . '/services/role.service.php';

// Controllers
require_once __DIR__ . '/controllers/user.controller.php';
require_once __DIR__ . '/controllers/role.controller.php';

// Routes
require_once __DIR__ . '/routes/auth.route.php';
require_once __DIR__ . '/routes/user.route.php';
<<<<<<< HEAD
require_once __DIR__ . '/routes/role.route.php';
=======
require_once __DIR__ . '/routes/permission.route.php';

>>>>>>> cf62a51f658d91cb8faf0425fd85d18679ce77cf
