<?php
// Lightweight target for an ALB target group health check.
// Confirms that Apache and PHP are running and able to serve HTTP requests.
// Kept independent of database connectivity so temporary DB maintenance,
// initialization, or schema migrations do not trigger an ASG termination loop.
http_response_code(200);
header('Content-Type: text/plain');
echo 'OK';

