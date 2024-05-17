<?php
declare(strict_types=1);
namespace App\Controller;

use App\Http\Request;
use App\Http\Response;

interface ControllerInterface {
    public function __invoke(Request $req): Response;
}
?>
